<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller {
    public function __construct() {
		parent::__construct();
		
    }
	
	
	public function index() {
		//
	}
	

	public function support() {
		my_load_view($this->setting->theme, 'User/support', $data);
	}
	
	
	public function my_profile() {
		$this->load->helper('my_tts');
		$data['rs'] = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
		$rs_payg = my_tts_generate_user_statitics();
		$data['payg_balance'] = $rs_payg->payg_balance;
		my_load_view($this->setting->theme, 'User/my_profile', $data);
	}
	
	
	
	public function my_profile_action() {
		my_check_demo_mode();  //check if it's in demo mode
		if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {  // admin modifies user's profile
		    my_check_demo_mode();  //check if it's in demo mode
			$ids = my_uri_segment(3);
		}
		else { //user modify his own profile
			$ids = $_SESSION['user_ids'];
		}
		// when update this part, don't forget to update api model to sync the same rule
		
		$data['rs'] = $this->db->where('ids', $ids)->get('user', 1)->row();
		$avatar_file = '';
		$this->form_validation->set_rules('email_address', my_caption('global_email_address'), 'trim|required|valid_email|max_length[50]|callback_email_duplicated_check[' . $ids . ']');
		if (my_post('username') != '') { $this->form_validation->set_rules('username', my_caption('global_username'), 'trim|min_length[5]|max_length[20]|alpha_dash|callback_username_duplicated_check[' . $ids . ']'); }
		$this->form_validation->set_rules('first_name', my_caption('mp_first_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('last_name', my_caption('mp_last_name_label'), 'trim|required|max_length[50]');
		$this->form_validation->set_rules('company', my_caption('mp_company_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('date_format', my_caption('mp_date_format_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('time_format', my_caption('mp_time_format_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('timezone', my_caption('mp_timezone_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('language', my_caption('mp_language_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('country', my_caption('mp_county_label'), 'trim|max_length[2]');
		$this->form_validation->set_rules('currency', my_caption('mp_currency_label'), 'trim|max_length[3]');
		$this->form_validation->set_rules('address_line_1', my_caption('mp_address_line_1_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('address_line_2', my_caption('mp_address_line_2_label'), 'trim|max_length[255]');
		$this->form_validation->set_rules('city', my_caption('mp_city_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('state', my_caption('mp_state_label'), 'trim|max_length[50]');
		$this->form_validation->set_rules('zip_code', my_caption('mp_zip_code_label'), 'trim|max_length[20]');
		$this->form_validation->set_rules('phone', my_caption('mp_phone_label'), 'trim|max_length[21]');
		if (isset($_FILES['userfile']['name'])) {
			if ($_FILES['userfile']['name'] != '') {
				$this->form_validation->set_rules('userfile', 'Upload File', 'callback_avatar_upload');
				$avatar_file = $_SESSION['user_ids'] . '.' . pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
			}
		}
		if ($this->form_validation->run() == FALSE) {
			$this->load->helper('my_tts');
			$this->session->set_flashdata('flash_danger', my_caption('global_something_failed'));
			$rs_payg = my_tts_generate_user_statitics($ids);
			$data['payg_balance'] = $rs_payg->payg_balance;
			if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {
				my_load_view($this->setting->theme, 'Admin/edit_user', $data);
			}
			else {
				my_load_view($this->setting->theme, 'User/my_profile', $data);
			}
		}
		else {
			$this->load->model('user_model');
			$res = $this->user_model->update_profile($ids, $avatar_file);
			if ($res['result']) {
				$this->session->set_flashdata('flash_success', $res['message']);
			}
			else {
				$this->session->set_flashdata('flash_danger', $res['message']);
			}
			if ($_SESSION['is_admin'] && my_uri_segment(3) != '') {
				redirect(base_url('admin/edit_user/' . my_uri_segment(3)));
			}
			else {
				redirect(base_url('user/my_profile'));
			}
		}
	}
	
	
	
	public function my_profile_impersonate_action() {
		$this->my_profile_action();
	}
	
	
	
	public function change_password() {
		my_load_view($this->setting->theme, 'User/change_password');
	}
	
	
	
	public function change_password_action() {
		my_check_demo_mode();  //check if it's in demo mode
		$throttle_check = my_throttle_check($_SESSION['user_ids']);
		if (!$throttle_check['result']) {
			$this->session->set_flashdata('flash_danger', $throttle_check['message']);
			redirect(base_url('user/change_password'));
		}
		else {
			$this->form_validation->set_rules('old_password', my_caption('cp_old_password_label'), 'trim|required|callback_check_old_password');
			if (!empty(my_post('new_password'))) {
				switch ($this->setting->psr) {
					case 'medium' :
					  $min_length = 8;
					  break;
					case 'strong' :
					  $min_length = 12;
					  break;
					default :
					  $min_length = 6;
				}
				$condition = 'trim|required|min_length[' . $min_length . ']|max_length[20]|callback_password_strength[' . $this->setting->psr . ']';
			}
			else {
				$condition = 'trim|required';
			}
			$this->form_validation->set_rules('new_password', my_caption('cp_new_password_label'), $condition);
			$this->form_validation->set_rules('new_password_confirm', my_caption('cp_new_password_confirm_label'), 'trim|required|matches[new_password]');
			if ($this->form_validation->run() == FALSE) {
				my_load_view($this->setting->theme, 'User/change_password');
			}
			else {
				my_log($_SESSION['user_ids'], 'Information', 'update-password', json_encode(my_ua()));  // log
				$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('password'=>my_hash_password(my_post('new_password'))));
				$this->session->set_flashdata('flash_success', my_caption('cp_success'));
				redirect(base_url('user/change_password'));
			}
		}
	}


	public function downgrade_with_card_on_file($planid) {
		$this->load->model('user_model');
		$user = $this->user_model->get_user($_SESSION['user_ids']);
		/**  Current **/
		if(isset($user) && ($user->pmid=='' || $user->stripecxid==''))
		{
			echo json_encode(['result' => 'error', 'title' => 'Error!', 'text'=> 'Please update your card details', 'redirect' => '/user/payment_info']);
		}else{
			$subs = $this->db->where('user_ids', $_SESSION['user_ids'])->where(['payment_gateway' => 'stripe'])->get('payment_subscription', 1)->result()[0];
			$subID = $subs->gateway_identifier;

			$pi = $this->db->where('ids', $planid)->get('payment_item', 1)->result()[0];
			$planprops = json_decode($pi->stuff_setting);

			/** Create new Subscription **/
			$payment_setting_array = json_decode($this->setting->payment_setting, 1);
			$apiKey = $payment_setting_array['stripe_secret_key'];
			\Stripe\Stripe::setApiKey($apiKey);
			$subscription = \Stripe\Subscription::retrieve($subID); // If it didn't work then it will throw error here.
			$stripe = new \Stripe\StripeClient($apiKey);
			$spm = $stripe->paymentMethods->attach($user->pmid, ['customer' => $user->stripecxid]);


			$subscription = \Stripe\SubscriptionSchedule::create([
			'customer' => $user->stripecxid,
			'start_date' => $subscription->current_period_end,
			'end_behavior' => 'release',
			'phases' => [
				[
				  'items' => [
				    [
				      'price' => $planprops->stripe_price_id,
				    ],
				  ],
				  'iterations' => 12,
				],
			]]);

			$subID = $subscription->id;
			// Cancel current subscription - set to pending
			my_payment_gateway_subscription_action('cancel', $subs, '/admin/CallBack');
			//
			$this->user_model->payment_log('stripe', $subID, $pi, $pi->item_price, 0);
			$this->handle_successful_payment($subID, false);
			echo json_encode(['result' => 'success', 'title' => 'Downgraded!', 'text' => 'Your plan will be downgraded to the chosen plan after the current plan expires.', 'redirect' => '/user/pay_now']);
		}

		
	}
	
	public function upgrade_with_card_on_file($planid) {
		
		$this->load->model('user_model');
		$subs = $this->db->where('user_ids', $_SESSION['user_ids'])->where(['payment_gateway' => 'stripe'])->get('payment_subscription', 1)->result()[0];
		$subID = $subs->gateway_identifier;
		$pi = $this->db->where('ids', $planid)->get('payment_item', 1)->result()[0];
		
		
		$planprops = json_decode($pi->stuff_setting);
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$apiKey = $payment_setting_array['stripe_secret_key'];

		try {
			
			// $subID = 'sub_JyNI3FS4rN2VOR';
			\Stripe\Stripe::setApiKey($apiKey);
			$subscription = \Stripe\Subscription::retrieve($subID);
			
			$coupon = my_get('coupon_id');
			
			if($coupon) {
			
				$percent_off = $this->db->where('couponid', $coupon)->get('promocodes', 1)->row()->percent_off;
				$item_price = $pi->item_price * ((100 - $percent_off) / 100);


				\Stripe\Subscription::update($subID, [
					'cancel_at_period_end' => false,
					'proration_behavior' => 'none',
					'coupon' => $coupon,
					'items' => [
						[
						
						'id' => $subscription->items->data[0]->id,
						
						'price' => $planprops->stripe_price_id,
						
						],
		
					],
					]);

			}else {

				\Stripe\Subscription::update($subID, [
					'cancel_at_period_end' => false,
					'proration_behavior' => 'none',
					'items' => [
						[
						
						'id' => $subscription->items->data[0]->id,
						
						'price' => $planprops->stripe_price_id,
						
						],
		
					],
					]);

					$item_price = $pi->item_price;
			}
			
				
				
			$this->user_model->payment_log('stripe', $subID, $pi, $item_price, 0);
			$subscription = \Stripe\Subscription::retrieve($subID);
			$this->handle_successful_payment($subID);
			echo json_encode(['result' => 'success', 'title' => 'Upgraded!', 'text'=>'Your plan was successfully upgraded!', 'redirect' => '/user/pay_now']);
		} catch (Exception $e) {
			
			echo json_encode(['result' => 'error', 'title' => 'Error!', 'text'=> $e->getMessage(), 'redirect' => '/user/pay_now']);
		}
		
	}
	
	public function my_activity_log() {
		my_load_view($this->setting->theme, 'User/my_activity');
	}
	
	
	
	public function my_notification() {
		($this->new_notification_tag) ? $this->db->where('ids', $_SESSION['user_ids'])->update('user', array('new_notification'=>0)) : null;
		my_load_view($this->setting->theme, 'User/my_notification');
	}
	
	
	
	public function my_notification_view() {
		$query = $this->db->where('ids', my_uri_segment(3))->group_start()->where('to_user_ids', $_SESSION['user_ids'])->or_where('to_user_ids', 'all')->group_end()->get('notification', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			($this->new_notification_tag) ? $this->db->where('ids', $_SESSION['user_ids'])->update('user', array('new_notification'=>0)) : null;
			($data['rs']->is_read == 0) ? $this->db->where('ids', $data['rs']->ids)->update('notification', array('is_read'=>1)) : null;
			my_load_view($this->setting->theme, 'Generic/view_notification', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function pay_now() {


		$coupon = my_get('c_bc');
		
		if ($coupon != '') {
			$c = $this->db->where('couponid', $coupon)->get('promocodes', 1)->row();
			if (!is_null($c)) {
				$data['coupon_id'] = $coupon;
				$data['coupon_percent_off'] = $c->percent_off;
				$data['coupon_code'] = $c->code;
			}
		}


		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		if (!empty($_SESSION['access_code'])) {
			$data['rs'] = $this->db->where('enabled', 1)->where('trash', 0)->where('access_code', $_SESSION['access_code'])->order_by('id', 'asc')->get('payment_item')->result();
		}
		else {
			$data['rs'] = $this->db->where('enabled', 1)->where('trash', 0)->where('access_code', '')->order_by('item_price', 'asc')->get('payment_item')->result();
		}
		$data['card_holder'] = false;
		$data['payment_gateway_stripe_one_time'] = $payment_setting_array['stripe_one_time_enabled'];
		$data['payment_gateway_stripe_recurring'] = $payment_setting_array['stripe_recurring_enabled'];
		//$data['payment_gateway_paypal_one_time'] = $payment_setting_array['paypal_one_time_enabled'];
		//$data['payment_gateway_paypal_recurring'] = $payment_setting_array['paypal_recurring_enabled'];
		$data['payment_gateway_one_time'] = 0;
		$data['payment_gateway_recurring'] = 0;
		$data['payment_gateway_name'] = my_caption('payment_pay_now');
		$qry = $this->db->where('user_ids', $_SESSION['user_ids'])->where('status !=', 'expired')->get('payment_subscription', 1);
		if ($qry) {
			$x = $qry->result()[0];
			if ($x->payment_gateway == 'stripe') {
				$data['card_holder'] = true;
			}
		}
		if (array_key_exists('addon_gateway', $payment_setting_array)) {
			$gateway = $payment_setting_array['addon_gateway'];
			if ($gateway) {
				array_key_exists($gateway . '_name', $payment_setting_array) ? $data['payment_gateway_name'] = my_caption('addons_payment_pay_with') . $payment_setting_array[$gateway . '_name'] : null;
				array_key_exists($gateway . '_one_time_enabled', $payment_setting_array) ? $data['payment_gateway_one_time'] = $payment_setting_array[$gateway . '_one_time_enabled'] : null;
				array_key_exists($gateway . '_recurring_enabled', $payment_setting_array) ? $data['payment_gateway_recurring'] = $payment_setting_array[$gateway . '_recurring_enabled'] : null;
			}
		}
		(!empty($payment_setting_array['tax_rate'])) ? $data['payment_tax_rate'] = $payment_setting_array['tax_rate'] : $data['payment_tax_rate'] = 0;
		$subdata = my_check_subscription($_SESSION['user_ids']);
		$data['current_price'] = ($subdata) ? $subdata->item_price : 0;
		my_load_view($this->setting->theme, 'User/pay_now', $data);
	}
	
	
	
	public function pay_retry() {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->where('redirect_status!=', 'success')->where('callback_status!=', 'success')->get('payment_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			($rs->type == 'purchase' || $rs->type == 'top-up') ? $pay_type = 'pay_once' : $pay_type = 'pay_recurring';
			($rs->coupon == '') ? $coupon = '' : $coupon = $rs->coupon . '/';
			(/*$rs->gateway == 'paypal' ||*/ $rs->gateway == 'stripe') ? $paymentGateway = $rs->gateway : $paymentGateway = 'gateway';
			redirect(base_url('user/' . $pay_type . '/' . $paymentGateway . '/' . $rs->item_ids . '/' . $coupon . '?quantity=' . $rs->quantity));
		}
		else {
			$this->session->set_flashdata('flash_danger', my_caption('payment_repay_unavailable'));
			redirect(base_url('user/pay_list'));
		}
	}
	
	
	
	public function pay_subscription_list() {
		
		my_load_view($this->setting->theme, 'User/pay_subscription_list');
	}
	
	
	
	public function pay_subscription_list_view() {
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$data['rs'] = $rs;
			$data['rs_item'] = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
			my_load_view($this->setting->theme, 'User/pay_subscription_list_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function pay_subscription_action() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$action = my_uri_segment(3);
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(4))->get('payment_subscription', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if ($rs->payment_gateway == 'stripe' || /*$rs->payment_gateway == 'paypal' ||*/ $rs->payment_gateway == 'manual') {  //legacy problem, will change soon
				$result = my_payment_gateway_subscription_action($action, $rs, 'user/pay_subscription_list');
			}
			else {
				$this->load->library('m_payment');
				$subscriptionArray['subscriptionID'] = $rs->gateway_identifier;
				$subscriptionArray['paymentGateway'] = $rs->payment_gateway;
				if ($action == 'cancel' || $action == 'cancel_now') {
					($action == 'cancel') ? $subscriptionArray['cancelNow'] = FALSE : $subscriptionArray['cancelNow'] = TRUE;
					$cancelledSubscriptionArray = $this->m_payment->cancelSubscription($subscriptionArray);
					if ($cancelledSubscriptionArray['success']) {
						($action == 'cancel_now') ? $status = 'expired' : $status = 'pending_cancellation';
						$this->db->where('ids', $rs->ids)->update('payment_subscription', array('status'=>$status));
						$result = '{"result":true, "title":"' . my_caption('global_cancelled_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('user/pay_subscription_list') .'"}';
					}
					else {  // fail to cancel
						$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
					}
				}
				elseif ($action == 'resume') {
					$resumedSubscriptionArray = $this->m_payment->resumeSubscription($subscriptionArray);
					if ($resumedSubscriptionArray['success']) {
						$this->db->where('gateway_identifier', $rs->gateway_identifier)->update('payment_subscription', array('status'=>'active'));
						$result = '{"result":true, "title":"' . my_caption('global_resumed_notice_title') . '", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"' . base_url('user/pay_subscription_list') .'"}';						
					}
					else {
						$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
					}
				}
				else {  // unrecignized command
					$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('payment_subscription_perform_fail') . '", "redirect":"CallBack"}';
				}
			}
		}
		else {  //no entry
			$result = '{"result":false, "title":"' . my_caption('global_failure') . '", "text":"'. my_caption('global_no_entries_found') . '", "redirect":"CallBack"}';
		}
		echo my_esc_html($result);
	}
	
	
	
	public function pay_list() {
		my_load_view($this->setting->theme, 'User/pay_list');
	}


	public function payment_info() {
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$publishableKey = $payment_setting_array['stripe_publishable_key'];
		$this->load->model('user_model');
		$user = $this->user_model->get_user($_SESSION['user_ids']);
		$pmid = $user->pmid;
		$cxid = $user->stripecxid;
		$last4 = $user->last4;
		$expyr = $user->ccexpyear;
		$expmn = $user->ccexpmonth;
		$name = $user->first_name . ' ' . $user->last_name;
		my_load_view($this->setting->theme, 'User/payment_info', ['publishable_key' => $publishableKey, 'name' => $name, 'mn' => $expmn, 'yr' => $expyr, 'last4' => $last4]);
	}

	public function payment_info_updateNew($token) {
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		// Setup Stripe
		$apiKey = $payment_setting_array['stripe_secret_key'];
		\Stripe\Stripe::setApiKey($apiKey);

		$this->load->model('user_model');
		// Add customer to Stripe
		$user = $this->user_model->get_user($_SESSION['user_ids']);
		$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
		if(is_null($user->stripecxid)) {
			try{
				$customer = $stripe->customers->create([
					'name'  => $user->first_name.' '.$user->last_name,
					'email' => $user->email_address,
					'source' => $token
				]);
				$customerID = $customer->id;
				$this->db->where('ids', $_SESSION['user_ids'])->update('user', ['stripecxid' => $customerID]);
			}catch(\Exception $e){
				log_message('error', $e->getMessage());
				echo json_encode([
					'error' => true,
					'message' => $e->getMessage(),
					'data'=> null
				]);die;
			}
			
		} else {
			$customerID = $user->stripecxid;
			try{
				$customer = $stripe->customers->update($customerID, ['source' => $token]);
			}catch(\Exception $e){
				log_message('error', $e->getMessage());
				echo json_encode([
					'error' => true,
					'message' => $e->getMessage(),
					'data'=> null
				]);die;
			}
		}
		print_r($customer);die;
		echo 'card updated';
		//
	}

	public function payment_info_update($token) {
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$this->load->model('user_model');
		$user = $this->user_model->get_user($_SESSION['user_ids']);
		$cxid = $user->stripecxid;
		$existingpmid = $user->pmid;
		$stripe = new \Stripe\StripeClient(['api_key' => $payment_setting_array['stripe_secret_key'], 'stripe_version' => '2020-08-27']);
		$t = $stripe->paymentMethods->retrieve($token);
		$stripe->paymentMethods->attach($token, ['customer' => $cxid]);
		try{
			$stripe->paymentMethods->detach($existingpmid);
		}catch(\Exception $e){
			//log_message('error', $e->getMessage());
			echo json_encode([
				'error' => true,
				'message' => $e->getMessage()
			]);die;
		}
		
		//update customer card in user table
		$expmon = $t->card->exp_month;
		$expyer = $t->card->exp_year;
		$last4 = $t->card->last4;
		$this->db->update('user', ['pmid' => $token, 'ccexpmonth' => $expmon, 'ccexpyear' => $expyer, 'last4' => $last4], ['ids' => $_SESSION['user_ids']]);
		echo json_encode([
				'error' => false,
				'message' => 'success'
			]);die;
	}

	public function pay_recurring() {
		my_check_demo_mode();  //check if it's in demo mode
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$quantity = html_purify(my_get('quantity'));
		(!is_int($quantity)) ? $quantity = 1 : $quantity = abs($quantity);
		$query = $this->db->where('ids', my_uri_segment(4))->where('enabled', 1)->where('type', 'subscription')->get('payment_item', 1);
		$rs = $query->row();
		$this->check_pay_condition($rs);
		$this->load->model('user_model');
		$item_price = $rs->item_price;
		if (my_coupon_module() && my_uri_segment(5) != '') {  //coupon enabled, calculate the new price if necessary
			$coupon_array = my_coupon_check($rs->ids, my_uri_segment(5));
			($coupon_array['result']) ? $item_price = $coupon_array['amount'] : null;
		}
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$item_stuff_array = json_decode($rs->stuff_setting, 1);
		if (!empty($payment_setting_array['tax_rate'])) {
			$tax = $payment_setting_array['tax_rate'];
			if (strtolower($rs->item_currency) == 'jpy') {
				($tax) ? $item_price = round($item_price * (1 + $tax/100), 2) : null;  //tax
			}
			else {
				($tax) ? $item_price = number_format(round($item_price * (1 + $tax/100), 2), 2) : null;  //tax
			}
		}
		else {
			$tax = 0;
		}
		if (my_uri_segment(3) == 'stripe' && $payment_setting_array['stripe_recurring_enabled']) {
			\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
			(!array_key_exists('stripe_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['stripe_product_id'];
			try {
				$product = \Stripe\Product::retrieve($product_id);
				($product->active) ? $product_id = $product->id : null;
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				$product_id = 'foo';
            }
			if ($product_id == 'foo') { //create product at stripe if product doesn't exist
				try {
					$product = \Stripe\Product::create(['name'=>$rs->item_name]);
					$product_id = $product->id;
					my_save_payment_item_stuff_setting(my_uri_segment(4), 'stripe_product_id', $product_id);
				}
				catch (\Exception $e) {
					log_message('error', $e->getMessage());
					die(my_caption('payment_exception'));
				}
			}
			//step 2: try to retrieve the price related to the product from stripe, Create one if it doesn't exist, The price should exist at the end of this step.
			(!array_key_exists('stripe_price_id', $item_stuff_array)) ? $price_id = 'foo' : $price_id = $item_stuff_array['stripe_price_id'];
			try {
				$price = \Stripe\Price::retrieve($price_id);
				($price->unit_amount == $item_price * 100) ? $price_id = $price->id : $price_id = 'foo';
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				$price_id = 'foo';
			}
			if ($price_id == 'foo') { //create price at stripe if price doesn't exist
				try {
					$stripe_amount = $item_price * 100;
					(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
					$price = \Stripe\Price::create([
					  'unit_amount' => $stripe_amount,
					  'currency' => strtolower($rs->item_currency),
					  'recurring' => [
					    'interval' => $rs->recurring_interval,
						'interval_count' => $rs->recurring_interval_count
					  ],
					  'product' => $product_id
					]);
					$price_id = $price->id;
					my_save_payment_item_stuff_setting(my_uri_segment(4), 'stripe_price_id', $price_id);
				}
				catch (\Exception $e) {
					log_message('error', $e->getMessage());
					die(my_caption('payment_exception'));
				}
			}
			//step 3: create subscription and redirect to stripe checkout
			try {
				$checkout_session = \Stripe\Checkout\Session::create([
				  'payment_method_types' => ['card'],
				  'line_items' => [[
				    'price' => $price_id,
					'quantity' => 1
				  ]],
				  'mode' => 'subscription',
				  'success_url' => base_url('/user/pay_success/{CHECKOUT_SESSION_ID}'),
				  'cancel_url' => base_url('/user/pay_cancel/{CHECKOUT_SESSION_ID}'),
				]);
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				die(my_caption('payment_exception'));
			}
			$data['publishable_key'] = $payment_setting_array['stripe_publishable_key'];
			$data['checkout_session'] = $checkout_session['id'];
			$this->user_model->payment_log('stripe', $checkout_session['id'], $rs, $item_price, $tax);
			my_load_view($this->setting->theme, 'User/pay_stripe', $data); //redirect to payment page
		}
		/*elseif (my_uri_segment(3) == 'paypal' && $payment_setting_array['paypal_recurring_enabled']) {
			$this->load->library('m_paypal');
			//handle product
			(!array_key_exists('paypal_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['paypal_product_id'];
			if ($product_id != 'foo') {
				$check_result_array = $this->m_paypal->retrieveProduct($product_id);
				
				(!$check_result_array['success']) ? $product_id = 'foo': null;
				
			}
			if ($product_id == 'foo') {  //need to create a new product
				$check_result_array = $this->m_paypal->newProduct(array('name'=>$rs->item_name));
				if ($check_result_array['success']) {
					$product_id = $check_result_array['product_id'];
					my_save_payment_item_stuff_setting(my_uri_segment(4), 'paypal_product_id', $product_id); //new generated, save the product_id
				}
			}
			//handle plan
			(!array_key_exists('paypal_plan_id', $item_stuff_array)) ? $plan_id = 'foo' : $plan_id = $item_stuff_array['paypal_plan_id'];
			if ($plan_id != 'foo') {
				$check_result_array = $this->m_paypal->retrievePlan($plan_id);
				if ($check_result_array['success']) {
					($check_result_array['plan_price'] != $item_price) ? $plan_id = 'foo' : null;
				}
				else {
					$plan_id = 'foo';
				}
			}
			if ($plan_id == 'foo') {  //need to create a new plan
			    $planArray = array(
				  'product_id' => $product_id,
				  'product_name' => $rs->item_name,
				  'interval_unit' => strtoupper($rs->recurring_interval),
				  'interval_count' => $rs->recurring_interval_count,
				  'price' => $item_price,
				  'currency' => strtoupper($rs->item_currency)
				);
				$check_result_array = $this->m_paypal->newPlan($planArray);
				if ($check_result_array['success']) {
					$plan_id = $check_result_array['plan_id'];
					my_save_payment_item_stuff_setting(my_uri_segment(4), 'paypal_plan_id', $plan_id);  //new generated, save the plan_id
				}
			}
			//submit subscription
			$subscriptionArray = array(
			  'plan_id' => $plan_id,
			  'return_url' => base_url('/user/pay_success/'),
			  'cancel_url' => base_url('/user/pay_cancel/')
			);
			$subscribe_result_array = $this->m_paypal->newSubscription($subscriptionArray);
			if (!array_key_exists('subscription_id', $subscribe_result_array)) {
				echo 'Unable to handle the payment, Please check the api credentials or network connection.';
			}
			else {
				$this->user_model->payment_log('paypal', $subscribe_result_array['subscription_id'], $rs, $item_price, $tax);
				redirect($subscribe_result_array['redirectURL']);
			}
		}*/
		elseif (my_uri_segment(3) == 'gateway') { //other payment gateways, it only works with the Payment Gateway Add-on
			$this->load->library('m_payment');
			$this->m_payment->pay('recurring', $rs, $item_price, $tax);  //item_price depends on coupon, tax denpends on coupon and tax setting, so item_price is different from the item's original price
		}
		else {
			echo my_caption('payment_payment_gateway_unavailable');
		}
	}


	public function pay_credit_card($items_id) {
		$query = $this->db->where('ids', $items_id)->where('enabled', 1)->where('type', 'subscription')->get('payment_item', 1);
		$rs = $query->row();
	
		$this->load->model('user_model');
		$item_price = $rs->item_price;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$item_stuff_array = json_decode($rs->stuff_setting, 1);
		// Setup Stripe
		$apiKey = $payment_setting_array['stripe_secret_key'];
		//\Stripe\Stripe::setApiKey($apiKey);
		
		// Add customer to Stripe
		$user = $this->user_model->get_user($_SESSION['user_ids']);
		$publishable_key = $payment_setting_array['stripe_publishable_key'];

		$coupon = my_get('c_bc');
		
		if ($coupon != '') {
			$c = $this->db->where('couponid', $coupon)->get('promocodes', 1)->row();
			if (!is_null($c)) {
				$configOptions['coupon'] = $coupon;
				$sdata['coupon'] = $coupon;
				$sdata['percent_off'] = $c->percent_off;
				$sdata['code'] = $c->code;
 
				// $sdata['percent_off'] = 10;//let'say  10% discount okay?
				$item_price = $rs->item_price * ((100 - $sdata['percent_off']) / 100);
				
			}
		}

		//$sdata['clientSecret'] = $payment_intent->client_secret;
		$sdata['name'] = $user->first_name . ' ' . $user->last_name;
		$sdata['pricing'] = $rs;
		$sdata['items_id'] = $items_id;
		$sdata['publishable_key'] = $publishable_key;
		$sdata['client_secret'] = $apiKey;
		$sdata['c_bc'] = $coupon;
		
		my_load_view($this->setting->theme, 'User/pay_credit_card', $sdata);
	}

	public function create_subscription()
	{
		$post = $this->input->post();
		if(!isset($_POST['token']))
		{
			echo json_encode(['error' => true,'message' => 'Payment Token missing']);die;
		}
		$items_id = $post['items_id'];
		$token = $post['token'];
		$query = $this->db->where('ids', $items_id)->where('enabled', 1)->where('type', 'subscription')->get('payment_item', 1);
		$rs = $query->row();

		$this->load->model('user_model');
		$item_price = $rs->item_price;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$item_stuff_array = json_decode($rs->stuff_setting, 1);
		// Setup Stripe
		$apiKey = $payment_setting_array['stripe_secret_key'];
		\Stripe\Stripe::setApiKey($apiKey);
		
		// Add customer to Stripe
		$user = $this->user_model->get_user($_SESSION['user_ids']);

		$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
		
		if(is_null($user->stripecxid)) {
			try{
				$customer = $stripe->customers->create([
					'name'  => $user->first_name.' '.$user->last_name,
					'email' => $user->email_address,
					'source' => $token
				]);
				$customerID = $customer->id;
				$this->db->where('ids', $_SESSION['user_ids'])->update('user', ['stripecxid' => $customerID]);
			}catch(\Exception $e){
				log_message('error', $e->getMessage());
				echo json_encode([
					'error' => true,
					'message' => $e->getMessage(),
					'data'=> null
				]);die;
			}
			
		} else {
			$customerID = $user->stripecxid;
			try{
				$stripe->customers->update($customerID, ['source' => $token]);
			}catch(\Exception $e){
				log_message('error', $e->getMessage());
				echo json_encode([
					'error' => true,
					'message' => $e->getMessage(),
					'data'=> null
				]);die;
			}
		}

		(!array_key_exists('stripe_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['stripe_product_id'];
		try {
			$product = \Stripe\Product::retrieve($product_id);
			($product->active) ? $product_id = $product->id : null;
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			$product_id = 'foo';
	    }
		if ($product_id == 'foo') { //create product at stripe if product doesn't exist
			try {
				$product = \Stripe\Product::create(['name'=>$rs->item_name]);
				$product_id = $product->id;
				my_save_payment_item_stuff_setting($items_id, 'stripe_product_id', $product_id);
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				echo json_encode(['error' => true,'message' => my_caption('payment_exception')]);die;
			}
		}

		(!array_key_exists('stripe_price_id', $item_stuff_array)) ? $price_id = 'foo' : $price_id = $item_stuff_array['stripe_price_id'];
		try {
			$price = \Stripe\Price::retrieve($price_id);
			($price->unit_amount == $item_price * 100) ? $price_id = $price->id : $price_id = 'foo';
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			$price_id = 'foo';
		}
		if ($price_id == 'foo') { //create price at stripe if price doesn't exist
			try {
				$stripe_amount = $item_price * 100;
				(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
				$price = \Stripe\Price::create([
					'unit_amount' => $stripe_amount,
					'currency' => strtolower($rs->item_currency),
					'recurring' => [
					'interval' => $rs->recurring_interval,
					'interval_count' => $rs->recurring_interval_count
					],
					'product' => $product_id
				]);
				$price_id = $price->id;
				my_save_payment_item_stuff_setting($items_id, 'stripe_price_id', $price_id);
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				echo json_encode(['error' => true,'message' => my_caption('payment_exception')]);die;
			}
		}

		$sdata = [];
		
		$configOptions = [
			'customer' => $customerID,
			'items' => [[
				'price' => $price_id,
			]],
			//'payment_behavior' => 'default_incomplete',
			'expand' => ['latest_invoice.payment_intent'],
		];

		$coupon = my_post('c_bc');
		
		if ($coupon != '') {
			$c = $this->db->where('couponid', $coupon)->get('promocodes', 1)->row();
			if (!is_null($c)) {
				$configOptions['coupon'] = $coupon;
				$sdata['coupon'] = $coupon;
				$sdata['percent_off'] = $c->percent_off;
				$sdata['code'] = $c->code;
 
				// $sdata['percent_off'] = 10;//let'say  10% discount okay?
				$item_price = $rs->item_price * ((100 - $sdata['percent_off']) / 100);
				
			}
		}
	
		try {
			$subscription = $stripe->subscriptions->create($configOptions);
			if($subscription)
			{
				if($charges = $subscription->latest_invoice->payment_intent->charges)
				{
					$outcome = $charges->data[0]->outcome;
					if($outcome->network_status=='declined_by_network')
					{
						echo json_encode(['error' => true,'message' => $outcome->seller_message]);die;
					}
				}
			}
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			
			$this->session->set_flashdata('flash_danger', $e->getMessage());
			echo json_encode(['error' => true,'message' => $e->getMessage(),'redirect_url'=> site_url('user/pay_credit_card/'.$items_id)]);die;
		}


		//$identifier = $subscription->latest_invoice->payment_intent->client_secret;
		//echo $identifier;die;
		$subid = $subscription->id;
		$publishable_key = $payment_setting_array['stripe_publishable_key'];;
		$this->user_model->payment_log('stripe', $subid, $rs, $item_price, 0);

		//$sdata['clientSecret'] = $identifier;
		$sdata['subscription'] = $subscription;
		$sdata['name'] = $user->first_name . ' ' . $user->last_name;
		$sdata['pricing'] = $rs;
		$sdata['subid'] = $subid;
		$sdata['publishable_key'] = $publishable_key;
		$sdata['payment_method'] = $subscription->latest_invoice->payment_intent ? $subscription->latest_invoice->payment_intent->charges->data[0]->payment_method:'';
		
		echo json_encode([
			'error' => false,
			'message' => 'success',
			'data'=> $sdata
		]);die;
	}

	public function pay_credit_card_old($items_id) {
		$query = $this->db->where('ids', $items_id)->where('enabled', 1)->where('type', 'subscription')->get('payment_item', 1);
		$rs = $query->row();
	
		$this->load->model('user_model');
		$item_price = $rs->item_price;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$item_stuff_array = json_decode($rs->stuff_setting, 1);
		// Setup Stripe
		$apiKey = $payment_setting_array['stripe_secret_key'];
		\Stripe\Stripe::setApiKey($apiKey);
		
		// Add customer to Stripe
		$user = $this->user_model->get_user($_SESSION['user_ids']);

		$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
		
		if(is_null($user->stripecxid)) {
			$customer = $stripe->customers->create([
			'email' => $user->email_address
			]);
			$customerID = $customer->id;
			$this->db->where('ids', $_SESSION['user_ids'])->update('user', ['stripecxid' => $customerID]);
		} else {
			$customerID = $user->stripecxid;
		}
		

		// Add Subscription
			
		(!array_key_exists('stripe_product_id', $item_stuff_array)) ? $product_id = 'foo' : $product_id = $item_stuff_array['stripe_product_id'];
		try {
			$product = \Stripe\Product::retrieve($product_id);
			($product->active) ? $product_id = $product->id : null;
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			$product_id = 'foo';
	    }
		if ($product_id == 'foo') { //create product at stripe if product doesn't exist
			try {
				$product = \Stripe\Product::create(['name'=>$rs->item_name]);
				$product_id = $product->id;
				my_save_payment_item_stuff_setting(my_uri_segment(3), 'stripe_product_id', $product_id);
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				die(my_caption('payment_exception'));
			}
		}
		
		// $product_id;

		(!array_key_exists('stripe_price_id', $item_stuff_array)) ? $price_id = 'foo' : $price_id = $item_stuff_array['stripe_price_id'];
		try {
			$price = \Stripe\Price::retrieve($price_id);
			($price->unit_amount == $item_price * 100) ? $price_id = $price->id : $price_id = 'foo';
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			$price_id = 'foo';
		}
		if ($price_id == 'foo') { //create price at stripe if price doesn't exist
			try {
				$stripe_amount = $item_price * 100;
				(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
				$price = \Stripe\Price::create([
					'unit_amount' => $stripe_amount,
					'currency' => strtolower($rs->item_currency),
					'recurring' => [
					'interval' => $rs->recurring_interval,
					'interval_count' => $rs->recurring_interval_count
					],
					'product' => $product_id
				]);
				$price_id = $price->id;
				my_save_payment_item_stuff_setting(my_uri_segment(3), 'stripe_price_id', $price_id);
			}
			catch (\Exception $e) {
				log_message('error', $e->getMessage());
				die(my_caption('payment_exception'));
			}
		}

		$sdata = [];
		
		$configOptions = [
		        'customer' => $customerID,
		        'items' => [[
		            'price' => $price_id,
		        ]],
		        'payment_behavior' => 'default_incomplete',
		        'expand' => ['latest_invoice.payment_intent'],
		];

		$coupon = my_get('c_bc');
		
		if ($coupon != '') {
			$c = $this->db->where('couponid', $coupon)->get('promocodes', 1)->row();
			if (!is_null($c)) {
				$configOptions['coupon'] = $coupon;
				$sdata['coupon'] = $coupon;
				$sdata['percent_off'] = $c->percent_off;
				$sdata['code'] = $c->code;
 
				// $sdata['percent_off'] = 10;//let'say  10% discount okay?
				$item_price = $rs->item_price * ((100 - $sdata['percent_off']) / 100);
				
			}
		}
	
		try {
			$subscription = $stripe->subscriptions->create($configOptions);
		}
		catch (\Exception $e) {
			log_message('error', $e->getMessage());
			$this->session->set_flashdata('flash_danger', $e->getMessage());
			redirect('user/pay_credit_card/'.$items_id);
		}
		
		
		$identifier = $subscription->latest_invoice->payment_intent->client_secret;
		$subid = $subscription->id;
		$publishable_key = $payment_setting_array['stripe_publishable_key'];;
		$this->user_model->payment_log('stripe', $subid, $rs, $item_price, 0);

		

		$sdata['clientSecret'] = $identifier;
		$sdata['name'] = $user->first_name . ' ' . $user->last_name;
		$sdata['pricing'] = $rs;
		$sdata['subid'] = $subid;
		$sdata['publishable_key'] = $publishable_key;
		
		my_load_view($this->setting->theme, 'User/pay_credit_card', $sdata);
	}
	
	public function thankyou() { 
		
		my_load_view($this->setting->theme, 'User/thankyou');
		
	}
	public function updatecc() {
		$gateway_identifier = my_get('si');
		$pm = my_get('pm');
		if(!empty($pm))
		{
			if ($this->handle_successful_payment($gateway_identifier)) {
				$this->load->model('user_model');
				$user = $this->user_model->get_user($_SESSION['user_ids']);
				$customerID = $user->stripecxid;
				$payment_setting_array = json_decode($this->setting->payment_setting, 1);
				$apiKey = $payment_setting_array['stripe_secret_key'];
				$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
				$cardinfo = $stripe->paymentMethods->retrieve($pm);
				$last4 = $cardinfo->card->last4;
				$expmonth = $cardinfo->card->exp_month;
				$expyear = $cardinfo->card->exp_year;
				$this->db->where('ids', $_SESSION['user_ids'])->update('user', ['ccexpmonth' => $expmonth, 'ccexpyear' => $expyear, 'last4' => $last4, 'pmid' => $pm]);
				return true;
			} else {
					throw \Exception();
			}
		}else{
			$this->handle_successful_payment($gateway_identifier);
			return true;
		}
	}

	public function pay_promocode() {
		$pc = my_get('pc');
		$cp = $this->db->where('code', $pc)->get('promocodes', 1)->row();
		if (is_null($cp)) {
			echo 'NULL';
		} else {
			echo $cp->couponid;
		}
	}

	public function testRj()
	{
		$query_subscription = $this->db->where('gateway_identifier', 'sub_1Kv0PgEWumCho9SwDsnB3JKD')->order_by('id','desc')->get('payment_subscription', 1);
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
		  print_r($update_array);die;
		}
		echo 'test';die;
		//$this->db->where('id', 1)->delete('payment_log');die;
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$apiKey = $payment_setting_array['stripe_secret_key'];
		$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
		try {
			$subscription = $stripe->subscriptions->retrieve('sub_1KtPbcEWumCho9Swky7HMup0');
			print_r($subscription);die;
		} catch (Exception $e) {
				echo $e->getMessage();
			}
	}

	protected function handle_successful_payment($gateway_identifier, $expire=true) { // $obj is only for subscription type
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		$apiKey = $payment_setting_array['stripe_secret_key'];
		$query = $this->db->where('gateway_identifier', $gateway_identifier)->order_by('id', 'desc')->get('payment_log', 1);  //check gateway_event_id to prevent duplicated
		if ($query->num_rows()) {
			$rs = $query->row();
			$query_item = $this->db->where('ids', $rs->item_ids)->order_by('id', 'desc')->get('payment_item', 1);
			if ($query_item->num_rows()) {
				$auto_renew = $query_item->row()->auto_renew;
			}
			else {
				$auto_renew = 0; //unexpected result
			}

            $check_result = FALSE;

			if ($expire == true) {
				$this->db->update('payment_subscription', ['status' => 'expired'], ['user_ids' => $rs->user_ids]);
			}
			
			$stripe = new \Stripe\StripeClient(['api_key' => $apiKey, 'stripe_version' => '2020-08-27',]);
			try {
			$subscription = $stripe->subscriptions->retrieve($gateway_identifier);
			if($subscription)
			{
				$insert_data = array(
					'ids' => my_random(),
					'item_ids' => $rs->item_ids,
					'user_ids' => $rs->user_ids,
					'payment_gateway' => $rs->gateway,
					'gateway_identifier' => $gateway_identifier,
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

				if($subscription->status=='active'){
					$this->db->where('id', $rs->id)->update('payment_log', array('redirect_status'=>'success', 'callback_status'=>'success', 'callback_time'=>my_server_time()));
					$this->db->where('ids', $rs->user_ids)->update('user', array('status' => 1, 'email_verified' => 1));
				}
			}else{
				$this->db->where('id', $rs->id)->delete('payment_log');
				return false;
			}
		} catch (Exception $e) {
				return false;
			}
			$check_result = TRUE;
		}

		if($subscription->status=='active')
		{
			$rs_email = $this->db->where('purpose', 'pay_success')->get('email_template', 1)->row();
			$email_address = my_user_setting($rs->user_ids, 'email_address');
			$email = array(
			  'email_to' => $email_address,
			  'email_subject' => $rs_email->subject,
			  'email_body' => str_replace('{{purchase_item}}', $rs->item_name, str_replace('{{purchase_price}}', $rs->currency . $rs->amount, $rs_email->body))
			);
			my_send_email($email);

			//remove user from mailchimp
			$this->remove_user_from_mailchimp($email_address);
		}
		
		return true;
	}
	
	public function remove_user_from_mailchimp($email) { 
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

	public function pay_success() {
		$this->session->set_flashdata('flash_success', my_caption('payment_payment_success'));
		$subdata = my_check_subscription($_SESSION['user_ids']);
		if($subdata) {
			my_payment_gateway_subscription_action('cancel_now', $subdata, '/users/pay_now');
		}
		if (my_get('g')) {
			$this->db->where('gateway_identifier', my_uri_segment(3))->update('payment_log', array('redirect_status'=>'success', 'callback_status'=>'success'));
			return true;
		} else {
			$this->db->where('gateway_identifier', my_uri_segment(3))->or_where('gateway_identifier', my_get('token'))->or_where('gateway_identifier', my_get('subscription_id'))->update('payment_log', array('redirect_status'=>'success'));
			redirect('/thankyou');
		}
	}
	
	
	public function pay_cancel($redirect = true) {
		(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
		$this->session->set_flashdata('flash_warning', my_caption('payment_payment_cancel'));
		$this->db->where('gateway_identifier', my_uri_segment(3))->or_where('gateway_identifier', my_get('token'))->or_where('gateway_identifier', my_get('subscription_id'))->update('payment_log', array('redirect_status'=>'cancel'));
		// Check if it was from upgrade - then send to upgrade page
		if ($redirect) {
			redirect(base_url('home/pricing'));
		} else {
			return;
		}
	}
	
	
	
	public function remove_self() {
		my_check_demo_mode();  //check if it's in demo mode
		$gdpr_array = json_decode($this->setting->gdpr, TRUE);
		if (!$_SESSION['is_admin'] && $gdpr_array['allow_remove']) {
			my_remove_user($_SESSION['user_ids']);
			redirect(base_url('generic/sign_out'));
		}
		else {
			redirect('user/my_profile');
		}
	}
	
	
	
	public function gdpr_export() {
		$rs = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
		$export_arr = array(
		  'identifier' => $rs->ids,
		  'emailAddress' => $rs->email_address,
		  'firstName' => $rs->first_name,
		  'lastName' => $rs->last_name,
		  'company' => $rs->company,
		  'addressLine1' => $rs->address_line_1,
		  'addressLine2' => $rs->address_line_2,
		  'city' => $rs->city,
		  'state' => $rs->state,
		  'zipCode' => $rs->zip_code,
		  'phoneNumber' => $rs->phone,
		  'createdTime' => $rs->created_time,
		  'lastUpdateTime' => $rs->update_time,
		  'agreementTime' => $rs->created_time,
		  'lastOnlineTime' => $rs->online_time
		);
		$this->load->helper('download');
		force_download('myData.txt', json_encode($export_arr, JSON_PRETTY_PRINT));
	}
	
	
	
	//This method is used to generate invoice
	public function invoice() {
		$query = $this->db->where('user_ids', $_SESSION['user_ids'])->where('ids', my_uri_segment(3))->get('payment_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			if (!$rs->generate_invoice) {
				$this->session->set_flashdata('flash_warning', my_caption('payment_invoice_not_applicable'));
				redirect(base_url('user/pay_list'));
			}
			else {
				$rs_item = $this->db->where('ids', $rs->item_ids)->get('payment_item', 1)->row();
				$rs_user = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1)->row();
				$issued_to = my_user_setting($_SESSION['user_ids'], 'company');
				if (empty($issued_to)) {
					$issued_to = $_SESSION['full_name'];
					$agency = FALSE;
				}
				else {
					$agency = TRUE;
				}
				$data = array(
				  'agency' => $agency,
				  'invoice_no' => strtoupper(substr($_SESSION['user_ids'], -10) . strtotime($rs->created_time)),
				  'generated_date' => my_conversion_from_server_to_local_time($rs->callback_time, $this->user_timezone, $this->user_date_format),
				  'issued_to' => $issued_to,
				  'address_line_1' => $rs_user->address_line_1,
				  'address_line_1' => $rs_user->address_line_2,
				  'city' => $rs_user->city,
				  'state' => $rs_user->state,
				  'country' => $rs_user->country,
				  'zip_code' => $rs_user->zip_code,
				  'payment_method' => ucfirst($rs->gateway),
				  'transaction_no' => substr($rs->gateway_identifier, -10),
				  'item' => $rs->item_name,
				  'currency' => $rs->currency,
				  'price' => $rs->price,
				  'quantity' => $rs->quantity,
				  'discount' => $rs->coupon_discount,
				  'tax_rate' => $rs->tax . '%',
				  'tax' => ($rs->price - $rs->coupon_discount) * ($rs->tax/100),
				  'amount' => $rs->amount  //currently only support one item so it's same as amount
				);
				if ($agency) {
					$data['company_no'] = $rs_user->company_number;
					$data['tax_no'] = $rs_user->tax_number;
				}
				$invoice_setting = json_decode($this->setting->invoice_setting, TRUE);
				if ($invoice_setting['invoice_format'] == 'html') {
					my_load_view($this->setting->theme, 'User/invoice', $data);
				}
				else {
					$html = my_load_view($this->setting->theme, 'User/invoice', $data, TRUE);
					$dompdf = new Dompdf\Dompdf();
					$invoice_css = '<style>' . file_get_contents(FCPATH . 'assets/themes/' . $this->setting->theme . '/css/invoice_default.css') . '</style>';
					$dompdf->loadHtml($invoice_css . $html);
					$dompdf->setPaper('A4', 'portrait');
					$dompdf->render();
					$dompdf->stream(my_uri_segment(3) . '.pdf', array('Attachment' => 0));
				}
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	// reset the user's api key
	public function reset_api_key() {
		my_check_demo_mode('alert_json');  //check if it's in demo mode
		$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('api_key'=>my_random()));
		echo '{"result":true, "title":"", "text":"'. my_caption('global_deleted_notice_message') . '", "redirect":"'. base_url('user/my_profile') . '"}';
	}
	
	
	
	// callback for form_validation
	public function check_old_password($old_password) {
		$query = $this->db->where('ids', $_SESSION['user_ids'])->get('user', 1);
		if ($query->num_rows()) {
			if (password_verify($old_password, $query->row()->password)) {
				return TRUE;
			}
			else {
				my_throttle_log($_SESSION['user_ids']);
				$this->form_validation->set_message('check_old_password', my_caption('cp_old_password_error'));
				return FALSE;
			}
		}
		else {
			my_throttle_log($_SESSION['user_ids']);
			$this->form_validation->set_message('check_old_password', my_caption('cp_old_password_error'));
			return FALSE;
		}
	}
	
	
	
	// callback for form_validation
	public function email_duplicated_check($email_address, $ids) {
		if (my_duplicated_check('user', array('email_address'=>$email_address), $ids)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('email_duplicated_check', my_caption('mp_email_taken'));
			return FALSE;
		}
	}
	
	
	// callback for form_validation
	public function username_duplicated_check($username, $ids) {
		if (my_duplicated_check('user', array('username'=>$username), $ids)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('username_duplicated_check', my_caption('mp_username_taken'));
			return FALSE;
		}
	}
	
	
    public function avatar_upload() {
		$this->load->library('m_upload');
		$this->m_upload->set_upload_path('/' . '/' . $this->config->item('my_upload_directory') . 'avatar/');
		$this->m_upload->set_allowed_types('png|gif|jpg|jpeg');
		$this->m_upload->set_file_name($_SESSION['user_ids']);
		//$this->my_upload->set_remove_file();
		$res = $this->m_upload->upload_done();
		if ($res['status']) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('avatar_upload', $res['error']);
			return FALSE;
		}
	}
	
	
	protected function check_pay_condition($rs) {
		if ($rs->purchase_limit != '0') {  //limit the purchase times
			if (my_check_purchase_by_item($rs->ids) || my_check_subscription_by_item($rs->ids)) { //already bought/subscribed
				$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_times_limit'));
				redirect('user/pay_now');
			}
		}
		if ($rs->access_condition != '0') {  //limit the condition of purchase/subscription, if someone wants to buy this item, he should buy the basic one first.
			$access_condition_array = explode(',', $rs->access_condition);
			if ($access_condition_array[0] != '0') {
				$check_result = FALSE;
				foreach ($access_condition_array as $condition) {
					if (my_check_purchase_by_item($condition) || my_check_subscription_by_item($condition)) {  //purchased/subscribed detected
						$check_result = TRUE;
						break;
					}
				}
				if (!$check_result) {
					$this->session->set_flashdata('flash_danger', my_caption('payment_purchase_violate_condition'));
					redirect('user/pay_now');
				}
			}
		}
		return TRUE;
	}


	// public function pay_once() { exit();
	// 	my_check_demo_mode();  //check if it's in demo mode
	// 	(!$this->payment_swtich) ? die(my_caption('payment_module_disabled')) : null;
	// 	$quantity = my_get('quantity');
	// 	(!is_int($quantity)) ? $quantity = 1 : $quantity = abs($quantity);
	// 	$query = $this->db->where('ids', my_uri_segment(4))->where('enabled', 1)->group_start()->where('type', 'top-up')->or_where('type', 'purchase')->group_end()->get('payment_item', 1);
	// 	if (!$query->num_rows()) {
	// 		echo my_caption('global_no_entries_found');
	// 	}
	// 	else {
	// 		$rs = $query->row();
	// 		$this->check_pay_condition($rs);
	// 		$this->load->model('user_model');
	// 		$item_price = $rs->item_price;
	// 		if (my_coupon_module() && my_uri_segment(5) != '') {  //coupon enabled, calculate the new price if necessary
	// 			$coupon_array = my_coupon_check($rs->ids, my_uri_segment(5));
	// 			($coupon_array['result']) ? $item_price = $coupon_array['amount'] : null;
	// 		}
	// 		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
	// 		if (!empty($payment_setting_array['tax_rate'])) {
	// 			$tax = $payment_setting_array['tax_rate'];
	// 			if (strtolower($rs->item_currency) == 'jpy') {
	// 				($tax) ? $item_price = round($item_price * (1 + $tax/100), 2) : null;  //tax
	// 			}
	// 			else {
	// 				($tax) ? $item_price = number_format(round($item_price * (1 + $tax/100), 2), 2) : null;  //tax
	// 			}
	// 		}
	// 		else {
	// 			$tax = 0;
	// 		}
	// 		if (my_uri_segment(3) == 'stripe' && $payment_setting_array['stripe_one_time_enabled']) {
	// 			\Stripe\Stripe::setApiKey($payment_setting_array['stripe_secret_key']);
	// 			try {
	// 				$stripe_amount = $item_price * 100;
	// 				(strtolower($rs->item_currency) == 'jpy') ? $stripe_amount = intval($item_price) : null;   // for Japanese Yen only
	// 				$checkout_session = \Stripe\Checkout\Session::create([
	// 				  'success_url' => base_url('/user/pay_success/{CHECKOUT_SESSION_ID}'),
	// 				  'cancel_url' => base_url('/user/pay_cancel/{CHECKOUT_SESSION_ID}'),
	// 				  'payment_method_types' => ['card'],
	// 				  'mode' => 'payment',
	// 				  'line_items' => [[
	// 				    'name' => $rs->item_name,
	// 					'description' => $rs->item_description,
	// 					'amount' => $stripe_amount,
	// 					'currency' => strtolower($rs->item_currency),
	// 					'quantity' => 1,
	// 				  ]]
	// 				]);
	// 				$data['publishable_key'] = $payment_setting_array['stripe_publishable_key'];
	// 				$data['checkout_session'] = $checkout_session['id'];
	// 				$this->user_model->payment_log('stripe', $checkout_session['id'], $rs, $item_price, $tax);
	// 				my_load_view($this->setting->theme, 'User/pay_stripe', $data); //redirect to payment page
	// 			}
	// 			catch (\Exception $e) {
	// 				log_message('error', $e->getMessage());
	// 				die(my_caption('payment_exception'));
	// 			}
	// 		}
	// 		elseif (my_uri_segment(3) == 'paypal' && $payment_setting_array['paypal_one_time_enabled']) {
	// 			  $paypal_clientid = $payment_setting_array['paypal_client_id'];
	// 			  $paypal_secret = $payment_setting_array['paypal_secret'];
	// 			  ($payment_setting_array['type'] == 'sandbox') ? $paypal_environment = new \PayPalCheckoutSdk\Core\SandboxEnvironment($paypal_clientid, $paypal_secret) : $paypal_environment = new \PayPalCheckoutSdk\Core\ProductionEnvironment($paypal_clientid, $paypal_secret);
	// 			  $paypal_client = new \PayPalCheckoutSdk\Core\PayPalHttpClient($paypal_environment);
	// 			  $paypal_request = new \PayPalCheckoutSdk\Orders\OrdersCreateRequest();
	// 			  $paypal_request->prefer('return=representation');
	// 			  $paypal_request->body = [
	// 			    'intent' => 'AUTHORIZE',
 //                    'purchase_units' => [[
	// 				  'reference_id' => my_random(),
 //                      'amount' => [
	// 				    'value' => $item_price,
 //                        'currency_code' => strtolower($rs->item_currency)
 //                      ]
 //                    ]],
 //                    'application_context' => [
	// 				  'cancel_url' => base_url('/user/pay_cancel/'),
 //                      'return_url' => base_url('/user/pay_success/')
 //                    ]
	// 			  ];
	// 			  try {
	// 				  $paypal_response = $paypal_client->execute($paypal_request);
	// 				  $paypal_order_result = $paypal_response->result;
	// 				  $this->user_model->payment_log('paypal', $paypal_order_result->id, $rs, $item_price, $tax);
	// 				  header('Location: ' . $paypal_order_result->links[1]->href);
	// 			  }
	// 			  catch(\Exception $e) {
	// 				  log_message('error', $e->getMessage());
	// 				  die(my_caption('payment_exception'));
	// 			  }
	// 		}
	// 		elseif (my_uri_segment(3) == 'gateway') { //other payment gateways, it only works with the Payment Gateway Add-on
	// 			$this->load->library('m_payment');
	// 			$this->m_payment->pay('one-time', $rs, $item_price, $tax);  //item_price depends on coupon, tax denpends on coupon and tax setting, so item_price is different from the item's original price
	// 		}
	// 		else {
	// 			echo my_caption('payment_payment_gateway_unavailable');
	// 		}
	// 	}
	// }
}
?>