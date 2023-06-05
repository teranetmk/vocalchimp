<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic_model extends CI_Model {



	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
	}
	
	
	
	public function upgrade($version_array) {
		if (strcmp($version_array['db_version'], $version_array['file_version']) != 0) { // do upgrade
		    $this->load->dbforge();
		    $step = $version_array['db_version'] . '->' . $version_array['next_version'];
		    switch ($step) {
				case '1.0.0->1.1.0' :  // 8 changes in this update
				  //change 1, add field "payment_setting" to table "setting"
				  if (!$this->db->field_exists('payment_setting', 'setting')) {
					  $fields = array('payment_setting' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('payment_setting'=>'{"type":"disabled","feature":"both","stripe_one_time_enabled":"0","stripe_recurring_enabled":"0","stripe_publishable_key":"","stripe_secret_key":"","stripe_signing_secret":"","paypal_one_time_enabled":"0","paypal_recurring_enabled":"0","paypal_client_id":"","paypal_secret":"","paypal_webhook_id":""}'));
				  }
				  //change 2, add field "version" to table "setting"
				  if (!$this->db->field_exists('version', 'setting')) {
					  $fields = array('version' => array('type'=>'varchar', 'constraint'=>'10', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('version'=>'1.0.0'));
				  }
				  //change 3, add field "balance" to table "user"
				  if (!$this->db->field_exists('balance', 'user')) {
					  $fields = array('balance' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('user', $fields);
					  $this->db->update('user', array('balance'=>'{"usd":0}'));
				  }
				  //change 4, add table "payment_item"
				  if (!$this->db->table_exists('payment_item')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'enabled' => array('type'=>'tinyint', 'constraint'=>'1', 'null'=>FALSE),
						'type' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'item_name' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'item_description' => array('type'=>'text', 'null'=>FALSE),
						'item_currency' => array('type'=>'char', 'constraint'=>'3', 'null'=>FALSE),
						'item_price' => array('type'=>'double', 'null'=>FALSE),
						'recurring_interval' => array('type'=>'varchar', 'constraint'=>'5', 'null'=>FALSE),
						'recurring_interval_count' => array('type'=>'int', 'constraint'=>'11', 'null'=>FALSE),
						'stuff_setting' => array('type'=>'text', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('payment_item', FALSE, $attributes);
					  
				  }
				  //change 5, add table "payment_log"
				  if (!$this->db->table_exists('payment_log')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'type' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'gateway' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'currency' => array('type'=>'char', 'constraint'=>'3', 'null'=>FALSE),
						'amount' => array('type'=>'double', 'null'=>FALSE),
						'quantity' => array('type'=>'int', 'constraint'=>'11', 'null'=>FALSE),
						'gateway_identifier' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'item_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'item_name' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'redirect_status' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'callback_status' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'callback_time' => array('type'=>'timestamp', 'null'=>TRUE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('payment_log', FALSE, $attributes);
				  }
				  //change 6, add table "payment_subscription"
				  if (!$this->db->table_exists('payment_subscription')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'item_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'payment_gateway' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'gateway_identifier' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'quantity' => array('type'=>'int', 'constraint'=>'11', 'null'=>FALSE),
						'status' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'start_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'end_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'updated_time' => array('type'=>'timestamp', 'null'=>TRUE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('payment_subscription', FALSE, $attributes);
				  }
				  //change 7, add a data record to table "permission"
				  $query_permission = $this->db->where('ids', 'g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT')->get('permission', 1);
				  if (!$query_permission->num_rows()) {
					  $insert_data = array('ids' => 'g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT', 'built_in' => '1', 'name' => 'Payment_Management');
					  $this->db->insert('permission', $insert_data);
				  }
				  //change 8, modify the value of "permission" in table "role", Only for "super admin" role
				  $rs_role = $this->db->get('role')->result();
				  foreach ($rs_role as $row) {
					  $permission_array = json_decode($row->permission, 1);
					  if (!array_key_exists('g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT', $permission_array)) {
						  ($row->id == 1) ? $permission_array['g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT'] = true : $permission_array['g8aI6bw5V6d930844f19fc137ac17260fe6b65043gQ9HKemkT'] = false;
						  $this->db->where('id', $row->id)->update('role', array('permission'=>json_encode($permission_array)));
					  }
				  }
				  break;
				case '1.1.0->1.2.0' :
				  if (!$this->db->field_exists('gateway_event_id', 'payment_log')) {
					  $fields = array('gateway_event_id' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_log', $fields);
				  }
				  if (!$this->db->field_exists('invoice_setting', 'setting')) {
					  $fields = array('invoice_setting' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('invoice_setting'=>'{"enabled":1,"company_name":"","address_line_1":"","address_line_2":"","phone":""}'));
				  }
				  if (!$this->db->field_exists('company', 'user')) {
					  $fields = array('company' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE));
					  $this->dbforge->add_column('user', $fields);
				  }
				  break;
				case '1.2.0->1.3.0' :
				  // change 1: bug fix for 1.2.0 at user.balance fields
				  $this->db->where('balance', '{\\"usd\\":0}')->update('user', array('balance'=>'{"usd":0}'));
				  // change 2: add table: catalog
				  if (!$this->db->table_exists('catalog')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'type' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'name' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('catalog', FALSE, $attributes);
				  }
				  // change 3: add table: ticket
				  if (!$this->db->table_exists('ticket')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'ids_father' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'source' => array('type'=>'varchar', 'constraint'=>'10', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_fullname' => array('type'=>'varchar', 'constraint'=>'100', 'null'=>FALSE),
						'main_status' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE),
						'read_status' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'priority' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE),
						'subject' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'description' => array('type'=>'longtext', 'null'=>FALSE),
						'associated_files' => array('type'=>'text',  'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'updated_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'rating' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('ticket', FALSE, $attributes);
				  }
				  // change 4: add field: setting.ticket_setting, apply a initial value
				  if (!$this->db->field_exists('ticket_setting', 'setting')) {
					  $fields = array('ticket_setting' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('ticket_setting'=>'{"enabled":0,"guest_ticket":0,"rating":1,"allow_upload":0,"notify_agent_list":"","notify_user":0,"close_rule":"3"}'));
				  }
				  // change 5: add permission(Support Management)
				  $query_permission = $this->db->where('ids', 'VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm')->get('permission', 1);
				  if (!$query_permission->num_rows()) {
					  $insert_data = array('ids' => 'VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm', 'built_in' => '1', 'name' => 'Support_Management');
					  $this->db->insert('permission', $insert_data);
				  }
				  //change 6 : update role accordingly
				  $rs_role = $this->db->get('role')->result();
				  foreach ($rs_role as $row) {
					  $permission_array = json_decode($row->permission, 1);
					  if (!array_key_exists('VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm', $permission_array)) {
						  ($row->id == 1) ? $permission_array['VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm'] = true : $permission_array['VzamDpcvhb706e8635168dd315656a30654e13db9fqcjSnpsm'] = false;
						  $this->db->where('id', $row->id)->update('role', array('permission'=>json_encode($permission_array)));
					  }
				  }
				  // change 7: add two email templates
				  $insert_array = array(
				    'ids' => 'tzqM8x3p7174d8c2d251203aaeeaa85fe4d6ad8338BhC0Oq5W',
					'purpose' => 'ticket_notify_agent',
					'built_in' => 1,
					'subject' => 'A new ticket raised',
					'body' => '<p>A new ticket raised or updated. Please sign in and check.</p>'
				  );
				  $this->db->insert(' email_template', $insert_array);
				  $insert_array = array(
				    'ids' => '8HKUnZ5F24a9ce26849ee64dcba7aaa2187950d40vHQYtzcsK',
					'purpose' => 'ticket_notify_user',
					'built_in' => 1,
					'subject' => 'Your ticket has been replied',
					'body' => '<p>Dear customer,</p><p>You ticket has been replied by our agent(s). Please sign in and check.</p><p><br /></p><p>Best Regards,</p><p>CyberBukit Membership Support</p><p>https://membership.demo.cyberbukit.com</p>'
				  );
				  $this->db->insert(' email_template', $insert_array);
				  break;
				case '1.3.0->1.4.0' :
				  // change 1: add fields to payment_log and assign default value
				  if (!$this->db->field_exists('price', 'payment_log')) {
					  $fields = array('price' => array('type'=>'double', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_log', $fields);
					  $this->db->query('update payment_log set price = amount');
				  }
				  if (!$this->db->field_exists('visible_for_user', 'payment_log')) {
					  $fields = array('visible_for_user' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_log', $fields);
				  }
				  if (!$this->db->field_exists('generate_invoice', 'payment_log')) {
					  $fields = array('generate_invoice' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_log', $fields);
				  }
				  if (!$this->db->field_exists('description', 'payment_log')) {
					  $fields = array('description' => array('type'=>'varchar', 'constraint'=>'1024', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_log', $fields);
				  }
				  $this->db->update('payment_log', array('visible_for_user'=>1, 'generate_invoice'=>1, 'description'=>''));
				  $this->db->update('payment_log', array('description'=>''));
				  //change 2: add fields to payment_subscription
				  if (!$this->db->field_exists('description', 'payment_subscription')) {
					  $fields = array('description' => array('type'=>'varchar', 'constraint'=>'1024', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_subscription', $fields);
				  }
				  $this->db->update('payment_subscription', array('description'=>''));
				  break;
				case '1.4.0->1.5.0' :
				  // change 1: add fields to setting and assign default value
				  if (!$this->db->field_exists('file_setting', 'setting')) {
					  $fields = array('file_setting' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('file_setting'=>'{"file_type":"jpg|jpeg|png|gif|svg|zip|rar|pdf|mp3|mp4|doc|docx|xls|xlsx","file_size":"102400"}'));
				  }
				  if (!$this->db->field_exists('front_setting', 'setting')) {
					  $fields = array('front_setting' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('front_setting'=>'{"enabled":0,"logo":"logo.png","company_name":"CyberBukit","email_address":"support@cyberbukit.com","html_title":"CyberBukit","html_author":"CyberBukit","html_description":"","html_keyword":"","about_us":"","pricing_enabled":1,"faq_enabled":1,"documentation_enabled":1,"blog_enabled":1,"subscriber_enabled":1,"social_facebook":"","social_twitter":"","social_linkedin":"","social_github":""}'));
				  }
				  // change 2: add fields to catalog and assign default value
				  if (!$this->db->field_exists('description', 'catalog')) {
					  $fields = array('description' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('catalog', $fields);
				  }
				  // change 3: add table contact_form
				  if (!$this->db->table_exists('contact_form')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'name' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'email_address' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'phone' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'message' => array('type'=>'text', 'null'=>FALSE),
						'ip_address' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'read_status' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('contact_form', FALSE, $attributes);
				  }
				  // change 4: add table documentation
				  if (!$this->db->table_exists('documentation')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'slug' => array('type'=>'varchar', 'constraint'=>'512', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'subject' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'body' => array('type'=>'text', 'null'=>FALSE),
						'attachment' => array('type'=>'text', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'enabled' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('documentation', FALSE, $attributes);
				  }
				  // change 5: add table faq
				  if (!$this->db->table_exists('faq')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'subject' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'body' => array('type'=>'text', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'enabled' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('faq', FALSE, $attributes);
				  }
				  // change 6: add table file_manager
				  if (!$this->db->table_exists('file_manager')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'temporary_ids' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'original_filename' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'file_ext' => array('type'=>'varchar', 'constraint'=>'10', 'null'=>FALSE),
						'description' => array('type'=>'text', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'trash' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('file_manager', FALSE, $attributes);
				  }
				  // change 7: add table subscriber
				  if (!$this->db->table_exists('subscriber')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'email_address' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'from_ip' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('subscriber', FALSE, $attributes);
				  }
				  break;
				case '1.5.0->1.6.0' :
				  // change 1: add 2 fields to setting and assign default value
				  if (!$this->db->field_exists('html_purify', 'setting')) {
					  $fields = array('html_purify' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('html_purify'=>1));
				  }
				  if (!$this->db->field_exists('xss_clean', 'setting')) {
					  $fields = array('xss_clean' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE));
					  $this->dbforge->add_column('setting', $fields);
					  $this->db->update('setting', array('xss_clean'=>1));
				  }
				  // change 2: add 2 fields to payment_item and assign default value
				  if (!$this->db->field_exists('purchase_limit', 'payment_item')) {
					  $fields = array('purchase_limit' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_item', $fields);
					  $this->db->update('payment_item', array('purchase_limit'=>0));
				  }
				  if (!$this->db->field_exists('access_condition', 'payment_item')) {
					  $fields = array('access_condition' => array('type'=>'text', 'null'=>FALSE));
					  $this->dbforge->add_column('payment_item', $fields);
					  $this->db->update('payment_item', array('access_condition'=>'0'));
				  }
				  //change 3: add table payment_purchased
				  if (!$this->db->table_exists('payment_purchased')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'payment_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'item_type' => array('type'=>'varchar', 'constraint'=>'12', 'null'=>FALSE),
						'item_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'item_name' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('payment_purchased', FALSE, $attributes);
				  }
				  //change 4: add 2 fields to documentation
				  if (!$this->db->field_exists('keyword', 'documentation')) {
					  $fields = array('keyword' => array('type'=>'varchar', 'constraint'=>'1024', 'null'=>FALSE));
					  $this->dbforge->add_column('documentation', $fields);
					  $this->db->update('documentation', array('keyword'=>''));
				  }
				  if (!$this->db->field_exists('updated_time', 'documentation')) {
					  $fields = array('updated_time' => array('type'=>'timestamp', 'null'=>TRUE));
					  $this->dbforge->add_column('documentation', $fields);
					  $this->db->update('documentation', array('updated_time'=>'2020-12-12 00:00:00'));
				  }
				  //change 5: add table blog
				  if (!$this->db->table_exists('blog')) {
					  $fields = array(
					    'id' => array('type'=>'int', 'constraint'=>'11', 'auto_increment'=>TRUE),
						'ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'author' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'user_ids' => array('type'=>'char', 'constraint'=>'50', 'null'=>FALSE),
						'slug' => array('type'=>'varchar', 'constraint'=>'512', 'null'=>FALSE),
						'cover_photo' => array('type'=>'varchar', 'constraint'=>'255', 'null'=>FALSE),
						'catalog' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'subject' => array('type'=>'varchar', 'constraint'=>'50', 'null'=>FALSE),
						'keyword' => array('type'=>'varchar', 'constraint'=>'1024', 'null'=>FALSE),
						'body' => array('type'=>'text', 'null'=>FALSE),
						'created_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'updated_time' => array('type'=>'timestamp', 'null'=>TRUE),
						'read_times' => array('type'=>'int', 'constraint'=>'11', 'null'=>FALSE),
						'comments' => array('type'=>'text', 'null'=>FALSE),
						'enabled' => array('type'=>'tinyint', 'constraint'=>'4', 'null'=>FALSE)
					  );
					  $this->dbforge->add_field($fields);
					  $this->dbforge->add_key('id', TRUE);
					  $attributes = array('ENGINE'=>'InnoDB');
					  $this->dbforge->create_table('blog', FALSE, $attributes);
				  }
				  break;
			}
			$this->db->update('setting', array('version' => $version_array['file_version']));
			$this->session->set_flashdata('upgrade_notice', '<font color="red">Upgrade from ' . $version_array['db_version'] . ' to ' . $version_array['next_version'] . ' successfully.</font><br><br>');
		}
		return TRUE;
	}
	

}