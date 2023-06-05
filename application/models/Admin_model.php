<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {



	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
	}
	
	
	
	public function permissionBuilder($builde_type, $ids='') {
		if ($builde_type == 'permission_created' || $builde_type == 'permission_removed') {  //call it after add a permission
			$rs_role = $this->db->order_by('id', 'asc')->get('role')->result();
			foreach ($rs_role as $row) {
				$permission_array = json_decode($row->permission, TRUE);
				if ($builde_type == 'permission_created') {
					$permission_array[$ids] = FALSE;  //append the permission
				}
				else {
					unset($permission_array[$ids]); //remove the permission
				}
				$this->db->where('id', $row->id)->update('role', array('permission'=>json_encode($permission_array)));
			}
		}
		elseif ($builde_type == 'update_all') {
			$rs_role = $this->db->order_by('id', 'asc')->get('role')->result();
			$rs_permission = $this->db->order_by('id', 'asc')->get('permission')->result();
			foreach ($rs_role as $row_role) {
				$permission_array = [];
				foreach ($rs_permission as $row_permission) {
					(my_post($row_role->ids . '_' . $row_permission->ids) == 'true') ? $permission = TRUE : $permission = FALSE;
					$permission_array[$row_permission->ids] = $permission;
				}
				$this->db->where('id', $row_role->id)->update('role', array('permission'=>json_encode($permission_array)));
			}
		}
		else {  //call it after add a role
			$rs_permission = $this->db->order_by('id', 'asc')->get('permission')->result();
			$permission_array = [];
			foreach ($rs_permission as $row) {
				$permission_array[$row->ids] = FALSE;
			}
			$this->db->where('ids', $ids)->update('role', array('permission'=>json_encode($permission_array)));
		}
		return TRUE;
	}
	
	
	
	public function update_general_settings() {
		(my_post('maintenance_mode') == '1') ? $maintenance_mode = 1 : $maintenance_mode = 0;
		(my_post('enabled_signup') == '1') ? $enabled_signup = 1 : $enabled_signup = 0;
		(my_post('terms_conditions') == '1') ? $terms_conditions = 1 : $terms_conditions = 0;
		(my_post('email_verification_required') == '1') ? $email_verification_required = 1 : $email_verification_required = 0;
		(my_post('allow_signin_before_verification') == '1') ? $allow_signin_before_verification = 1 : $allow_signin_before_verification = 0;
		(my_post('allow_remember') == '1') ? $allow_remember = 1 : $allow_remember = 0;
		(my_post('kyc_payment') == '1') ? $kyc_payment = 1 : $kyc_payment = 0;
		(my_post('allow_reset') == '1') ? $allow_reset = 1 : $allow_reset = 0;
		(my_post('enabled_api') == '1') ? $enabled_api = 1 : $enabled_api = 0;
		(my_post('xss_clean') == '1') ? $xss_clean = 1 : $xss_clean = 0;
		(my_post('html_purify') == '1') ? $html_purify = 1 : $html_purify = 0;
		$update_data = array(
		  'sys_name' => my_post('system_name'),
		  'theme' => my_post('system_theme'),
		  'google_analytics_id' => my_post('google_analytics_id'),
		  'default_role' => my_post('default_role'),
		  'default_package' => my_post('default_package'),
		  'psr' => my_post('psr'),
		  'two_factor_authentication' => my_post('two_FA'),
		  'html_purify' => $html_purify,
		  'xss_clean' => $xss_clean,
		  'throttling_policy' => my_post('throttling_policy'),
		  'throttling_unlock_time' => my_post('throttling_unlock_time'),
		  'maintenance_message' => my_post('maintenance_information'),
		  'maintenance_mode' => $maintenance_mode,
		  'signup_enabled' => $enabled_signup,
		  'tc_show' => $terms_conditions,
		  'email_verification_required' => $email_verification_required,
		  'signin_before_verified' => $allow_signin_before_verification,
		  'remember' => $allow_remember,
		  'kyc' => $kyc_payment,
		  'forget_enabled' => $allow_reset,
		  'api_enabled' => $enabled_api,
		  'dashboard_custom_css' => my_post('dashboard_custom_css'),
		  'dashboard_custom_javascript' => my_post('dashboard_custom_javascript')
		);
		$this->db->update('setting', $update_data);
		return TRUE;

	}
	
	
	
	public function update_auth_integration() {
		// Google reCaptcha
		(my_post('google_recaptcha_enabled') == '1') ? $google_recaptcha_enabled = 1 : $google_recaptcha_enabled = 0;
		$google_recaptcha_detail_array = array(
		  'version' => 'v2_1',
		  'site_key' => my_post('google_recaptcha_site_key'),
		  'secret_key' => my_post('google_recaptcha_secret_key')
		);
		$update_array = array(
		  'recaptcha_enabled' => $google_recaptcha_enabled,
		  'recaptcha_detail' => json_encode($google_recaptcha_detail_array)
		);
		
		// Google Login
		(my_post('google_login_enabled') == '1') ? $google_login_enabled = 1 : $google_login_enabled = 0;
		$oauth_array = array(
		  'google' => array(
		    'enabled' => $google_login_enabled,
			'client_id' => my_post('google_client_id'),
			'client_secret' => my_post('google_client_secret')
		));
		
		
		//Facebook Login
		(my_post('facebook_login_enabled') == '1') ? $facebook_login_enabled = 1 : $facebook_login_enabled = 0;
		$oauth_array += array(
		  'facebook' => array(
		    'enabled' => $facebook_login_enabled,
			'app_id' => my_post('facebook_app_id'),
			'app_secret' => my_post('facebook_app_secret')
		));
		
		//Twitter Login
		(my_post('twitter_login_enabled') == '1') ? $twitter_login_enabled = 1 : $twitter_login_enabled = 0;
		$oauth_array += array(
		  'twitter' => array(
		    'enabled' => $twitter_login_enabled,
			'consumer_key' => my_post('twitter_consumer_key'),
			'consumer_secret' => my_post('twitter_consumer_secret')
		));
		
		$update_array += array('oauth_setting'=>json_encode($oauth_array));
		$this->db->update('setting', $update_array);
		
	}
	
	
	
	public function send_notification($global_setting) {
		$insert_data = array(
		  'ids' => my_random(),
		  'from_user_ids' => $_SESSION['user_ids'],
		  'to_user_ids' => 'all',
		  'subject' => my_post('notification_subject'),
		  'body' => my_post('notification_body'),
		  'is_read' => 2,
		  'send_email' => 0,
		  'created_time' => my_server_time(),
		  'read_time' => ''
		);
		$this->db->insert('notification', $insert_data);
		$this->db->update('user', array('new_notification'=>1));
		return TRUE;
	}
	
	
	
	public function invite_user($emailAddress) {
		$query = $this->db->where('email_address', $emailAddress)->get('user', 1);
		if ($query->num_rows()) {
			return array('result'=>FALSE, 'message'=>my_caption('signup_email_taken'));
		}
		else {
			$verification_token = my_random();
			$url = base_url() . 'auth/invite_user/' . $verification_token;
			$rs_email = $this->db->where('purpose', 'invite_email')->get('email_template', 1)->row();
			$body = str_replace('{{verification_url}}', $url, $rs_email->body);
			$email = array(
			  'email_to' => $emailAddress,
			  'email_subject' => $rs_email->subject,
			  'email_body' => $body
		    );
			$res = my_send_email($email);
			if ($res['result']) {
				$insert_data = array(
				  'type' => 'invite_activation',
				  'email' => $emailAddress,
				  'token' => $verification_token,
				  'reference' => '',
				  'created_time' => my_server_time(),
				  'done' => 0
				);
				$this->db->insert('token', $insert_data);
				return array('result'=>TRUE, 'message'=>my_caption('user_invite_send_success'));
			}
			else {
				return array('result'=>FALSE, 'message'=>$res['message']);
			}
		}
	}
	
	
	
	public function miscellaneous() {
		(my_post('cookie_popup') == '1') ? $gdpr_array['enabled'] = 1 : $gdpr_array['enabled'] = 0;
		(my_post('allow_remove') == '1') ? $gdpr_array['allow_remove'] = 1 : $gdpr_array['allow_remove'] = 0;
		(my_post('cookie_message') != '') ? $gdpr_array['cookie_message'] = my_post('cookie_message') : $gdpr_array['cookie_message'] = 'This website uses cookies to ensure you get the best experience on our website.';
		(my_post('cookie_policy_link_text') != '') ? $gdpr_array['cookie_policy_link_text'] = my_post('cookie_policy_link_text') : $gdpr_array['cookie_policy_link_text'] = 'Learn more';
		(my_post('cookie_policy_link') != '') ? $gdpr_array['cookie_policy_link'] = my_post('cookie_policy_link') : $gdpr_array['cookie_policy_link'] = base_url('generic/terms_conditions');
		$file_setting_array = array(
		  'file_type' => my_post('file_type'),
		  'file_size' => my_post('file_size')
		);
		$this->db->update('setting', array('gdpr'=>json_encode($gdpr_array), 'file_setting'=>json_encode($file_setting_array)));
		return array('result'=>TRUE, 'message'=>my_caption('global_changes_have_been_saved'));
	}
	
	
	
	public function payment_setting_update() {
		(my_post('stripe_one_time_enabled') == '1') ? $stripe_one_time_enabled = '1' : $stripe_one_time_enabled = 0;
		(my_post('stripe_recurring_enabled') == '1') ? $stripe_recurring_enabled = '1' : $stripe_recurring_enabled = 0;
		//(my_post('paypal_one_time_enabled') == '1') ? $paypal_one_time_enabled = '1' : $paypal_one_time_enabled = 0;
		//(my_post('paypal_recurring_enabled') == '1') ? $paypal_recurring_enabled = '1' : $paypal_recurring_enabled = 0;
		$payment_setting = json_decode($this->setting->payment_setting, 1);
		$payment_setting['type'] = my_post('payment_type');
		$payment_setting['feature'] = my_post('payment_feature');
		$payment_setting['tax_rate'] = my_post('payment_tax_rate');
		$payment_setting['stripe_one_time_enabled'] = $stripe_one_time_enabled;
		$payment_setting['stripe_recurring_enabled'] = $stripe_recurring_enabled;
		$payment_setting['stripe_publishable_key'] = my_post('stripe_publishable_key');
		$payment_setting['stripe_secret_key'] = my_post('stripe_secret_key');
		$payment_setting['stripe_signing_secret'] = my_post('stripe_signing_secret');
		//$payment_setting['paypal_one_time_enabled'] = $paypal_one_time_enabled;
		//$payment_setting['paypal_recurring_enabled'] = $paypal_recurring_enabled;
		//$payment_setting['paypal_client_id'] = my_post('paypal_client_id');
		//$payment_setting['paypal_secret'] = my_post('paypal_secret');
		//$payment_setting['paypal_webhook_id'] = my_post('paypal_webhook_id');
		$invoice_setting = array(
		  'invoice_format' => my_post('payment_invoice_format'),
		  'company_name' => my_post('payment_invoice_setting_company'),
		  'company_number' => my_post('payment_company_number'),
		  'tax_number' => my_post('payment_tax_number'),
		  'address_line_1' => my_post('payment_invoice_setting_address_line_1'),
		  'address_line_2' => my_post('payment_invoice_setting_address_line_2'),
		  'phone' => my_post('payment_invoice_setting_tel')
		);
		$this->db->update('setting', array('payment_setting'=>json_encode($payment_setting), 'invoice_setting'=>json_encode($invoice_setting)));
		return TRUE;
	}
	
	
	
	public function payment_item_add() {
		if (my_post('item_type') == 'subscription') {
			$recurring_interval_count = my_post('recurring_interval_count');
			(my_post('renew_action') == '3') ? $auto_renew = 1 : $auto_renew = 0;
		}
		elseif (my_post('item_type') == 'top-up') {
			$recurring_interval_count = 0;
			$auto_renew = 0;
		}
		elseif (my_post('item_type') == 'purchase') {
			$recurring_interval_count = 0;
			(my_post('renew_action') == '2') ? $auto_renew = 1 : $auto_renew = 0;
		}
		$access_condition_array = $this->input->post('access_condition[]');
		$conditions = '';
		foreach ($access_condition_array as $condition) {
			$conditions .= $condition . ',';
		}

		$ids = my_random();
		$item = array(
		  'ids' => $ids,
		  'enabled' => my_post('item_enabled'),
		  'type' => my_post('item_type'),
		  'item_name' => my_post('item_name'),
		  'item_description' => my_post('item_description'),
		  'item_currency' => my_post('item_currency'),
		  'item_price' => my_post('item_price'),
		  'recurring_interval' => my_post('recurring_interval'),
		  'recurring_interval_count' => $recurring_interval_count,
		  'purchase_limit' => my_post('purchase_times'),
		  'access_condition' => rtrim($conditions, ','),
		  'trash' => 0,
		  'show_order' => 0,
		  'auto_renew' => $auto_renew,
		  'access_code' => my_post('access_code')
		);

		//$stripePlan = $this->createdStripePlan($item);
		if(false)
		{
			$limit_stuff_array = array(
			  'characters_limit' => my_post('characters_limit'),
			  'characters_used' => 0,
			  'stripe_product_id' => $stripePlan['stripe_product_id'],
			  'stripe_price_id' => $stripePlan['stripe_price_id']
			);
			$item['stuff_setting'] = json_encode($limit_stuff_array);
		}else{
			$limit_stuff_array = array(
			  'characters_limit' => my_post('characters_limit'),
			  'characters_used' => 0
			);
			$item['stuff_setting'] = json_encode($limit_stuff_array);
		}

		$this->db->insert('payment_item', $item);
		$this->payment_apply($ids, my_post('item_type'));
		return TRUE;
	}
	
	public function createdStripePlan($item){
		try {
				$payment_setting_array = json_decode($this->setting->payment_setting, 1);
				$apiKey = $payment_setting_array['stripe_secret_key'];
				if($apiKey)
				{
			        $stripe = new \Stripe\StripeClient($apiKey);
			        //create product
					$res = $stripe->products->create([
					  'name' => $item['item_name'],
					]);

					//create price
					if(isset($res))
					{
						try {
							$unit_amount = $item['item_price']*100;
							$price = $stripe->prices->create([
									  'unit_amount' => $unit_amount,
									  'currency' => $item['item_currency'],
									  'recurring' => ['interval' => $item['recurring_interval']],
									  'product' => $res['id'],
									]);
							if($price)
							{
								$data['stripe_product_id'] = $res['id'];
								$data['stripe_price_id']   = $price['id'];
								return $data;
							}else{
								return false;
							}
							
						} catch (Exception $e) {				
							return false;
						}
						return $res['id'];
					}else{
						return false;
					}
				}				
			} catch (Exception $e) {				
				return false;
			}
	}
	
	public function payment_item_modify($item_type) {
		if ($item_type == 'subscription') {
			$recurring_interval_count = my_post('recurring_interval_count');
			(my_post('renew_action') == '3') ? $auto_renew = 1 : $auto_renew = 0;
		}
		elseif ($item_type == 'top-up') {
			$recurring_interval_count = 0;
			$auto_renew = 0;
		}
		elseif ($item_type == 'purchase') {
			$recurring_interval_count = 0;
			(my_post('renew_action') == '2') ? $auto_renew = 1 : $auto_renew = 0;
		}
		$access_condition_array = $this->input->post('access_condition[]');
		$conditions = '';
		foreach ($access_condition_array as $condition) {
			$conditions .= $condition . ',';
		}

		$limit_stuff_array = array(
		  'characters_limit' => my_post('characters_limit'),
		  'characters_used' => 0
		);

		//set strip product details on update
		$paymentItem = $this->db->where('ids', my_post('ids'))->get('payment_item');
		if ($paymentItem->num_rows() > 0) {
			$paymentrow = $paymentItem->row();
			if($paymentrow->stuff_setting!='')
			{
				$stuff_setting_ary = json_decode($paymentrow->stuff_setting);
				if(isset($stuff_setting_ary->stripe_price_id) && isset($stuff_setting_ary->stripe_product_id))
				{
					$limit_stuff_array['stripe_product_id'] = $stuff_setting_ary->stripe_product_id;
					$limit_stuff_array['stripe_price_id'] = $stuff_setting_ary->stripe_price_id;
				} 
			}
		}
		
		$item = array(
		  'enabled' => my_post('item_enabled'),
		  'item_name' => my_post('item_name'),
		  'item_description' => my_post('item_description'),
		  'item_currency' => my_post('item_currency'),
		  'item_price' => my_post('item_price'),
		  'recurring_interval' => my_post('recurring_interval'),
		  'recurring_interval_count' => $recurring_interval_count,
		  'stuff_setting' => json_encode($limit_stuff_array),
		  'purchase_limit' => my_post('purchase_times'),
		  'access_condition' => rtrim($conditions, ','),
		  'auto_renew' => $auto_renew,
		  'access_code' => my_post('access_code')
		);
		$this->db->where('ids', my_post('ids'))->update('payment_item', $item);
		$this->payment_apply(my_post('ids'), $item_type);
		return TRUE;
	}
	
	
	
	public function support_setting_update() {
		$ticket_item = json_decode($this->setting->ticket_setting, TRUE);
		(my_post('support_ticket_enabled') == '1') ? $ticket_item['enabled'] = 1 : $ticket_item['enabled'] = 0;
		(my_post('support_ticket_guest_enabled') == '1') ? $ticket_item['guest_ticket'] = 1 : $ticket_item['guest_ticket'] = 0;
		(my_post('support_ticket_rating_enabled') == '1') ? $ticket_item['rating'] = 1 : $ticket_item['rating'] = 0;
		(my_post('support_ticket_notify_user') == '1') ? $ticket_item['notify_user'] = 1 : $ticket_item['notify_user'] = 0;
		$ticket_item['notify_agent_list'] = my_post('support_ticket_notification_email_address');
		$ticket_item['allow_upload'] = 0;
		$ticket_item['close_rule'] = my_post('support_ticket_setting_close_policy');
		$this->db->update('setting', array('ticket_setting'=>json_encode($ticket_item)));
		return TRUE;
	}
	
	
	
	public function payment_log_insert() {
		$current_datetime = my_server_time();
		(my_post('adjust_visible_for_user') == '1') ? $adjust_visible_for_user = 1 : $adjust_visible_for_user = 0;
		(my_post('adjust_create_invoice') == '1') ? $adjust_create_invoice = 1 : $adjust_create_invoice = 0;
		$amount = my_post('adjust_amount');
		$payment_setting_array = json_decode($this->setting->payment_setting, 1);
		if (!empty($payment_setting_array['tax_rate'])) {
			$tax = $payment_setting_array['tax_rate'];
			($tax) ? $amount = round($amount * (1 + $tax/100), 2) : null;  //tax
		}
		else {
			$tax = 0;
		}
		$insert_array = array(
		  'ids' => my_random(),
		  'user_ids' => my_post('user_ids'),
		  'type' => 'Adjust-Balance',
		  'gateway' => my_post('adjust_payment_gateway'),
		  'currency' => my_post('adjust_currency'),
		  'price' => my_post('adjust_amount'),
		  'quantity' => 1,
		  'amount' => $amount,
		  'gateway_identifier' => 0,
		  'gateway_event_id' => 0,
		  'item_ids' => 0,
		  'item_name' => my_post('adjust_balance_type') . '-Balance',
		  'redirect_status' => 'success',
		  'callback_status' => 'success',
		  'created_time' => $current_datetime,
		  'callback_time' => $current_datetime,
		  'visible_for_user' => $adjust_visible_for_user,
		  'generate_invoice' => $adjust_create_invoice,
		  'description' => my_post('adjust_description'),
		  'stuff' => '',
		  'coupon' => '',
		  'coupon_discount' => 0,
		  'tax' => $tax
		);
		$this->db->insert('payment_log', $insert_array);
		return TRUE;
	}
	
	
	
	public function front_setting_update($logo_filename) {
		(my_post('enabled_front') == '1') ? $enabled = 1 : $enabled = 0;
		(my_post('enabled_pricing') == '1') ? $enabled_pricing = 1 : $enabled_pricing = 0;
		(my_post('enabled_faq') == '1') ? $enabled_faq = 1 : $enabled_faq = 0;
		(my_post('enabled_documentation') == '1') ? $enabled_documentation = 1 : $enabled_documentation = 0;
		(my_post('enabled_blog') == '1') ? $enabled_blog = 1 : $enabled_blog = 0;
		(my_post('enabled_subscriber') == '1') ? $enabled_subscriber = 1 : $enabled_subscriber = 0;
		$front_setting = array(
		  'enabled' => $enabled,
		  'logo' => $logo_filename,
		  'company_name' => my_post('company_name'),
		  'email_address' => my_post('email_address'),
		  'html_title' => my_post('html_title'),
		  'html_author' => my_post('html_author'),
		  'html_description' => my_post('html_description'),
		  'html_keyword' => my_post('html_keyword'),
		  'about_us' => my_post('about_us'),
		  'pricing_enabled' => $enabled_pricing,
		  'faq_enabled' => $enabled_faq,
		  'documentation_enabled' => $enabled_documentation,
		  'blog_enabled' => $enabled_blog,
		  'subscriber_enabled' => $enabled_subscriber,
		  'social_facebook' => my_post('social_facebook'),
		  'social_twitter' => my_post('social_twitter'),
		  'social_linkedin' => my_post('social_linkedin'),
		  'social_github' => my_post('social_github'),
		  'custom_css' => my_post('custom_css'),
		  'custom_javascript' => my_post('custom_javascript')
		);
		$this->db->update('setting', array('front_setting'=>json_encode($front_setting)));
		return TRUE;
	}
	
	
	
	public function blog_save() {
		(my_post('blog_enabled') == '1') ? $blog_enabled = 1 : $blog_enabled = 0;
		$ids = my_random();
		$file_name = '';
		if ($_FILES['userfile']['name'] != '') {
			$file_name = $this->blog_upload_cover_photo($ids);
		}
		$insert_data = array(
		  'ids' => $ids,
		  'author' => $_SESSION['full_name'],
		  'user_ids' => $_SESSION['user_ids'],
		  'slug' => my_generate_slug('blog', 'subject', my_post('blog_subject')),
		  'cover_photo' => $file_name,
		  'catalog' => my_post('blog_catalog'),
		  'subject' => my_post('blog_subject'),
		  'keyword' => my_post('blog_keyword'),
		  'body' => $this->input->post('blog_body', FALSE),
		  'created_time' => my_server_time(),
		  'updated_time' => my_server_time(),
		  'read_times' => '0',
		  'comments' => '',
		  'enabled' => $blog_enabled
		);
		$this->db->insert('blog', $insert_data);		
		return $ids;
	}
	
	
	
	public function blog_update($ids) {
		(my_post('blog_enabled') == '1') ? $blog_enabled = 1 : $blog_enabled = 0;
		$file_name = '';
		if ($_FILES['userfile']['name'] != '') {
			$file_name = $this->blog_upload_cover_photo($ids);
		}
		$update_array = array(
		  'catalog' => my_post('blog_catalog'),
		  'keyword' => my_post('blog_keyword'),
		  'subject' => my_post('blog_subject'),
		  'body' => $this->input->post('blog_body', FALSE),
		  'updated_time' => my_server_time(),
		  'enabled' => $blog_enabled
		);
		($file_name != '') ? $update_array['cover_photo'] = $file_name : null;
		$this->db->where('ids', $ids)->update('blog', $update_array);
		return TRUE;
	}
	
	
	
	public function blog_upload_cover_photo($ids) {
		$this->load->library('m_upload');
		$this->m_upload->set_upload_path('/' . $this->config->item('my_upload_directory') . 'blog/');
		$this->m_upload->set_allowed_types('png|jpg|jpeg|gif');
		$this->m_upload->set_max_width(19200);
		$this->m_upload->set_max_height(10800);
		$this->m_upload->set_max_size(10000);
		$this->m_upload->set_file_name($ids);
		$res = $this->m_upload->upload_done();
		($res['status']) ? $file_name = $ids . '.' . strtolower(pathinfo($_FILES["userfile"]["name"], PATHINFO_EXTENSION)) : $file_name = '';
		return $file_name;
	}
	
	
	
	protected function payment_apply($item_ids, $item_type) {
		if (my_post('bulk_actions') != '0' && $item_type != 'top-up') {
			switch (my_post('bulk_actions')) {
				case '1' :  //apply to all
				  $query = $this->db->get('tts_resource');
				  break;
				case '2' :  //apply to all standard voice
				  $query = $this->db->like('engine', 'standard')->get('tts_resource');
				  break;
				case '3' :  //apply to all neural voice
				  $query = $this->db->like('engine', 'neural')->get('tts_resource');
				  break;
				case '4' :  //apply to all aws
				  $query = $this->db->where('scheme', 'aws')->get('tts_resource');
				  break;
				case '5' :  //apply to all google
				  $query = $this->db->where('scheme', 'google')->get('tts_resource');
				  break;
				case '6' : //apply to all azure
				  $query = $this->db->where('scheme', 'azure')->get('tts_resource');
				  break;
			}
			if ($query->num_rows()) {
				$rs = $query->result();
				$bulk_actions = my_post('bulk_actions');
				foreach ($rs as $row) {
					if ($bulk_actions == '1' || $bulk_actions == '4' || $bulk_actions == '5' || $bulk_actions == '6') {  // 1,4,5,6 apply to standard and neural voice
					    $accessibility_standard = $row->accessibility_standard;
						if (!my_check_str_contains($accessibility_standard, $item_ids)) {
							($accessibility_standard == '') ? $accessibility_standard = $item_ids : $accessibility_standard .= ',' . $item_ids;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_standard'=>$accessibility_standard));
						}
						$accessibility_neural = $row->accessibility_neural;
						if (!my_check_str_contains($accessibility_neural, $item_ids)) {
							($accessibility_neural == '') ? $accessibility_neural = $item_ids : $accessibility_neural .= ',' . $item_ids;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_neural'=>$accessibility_neural));
						}
					}
					elseif ($bulk_actions == '2') {  // apply to standard voice
					    $accessibility_standard = $row->accessibility_standard;
						if (!my_check_str_contains($accessibility_standard, $item_ids)) {
							($accessibility_standard == '') ? $accessibility_standard = $item_ids : $accessibility_standard .= ',' . $item_ids;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_standard'=>$accessibility_standard));
						}
					}
					elseif ($bulk_actions == '3') {  // apply to neural voice
					    $accessibility_neural = $row->accessibility_neural;
						if (!my_check_str_contains($accessibility_neural, $item_ids)) {
							($accessibility_neural == '') ? $accessibility_neural = $item_ids : $accessibility_neural .= ',' . $item_ids;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_neural'=>$accessibility_neural));
						}
					}
				}
			}
		}
		return TRUE;
	}
	
	
}