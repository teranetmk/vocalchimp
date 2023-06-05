<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once FCPATH . 'vendor/autoload.php';

class Webhook extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));		
	}
	
	
	
	public function stripe() {
		$payment_setting_array = json_decode(my_global_setting('payment_setting'), 1);
		\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
		$endpoint_secret = $payment_setting_array['stripe_signing_secret'];
		$payload = @file_get_contents('php://input');
		$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
		$event = null;
		try {
			$event = \Stripe\Webhook::constructEvent(
			  $payload, $sig_header, $endpoint_secret
			);
		}
		catch(\UnexpectedValueException $e) {
			http_response_code(400);  //invalid payload
			exit();
		}
		catch(\Stripe\Exception\SignatureVerificationException $e) {
			http_response_code(400);  // invalid signature
			exit();
		}
		// Handle the event
		log_message('error',$event->type);
		switch ($event->type) {
			case 'checkout.session.completed' :
			  	$payment_callback = $event->data->object;
			  	$this->handle_successful_payment($payment_callback->id, $event->id, $payment_callback);
			  	break;
			case 'charge.succeeded' :
			  	$payment_callback = $event->data->object;
			  	log_message('error','hello i am here '.$payment_callback->id);
			  	break;
			case 'payment_intent.payment_failed' :
	            log_message('stripe payment failed');
	            /*$payment_callback = $event->data->object;
	            $query_subscription = $this->db->where('gateway_identifier', $payment_callback->id)->get('payment_subscription', 1);
	            if ($query_subscription->num_rows()) {
				  	$rs = $query_subscription->row();
	            }*/
			  	break;
			case 'order.payment_failed' :
	            log_message('stripe order.payment_failed');
	            /*$payment_callback = $event->data->object;
	            $query_subscription = $this->db->where('gateway_identifier', $payment_callback->id)->get('payment_subscription', 1);
	            if ($query_subscription->num_rows()) {
				  	$rs = $query_subscription->row();
	            }*/
			  	break;
			case 'customer.subscription.created' :
			  	$payment_callback = $event->data->object;
			  	$query_subscription = $this->db->where('gateway_identifier', $payment_callback->id)->get('payment_subscription', 1);
			    if ($query_subscription->num_rows()) {
				  	$rs = $query_subscription->row();

				  	//if status not active
				  	if($payment_callback->status=='active')
				  	{
				  		$this->db->where('gateway_identifier', $payment_callback->id)->update('payment_subscription', ['status'=>'active']);


				  		$query_user = $this->db->where('ids', $rs->user_ids)->get('user', 1);
					  	if ($query_user->num_rows()) {
						  $user = $query_user->row();
						  if($user->email_address!='')
						  {
						  	//remove user from mailchimp
							$this->remove_user_from_mailchimp($user->email_address);
						  }
						}
				  	}
			    }
			  break;
			case 'customer.subscription.updated' :
				log_message('error','i am here customer.subscription.updated');
			  $payment_callback = $event->data->object;
			  if ($payment_callback->status == 'active'){
				  $query_subscription = $this->db->where('gateway_identifier', $payment_callback->id)->order_by('id','desc')->get('payment_subscription', 1);
				  if ($query_subscription->num_rows()) {
					  $rs = $query_subscription->row();
					  $rs_subscription_item = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
					  $update_array = array(
						'start_time' => mdate('%Y-%m-%d %H:%i:%s', $payment_callback->current_period_start),
						'end_time' => mdate('%Y-%m-%d %H:%i:%s', $payment_callback->current_period_end),
						'updated_time' => my_server_time(),
						'stuff' => $rs_subscription_item->stuff_setting,
						'used_up' => 0
					  );
					  $this->create_subscription_updated_payment_log($payment_callback->id, $event->id); // create a payment log
				  }
				  log_message('error','new plan updated');
				  $this->db->where('gateway_identifier', $payment_callback->id)->where('id',$rs->id)->update('payment_subscription', $update_array);
			  }else{
				  $update_array = array('status' => 'expired', 'updated_time' => my_server_time());
				  $this->db->where('gateway_identifier', $payment_callback->id)->update('payment_subscription', $update_array);
			  }
			  break;
			case 'customer.subscription.deleted':
				$payment_callback = $event->data->object;
				if ($payment_callback->status == 'canceled' && $payment_callback->cancel_at_period_end) {

					if(isset($payment_callback->customer)){
						try{
							$payment_setting_array = json_decode($this->setting->payment_setting, 1);
							$stripe = new \Stripe\StripeClient($payment_setting_array['stripe_secret_key']);
							$stripe->customers->delete($payment_callback->customer,[]);
						}catch(Exception $e){
							log_message('error',$e->getMessage());
						}
					}
				}
			break;
		}
		http_response_code(200);
	}
	
	/*public function paypal() {
		$webhook_array = json_decode(file_get_contents('php://input'), TRUE);
		if (!json_last_error()) {
			$paypal_event = $webhook_array['event_type'];
			// use 'paypal_authorize_order' or 'retrieveSubscription' to verify the reality of the webhook event
			switch ($paypal_event) {
				case 'CHECKOUT.ORDER.APPROVED' :  // one-time
				  $paypal_order_id = $webhook_array['resource']['id'];
				  if ($this->paypal_authorize_order($paypal_order_id)) {
					  $this->handle_successful_payment($paypal_order_id, $webhook_array['id']);
				  }
				  break;
				case 'PAYMENT.SALE.COMPLETED' :  // recurring
				  $paypal_order_id = $webhook_array['resource']['billing_agreement_id'];
				  $rs_subscription = $this->db->where('gateway_identifier', $paypal_order_id)->get('payment_subscription', 1);
				  if ($rs_subscription->num_rows()) {  //update subscription
					  $this->load->library('m_paypal');
					  $subscription_arr = $this->m_paypal->retrieveSubscription($paypal_order_id);
					  if ($subscription_arr['success']) {
						  if ($subscription_arr['status'] == 'ACTIVE') {
							  $update_array = array(
								'start_time' => $subscription_arr['start_time'],
								'end_time' => $subscription_arr['end_time'],
								'updated_time' => my_server_time()
							  );
							  $this->create_subscription_updated_payment_log($paypal_order_id, $webhook_array['id']);
						  }
						  else {
							  $update_array = array('status' => 'expired', 'updated_time' => my_server_time());
						  }
						  $this->db->where('gateway_identifier', $paypal_order_id)->update('payment_subscription', $update_array);
					  }
				  }
				  else {
					  $this->handle_successful_payment($paypal_order_id, $webhook_array['id']);
				  }
				  break;
			}
		}
		http_response_code(200);
	}*/
	
	protected function handle_successful_payment($gateway_identifier, $gateway_event_id, $obj = '') { // $obj is only for subscription type
		$query = $this->db->where('gateway_identifier', $gateway_identifier)->where('gateway_event_id!=', $gateway_event_id)->where('callback_status', 'pending')->get('payment_log', 1);  //check gateway_event_id to prevent duplicated
		if ($query->num_rows()) {
			$rs = $query->row();
			$query_item = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1);
			if ($query_item->num_rows()) {
				$auto_renew = $query_item->row()->auto_renew;
			}
			else {
				$auto_renew = 0; //unexpected result
			}
			$this->db->where('id', $rs->id)->update('payment_log', array('gateway_event_id'=>$gateway_event_id, 'redirect_status'=>'success', 'callback_status'=>'success', 'callback_time'=>my_server_time()));
			$check_result = FALSE;
			switch ($rs->type) {
				case 'purchase' :
				  // log it
				  $insert_array = array(
					'ids' => my_random(),
					'user_ids' => $rs->user_ids,
					'payment_ids' => $rs->ids,
					'item_type' => 'purchase',
					'item_ids' => $rs->item_ids,
					'item_name' => $rs->item_name,
					'created_time' => my_server_time(),
					'stuff' => $rs->stuff,
					'used_up' => 0,
					'auto_renew' => $auto_renew
				  );
				  $this->db->insert('payment_purchased', $insert_array);
				  $check_result = TRUE;
				  break;
				case 'subscription' :
				  // Force expire all subscriptions
				  $this->db->update('payment_subscription', ['status' => 'expired'], ['user_ids' => $rs->user_ids]);
				  //build up subscription
				  switch ($rs->gateway) {
					  case 'stripe' :
						$subscription = $this->stripe_retrieve_subscription($obj->subscription);
						if ($subscription) {
							$insert_data = array(
							  'ids' => my_random(),
							  'item_ids' => $rs->item_ids,
							  'user_ids' => $rs->user_ids,
							  'payment_gateway' => $rs->gateway,
							  'gateway_identifier' => $obj->subscription,
							  'quantity' => $rs->quantity,
							  'status' => $subscription->status,
							  'start_time' => mdate('%Y-%m-%d %H:%i:%s', $subscription->current_period_start),
							  'end_time' => mdate('%Y-%m-%d %H:%i:%s', $subscription->current_period_end),
							  'created_time' => my_server_time(),
							  'updated_time' => my_server_time(),
							  'description' => '',
							  'stuff' => $rs->stuff,
							  'used_up' => 0,
							  'auto_renew' => $auto_renew
							);
							$this->db->insert('payment_subscription', $insert_data);
							$check_result = TRUE;
						}
					  /*case 'paypal' :
						$this->load->library('m_paypal');
						$subscription_arr = $this->m_paypal->retrieveSubscription($gateway_identifier);
						if ($subscription_arr['success']) {
							if ($subscription_arr['status'] == 'ACTIVE') {
								$rs_subscription = $this->db->where('gateway_identifier', $gateway_identifier)->get('payment_subscription', 1);
								if ($rs_subscription->num_rows()) {  //update subscription
									$update_array = array(
									  'start_time' => $subscription_arr['start_time'],
									  'end_time' => $subscription_arr['end_time'],
									  'updated_time' => my_server_time()
									);
									$this->db->where('gateway_identifier', $gateway_identifier)->update('payment_subscription', $update_array);
									$this->create_subscription_updated_payment_log($gateway_identifier, $gateway_event_id);
								}
								else {  //new subscription
									$insert_data = array(
									  'ids' => my_random(),
									  'item_ids' => $rs->item_ids,
									  'user_ids' => $rs->user_ids,
									  'payment_gateway' => $rs->gateway,
									  'gateway_identifier' => $gateway_identifier,
									  'quantity' => $rs->quantity,
									  'status' => 'active',
									  'start_time' => $subscription_arr['start_time'],
									  'end_time' => $subscription_arr['end_time'],
									  'created_time' => my_server_time(),
									  'updated_time' => my_server_time(),
									  'description' => '',
									  'used_up' => 0,
									  'stuff' => $rs->stuff,
									  'auto_renew' => $auto_renew
									);
									$this->db->insert('payment_subscription', $insert_data);
									$check_result = TRUE;
								//}	
							}
						}
						break;*/
				  }
				  break;
			}
			if ($check_result) {
				if (my_coupon_module() || my_affiliate_module()) {
					$this->load->helper('my_coupon_affiliate');
					my_coupon_applied($rs, $rs->coupon);
					my_affiliate_applied($rs);
				}
			}

			//send email at last step, ignore whether it's successfull, do confirm this operation is always at last step

			$rs_email = $this->db->where('purpose', 'pay_success')->get('email_template', 1)->row();
			$email = array(
			  'email_to' => my_user_setting($rs->user_ids, 'email_address'),
			  'email_subject' => $rs_email->subject,
			  'email_body' => str_replace('{{purchase_item}}', $rs->item_name, str_replace('{{purchase_price}}', $rs->currency . $rs->amount, $rs_email->body))
			);
			my_send_email($email);
		}
	}
	
	protected function remove_user_from_mailchimp($email) { 
		log_message('error','remove email from mailchimp: '.$email);
		$mcurl = 'https://us6.api.mailchimp.com/3.0/';
		$audienceID = '2d069deaaa';
		$apiKey 	= 'c466a425c3597cd0b87901b2a65244b1-us6';
    	$url = $mcurl.'lists/'.$audienceID.'/members/'.md5($email);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_USERPWD, "user:{$apiKey}");
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
	}
	
	/*protected function paypal_authorize_order($paypal_order_id) {
		$payment_setting_array = json_decode(my_global_setting('payment_setting'), 1);
		$paypal_clientid = $payment_setting_array['paypal_client_id'];
		$paypal_secret = $payment_setting_array['paypal_secret'];
		($payment_setting_array['type'] == 'sandbox') ? $paypal_environment = new \PayPalCheckoutSdk\Core\SandboxEnvironment($paypal_clientid, $paypal_secret) : $paypal_environment = new \PayPalCheckoutSdk\Core\ProductionEnvironment($paypal_clientid, $paypal_secret);
		$paypal_client = new \PayPalCheckoutSdk\Core\PayPalHttpClient($paypal_environment);
		$paypal_request = new \PayPalCheckoutSdk\Orders\OrdersAuthorizeRequest($paypal_order_id);
		$paypal_request->prefer('return=representation');
		try {
			$paypal_response = $paypal_client->execute($paypal_request);
			if ($paypal_response->result->status == 'COMPLETED') {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
		catch (\Exception $e) {
			return FALSE;
		}
	}*/
	
	
	
	protected function stripe_retrieve_subscription($subscription_id) {
		try {
			$subscription = \Stripe\Subscription::retrieve($subscription_id);
			return $subscription;
		}
		catch (\Exception $e) {
			return FALSE;
		}
	}
	
	
	
	public function create_subscription_updated_payment_log($gateway_identifier, $gateway_event_id) {  // no difference between different payment gateway
		$query = $this->db->where('gateway_identifier', $gateway_identifier)->where('gateway_event_id', $gateway_event_id)->get('payment_log', 1);
		if ($query->num_rows == 0) { // no duplicated record, create a new log
			$temporary_checking_time = date('Y-m-d H:i_s', strtotime(my_server_time()) - (5 * 60));
			$query_subscription = $this->db->where('gateway_identifier', $gateway_identifier)->where('created_time<', $temporary_checking_time)->get('payment_subscription', 1);
			if ($query_subscription->num_rows()) {
				$rs_subscription = $query_subscription->row();
				$rs_item = $this->db->where('ids', $rs_subscription->item_ids)->get('payment_item', 1)->row();
				$current_datetime = my_server_time();
				$insert_array = array(
				  'ids' => my_random(),
				  'user_ids' => $rs_subscription->user_ids,
				  'type' => 'subscription',
				  'gateway' => $rs_subscription->payment_gateway,
				  'currency' => $rs_item->item_currency,
				  'price' => $rs_item->item_price,
				  'quantity' => $rs_subscription->quantity,
				  'amount' => $rs_item->item_price * $rs_subscription->quantity,
				  'gateway_identifier' => $gateway_identifier,
				  'gateway_event_id' => $gateway_event_id,
				  'item_ids' => $rs_subscription->item_ids,
				  'item_name' => $rs_item->item_name,
				  'redirect_status' => 'success',
				  'callback_status' => 'success',
				  'created_time' => $current_datetime,
				  'callback_time' => $current_datetime,
				  'visible_for_user' => 1,
				  'generate_invoice' => 1,
				  'description' => '',
				  'stuff' => $rs_item->stuff_setting,
				  'coupon' => ''
				);
				$this->db->insert('payment_log', $insert_array);
			}
		}
		return TRUE;
	}














	// new scheme for payment starts from here
	// new scheme for payment starts from here
	// new scheme for payment starts from here
	
	protected function handlePayment($chargedArray, $logRS) {  //for all kinds of payment at the initial stage
		switch($logRS->type) {
			case 'top-up' :
			  my_user_reload($logRS->user_ids, 'Add', $logRS->currency, $logRS->price);  //reload the balance
			  break;
			case 'purchase' :
			  $this->handlePurchasedLog($logRS);  //insert the purchase log for further, possible use (not payment log)
			  break;
			case 'subscription' :
			  // no need to handle
			  break;
		}
		$this->handleCouponAffiliate($logRS);
		$this->handleNotification($logRS);
		$this->handlePaymentLog($chargedArray['eventID'], $logRS->id);
	}
	
	
	
	protected function handleNewSubscription($chargedArray, $logRS) {
		$this->load->library('m_payment');
		$subscriptionArray = $this->m_payment->retrieveSubscription($logRS->gateway, $chargedArray['identifier']);
		(array_key_exists('stuff', $chargedArray)) ? $stuff = $chargedArray['stuff'] : $stuff = '{}';
		(array_key_exists('authCode', $chargedArray)) ? $authCode = $chargedArray['authCode'] : $authCode = '';
		if ($subscriptionArray['success']) {
			if ($subscriptionArray['status'] == 'active') {
				$insertArray = array(
				  'ids' => my_random(),
				  'item_ids' => $logRS->item_ids,
				  'user_ids' => $logRS->user_ids,
				  'payment_gateway' => $logRS->gateway,
				  'gateway_identifier' => $chargedArray['identifier'],
				  'gateway_auth_code' => $authCode,
				  'quantity' => $logRS->quantity,
				  'status' => 'active',
				  'start_time' => $subscriptionArray['currentStartTime'],
				  'end_time' => $subscriptionArray['currentEndTime'],
				  'created_time' => $subscriptionArray['cycleStartTime'],
				  'updated_time' => my_server_time(),
				  'description' => '',
				  'stuff' => my_get_payment_item($logRS->item_ids, 'stuff_setting'),
				  'used_up' => 0,
				  'auto_renew' => my_get_payment_item($logRS->item_ids, 'auto_renew')
				);
				$this->db->insert('payment_subscription', $insertArray);
			}
		}
		return TRUE;
	}
	
	
	
	protected function handleUpdatedSubscription($subscriptionArray, $chargedArray) {
		$query = $this->db->where('gateway_identifier', $subscriptionArray['identifier'])->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$rsItem = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
			$insertArray = array(
			  'ids' => my_random(),
			  'user_ids' => $rs->user_ids,
			  'type' => 'subscription',
			  'gateway' => $rs->payment_gateway,
			  'currency' => $rsItem->item_currency,
			  'price' => $rsItem->item_price,
			  'quantity' => $rs->quantity,
			  'amount' => $chargedArray['amount'],
			  'gateway_identifier' => $subscriptionArray['identifier'],
			  'gateway_event_id' => $chargedArray['identifier'],
			  'item_ids' => $rs->item_ids,
			  'item_name' => $rsItem->item_name,
			  'redirect_status' => 'success',
			  'callback_status' => 'success',
			  'created_time' => my_server_time(),
			  'callback_time' => my_server_time(),
			  'visible_for_user' => 1,
			  'generate_invoice' => 1,
			  'description' => '',
			  'stuff' => my_get_payment_item($rs->item_ids, 'stuff_setting'),
			  'coupon' => ''
			);
			$updateArray = array(
			  'start_time' => $subscriptionArray['currentStartTime'],
			  'end_time' => $subscriptionArray['currentEndTime'],
			  'updated_time' => my_server_time(),
			  'status' => $subscriptionArray['status'],
			  'stuff' => my_get_payment_item($rs->item_ids, 'stuff_setting'),
			  'used_up' => 0
			);
			$this->db->insert('payment_log', $insertArray);
			$this->db->where('gateway_identifier', $subscriptionArray['identifier'])->update('payment_subscription', $updateArray);
		}
		return TRUE;
	}
	
	
	
	protected function handlePurchasedLog($logRS) {
		$insertArray = array(
		  'ids' => my_random(),
		  'user_ids' => $logRS->user_ids,
		  'payment_ids' => $logRS->ids,
		  'item_type' => 'purchase',
		  'item_ids' => $logRS->item_ids,
		  'item_name' => $logRS->item_name,
		  'created_time' => my_server_time(),
		  'stuff' => my_get_payment_item($logRS->item_ids, 'stuff_setting'),
		  'used_up' => 0,
		  'auto_renew' => my_get_payment_item($logRS->item_ids, 'auto_renew')
		);
		$this->db->insert('payment_purchased', $insertArray);
		return TRUE;
	}
	
	
	
	protected function handlePaymentLog($eventID, $logID) {
		$updateArray = array(
		  'gateway_event_id' => $eventID,
		  'redirect_status' => 'success',
		  'callback_status' => 'success',
		  'callback_time' => my_server_time()
		);
		$this->db->where('id', $logID)->update('payment_log', $updateArray);
		return TRUE;
	}

	
	
	protected function handleCouponAffiliate($logRS) {
		if (my_coupon_module() || my_affiliate_module()) {
			$this->load->helper('my_coupon_affiliate');
			my_coupon_applied($logRS, $logRS->coupon);
			my_affiliate_applied($logRS);
		}
		return TRUE;
	}
	
	
	protected function handleNotification($logRS) {
		$emailRS = $this->db->where('purpose', 'pay_success')->get('email_template', 1)->row();
		$email = array(
		  'email_to' => my_user_setting($logRS->user_ids, 'email_address'),
		  'email_subject' => $emailRS->subject,
		  'email_body' => str_replace('{{purchase_item}}', $logRS->item_name, str_replace('{{purchase_price}}', $logRS->currency . $logRS->amount, $emailRS->body))
		);
		my_send_email($email);
		return TRUE;
	}
	
	
	public function authorized() {
		switch ($this->uri->segment(3)) {
			case 'yoomoney' :
			  $paymentLogIds = '';
			  if (!empty($_SESSION['yoomoneyProcessingID'])) {
				  $paymentLogIds = $_SESSION['yoomoneyProcessingID'];
				  unset($_SESSION['yoomoneyProcessingID']);
			  }
			  redirect(base_url('user/pay_success/' . $paymentLogIds));
			  break;
			default:
			  redirect(base_url('user/pay_success/' . $this->uri->segment(4)));
		}
	}
	
	
	public function razorpay() {
		$payload = file_get_contents('php://input');
		// log_message('error', $payload);  //for debug only
		$payloadArray = json_decode($payload, 1);
		$this->load->library('m_payment');
		switch ($payloadArray['event']) {
			case 'payment.captured' :  //webhook of checkout
			  $paymentVerifyArray = $this->m_payment->verifyPayment('razorpay', $payloadArray);
			  if ($paymentVerifyArray['success']) {  //authorize the payment successfully
				  $query = $this->db->where('gateway_identifier', $paymentVerifyArray['identifier'])->where('callback_status', 'pending')->get('payment_log', 1);
				  if ($paymentVerifyArray['status'] == 'captured' && $query->num_rows()) {
					  $rs = $query->row();
					  if ($rs->amount*100 == $paymentVerifyArray['amount'] && $rs->currency == $paymentVerifyArray['currency']) {  //according to the guideline of Razorpay, these elements need to be verified.
						  $chargedArray['identifier'] = $payloadArray['payload']['payment']['entity']['notes']['identifier'];
						  $chargedArray['eventID'] = $payloadArray['payload']['payment']['entity']['id'];
						  $this->handlePayment($chargedArray, $rs);  //multiple steps in this process
					  }
				  }
			  }
			  break;
			case 'subscription.charged' : //webhook of recurring
			  $subscriptionArray = $this->m_payment->retrieveSubscription('razorpay', $payloadArray['payload']['subscription']['entity']['id']);
			  if ($subscriptionArray['success']) {
				  if ($payloadArray['payload']['subscription']['entity']['paid_count'] = 1) {  // first time of subscription
					  $chargedArray['identifier'] = $payloadArray['payload']['subscription']['entity']['id'];
					  $query = $this->db->where('gateway_event_id', $payloadArray['payload']['payment']['entity']['id'])->get('payment_log', 1);
					  if ($query->num_rows()) { $this->handleNewSubscription($chargedArray, $query->row());}
				  }
				  else {
					  $chargedArray['identifier'] = $payloadArray['payload']['payment']['entity']['id'];
					  $chargedArray['amount'] = $payloadArray['payload']['payment']['entity']['amount']/100;
					  $this->handleUpdatedSubscription($subscriptionArray, $chargedArray);
				  }
			  }
			  break;
		}
		http_response_code(200);
	}
	
	
	
	public function paystack() {
		$payload = file_get_contents('php://input');
		// log_message('error', $payload);  //for debug only
		$payloadArray = json_decode($payload, 1);
		$this->load->library('m_payment');
		switch ($payloadArray['event']) {
			case 'charge.success' : //pay successfully or subscription updated
			  $paymentVerifyArray = $this->m_payment->verifyPayment('paystack', $payloadArray);
			  if ($paymentVerifyArray['success']) {
				  if ($paymentVerifyArray['status']) {
					  $query = $this->db->where('gateway_identifier', $paymentVerifyArray['identifier'])->where('callback_status', 'pending')->get('payment_log', 1);
					  if ($query->num_rows()) { // pay successfully
						  $rs = $query->row();
						  $chargedArray['identifier'] = $payloadArray['data']['reference'];
						  $chargedArray['eventID'] = $payloadArray['data']['authorization']['authorization_code'];
						  $this->handlePayment($chargedArray, $rs);
					  }
					  else {  // subscription updated
						  $querySubscription = $this->db->where('gateway_auth_code', $payloadArray['data']['authorization']['authorization_code'])->get('payment_subscription', 1);
						  if ($querySubscription->num_rows()) {
							  $rsSubscription = $querySubscription->row();
							  $subscriptionArray = $this->m_payment->retrieveSubscription('paystack', $rsSubscription->gateway_identifier);
							  if ($subscriptionArray['success']) {
								  $chargedArray['identifier'] = $rsSubscription->gateway_identifier;
								  $chargedArray['amount'] = $payloadArray['data']['amount']/100;
								  $this->handleUpdatedSubscription($subscriptionArray, $chargedArray);
							  }
						  }
					  }
				  }
			  }
			  break;
			case 'subscription.create' : //webhook of the first time of recurring
			  $query = $this->db->where('gateway_event_id', $payloadArray['data']['authorization']['authorization_code'])->get('payment_log', 1);
			  if ($query->num_rows()) {
				  $rs = $query->row();
				  $chargedArray['identifier'] = $payloadArray['data']['subscription_code'];
				  $chargedArray['authCode'] = $payloadArray['data']['authorization']['authorization_code'];
				  $this->handleNewSubscription($chargedArray, $rs);
			  }
			  else {
				  http_response_code(400);
				  exit();
			  }
			  break;
		}
		http_response_code(200);
	}
	
	
	
	public function yoomoney() {  //yoomoney
		$payload = file_get_contents('php://input');
		// log_message('error', $payload);  //for debug only
		$payloadArray = json_decode($payload, 1);
		$this->load->library('m_payment');
		if ($payloadArray['event'] == 'payment.succeeded') {
			$paymentVerifyArray = $this->m_payment->verifyPayment('yoomoney', $payloadArray);
			if ($paymentVerifyArray['success']) {
				$query = $this->db->where('gateway_identifier', $payloadArray['object']['id'])->where('callback_status', 'pending')->get('payment_log', 1);
				if ($query->num_rows()) {  //pay for the first time, no matter one-time or recurring
					$rs = $query->row();
					$chargedArray['identifier'] = $payloadArray['object']['id'];
					$chargedArray['eventID'] = $payloadArray['object']['id'];
					$this->handlePayment($chargedArray, $rs);
					if ($rs->type == 'subscription') { $this->handleNewSubscription($chargedArray, $rs); }
				}
				else {  // maybe it's an update of recurring?
					$query_log = $this->db->where('gateway_event_id', $payloadArray['object']['id'])->get('payment_log', 1);
					if (!$query_log->num_rows()) {  //prevent scams of duplication
						$subscriptionArray = $this->m_payment->retrieveSubscription('yoomoney', $payloadArray['object']['payment_method']['id']);
						if ($subscriptionArray['success']) {
							$chargedArray['identifier'] = $payloadArray['object']['id'];
							$chargedArray['amount'] = $payloadArray['object']['amount']['value'];
							$this->handleUpdatedSubscription($subscriptionArray, $chargedArray);
						}
					}
				}
			}
		}
		http_response_code(200);
	}
	
	
}
