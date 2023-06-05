<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Generic extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
        date_default_timezone_set($this->config->item('time_reference'));
		$this->setting = my_global_setting('all_fields');		
	}

	
	
	// This is used for switch language, selected language is saved in cookie, the cookie name is "site_lang".
	// It'll redirect back to current url after choosed.
	public function switchLang($language = "") {
		my_set_language_cookie($language);
		redirect($_SERVER['HTTP_REFERER']);
	}
	
	
	
	public function sign_out() {
		$this->load->model('user_model');
		(get_cookie('remember_signin', TRUE) != '') ? $this->user_model->remove_cookie('remember_signin', get_cookie('remember_signin', TRUE)) : null;
		$this->db->where('ids', $_SESSION['user_ids'])->update('user', array('online'=>0));
		$this->load->helper('cookie');
		delete_cookie('vd_email');
		delete_cookie('vd_a');
		$this->session->sess_destroy();
		redirect(site_url());
	}	
	
	
	
	public function terms_conditions() {
		$setting = my_global_setting('all_fields');
		$data['setting'] = $setting;
		my_load_view($setting->theme, 'Auth/terms_conditions', $data);
	}
	
	
	
	public function online() {
		$this->db->where('ids', my_uri_segment(3))->update('user', array('online'=>1, 'online_time'=>my_server_time()));
		echo '0';
	}
	
	
	
	public function upgrade() {  //upgrade the database structure
		$version_array = $this->get_version();
		if (strcmp($version_array['db_version'], $version_array['file_version']) != 0) { //db version and file version are different
			$db_version_array = explode('.', $version_array['db_version']);
			$file_version_array = explode('.', $version_array['file_version']);
			if (intval($db_version_array[1]) < intval($file_version_array[1])) {  // need to upgrade the database
				$html = 'New Upgrade Available. Upgrade needs to be done version by version so you may need to perform several times.<br><br>';
				$html .= 'Current Version: ' . $version_array['db_version'] . '<br><br>';
				$html .= 'New Version Available To Upgrade: ' . $version_array['next_version'] . '<br><br>';
				$html .= '<b>IMPORTANT NOTICE : </b>Before clicking the following link, You should fully backup your current database first, It\'s an irreversible operation. <br><br>';
				$html .= '<a href="' . base_url('generic/upgrade_action') . '">PROCEED TO UPGRADE</a>';
			}
			elseif (intval($db_version_array[1]) == intval($file_version_array[1])) { // only need to replace file, no change in database
				$html = 'In order to upgrade you just need to replace old files with new ones because there are no changes in database, Backup all your old files before replacement.';
			}
			else { //something error
				$html = 'Unable to perform the upgrade process.<br>Failure Reason: The version of your database is newer than  the file\'s.';
			}
		}
		else { // up to date
			$html = 'Current version is up to date, No action required.';
		}
		$data['html'] = '<p>' . $this->session->flashdata('upgrade_notice') . $html . '</p>';
		$this->load->view('upgrade', $data);
	}
	
	
	
	public function upgrade_action() {
		$this->load->model('generic_model');
		$this->generic_model->upgrade($this->get_version());
		redirect(base_url('generic/upgrade'));
	}
	
	
	
	protected function get_version() {
		($this->db->field_exists('version', 'setting')) ? $db_version = $this->db->get('setting', 1)->row()->version : $db_version = '1.0.0';
		(!is_null($this->config->item('my_version'))) ? $file_version = $this->config->item('my_version') : $file_version = '1.0.0';
		$db_version_array = explode('.', $db_version);
		$file_version_array = explode('.', $file_version);
		$next_version = strval($db_version_array[0]) . '.' . strval(intval($db_version_array[1]) + 1) . '.' . strval('0');
		return array(
		  'db_version' => $db_version,
		  'file_version' => $file_version,
		  'next_version'=> $next_version
		);
	}
	
	
}