<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tts extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->helper('my_tts');
		$this->load->model('tts_model');
    }
	
	
	
	public function index() {
		$this->load->library('m_tts');
		$this->m_tts->syncResource('azure');
	}
	
	
	
	public function start() {
		$data = get_subscription_info();
		if (!$data && !$_SESSION['is_admin'] ){
			$this->session->set_flashdata('flash_danger', 'Your credits have exhausted. Please upgrade your plan to continue. (If you made purchase with PayPal please allow 2~3 mins)');
			
			return redirect('/user/pay_now');
		}

		$incomplete = incomplete_check();
		if($incomplete && !$_SESSION['is_admin'])
		{
			$this->session->set_flashdata('flash_danger', 'Your payment failed. Please update your card details and try again');
			return redirect('/user/pay_now');
		}

		my_load_view($this->setting->theme, 'Tts/start', $data);
	}


	
	
	
	public function start_action() {
		$tts_check_result = $this->tts_basic_check(my_post('tts_text'));
		if (!$tts_check_result['result']) {
			$res = '{"result":false, "message":"' . $tts_check_result['message'] . '"}';
		}
		else {
			$query = $this->db->where('ids', my_post('tts_resource_ids'))->where('enabled', 1)->get('tts_resource', 1);
			if (!$query->num_rows()) {
				$res = '{"result":false, "message":"' . my_caption('tts_voice_unavailable') . '"}';
			}
			else {
				$rs = $query->row();
				if (my_post('ssml_mode') == '1' && $this->tts_config->ssml) {
					$tts_text = $this->input->post('tts_text', FALSE);
					$ssml_mode = TRUE;
				}
				else {
					$tts_text = my_post('tts_text');
					$ssml_mode = FALSE;
				}
				$text_array = $this->text_builder($tts_text, $rs->scheme, $ssml_mode);  //handle the text for multiple purposes
				$tts_config_array = array('output_format'=>'mp3','output_volume'=>my_post('tts_ssml_volume'), 'spk_rate'=>my_post('tts_ssml_spk_rate'));
				(my_post('synthesize_type') == 'preview') ? $file_path = $this->tts_config->file_path_preview : $file_path = $this->tts_config->file_path_user;
				$config = array(
				  'ids' => my_random(),
				  'voice_ids' => $rs->ids,
				  'scheme' => $rs->scheme,
				  'engine' => my_post('tts_engine'),
				  'language_code' => $rs->language_code,
				  'language_name' => $rs->language_name,
				  'voice_id' => $rs->voice_id,
				  'gender' => $rs->gender,
				  'voice_name' => $rs->gender . ', ' . $rs->name,
				  'output_format' => 'mp3',
				  'text_type' => $text_array['text_type'],
				  'ssml_mode' => $ssml_mode,
				  'text' => $text_array['text_text'],
				  'characters_count' => $text_array['text_length'],
				  'file_path' => $file_path,
				  'tts_config' => json_encode($tts_config_array),
				  'synthesize_type' => my_post('synthesize_type'),
				);
				if ($config['scheme'] == 'azure' && ($config['language_code'] == 'ja-JP' || $config['language_code'] == 'ko-KR' || $config['language_code'] == 'yue-HK' || $config['language_code'] == 'cmn-TW' || $config['language_code'] == 'cmn-CN')) { //Japanese, Korean, Chinese in Azure will be counted twice
					$config['characters_count'] = 2*$config['characters_count'];
				}
				($config['scheme'] == 'aws' && $config['characters_count'] > 2999) ? $config['storage'] = 'S3' : $config['storage'] = $this->tts_config->storage;  //force to S3 according to aws requirement
				$this->load->library('m_billing');
				$billing_array = $this->m_billing->billing($config);
				if ($billing_array['result']) {
					$this->load->library('m_tts');
					$res = $this->m_tts->synthesis($config);
					if ($res['result']) {  //synthesis successfully
						$config['tts_uri'] = $res['tts_uri']; //file path
						($config['synthesize_type'] == 'preview') ? $res['tts_uri'] = base_url($res['tts_uri']) : null;  //for play preview only
						$this->tts_model->save_tts($config);  //save to db
					}
					$this->update_statitics($config['synthesize_type'], $config['characters_count']);  //update statitics
					$res = json_encode($res);
				}
				else {
					$res = '{"result":false, "message":"' . $billing_array['message'] . '"}';
				}
			}
		}
		echo my_esc_html($res);
	}
	
	
	
	public function download() {
		if (!my_check_permission('TTS Management')) {
			$this->db->where('user_ids', $_SESSION['user_ids']);
		}
		$query = $this->db->where('ids', my_uri_segment(3))->get('tts_log', 1);
		if ($query->num_rows()) {
			$rs = $query->row();
			$tts_uri = $rs->tts_uri;
			$this->load->helper('download');
			if (substr($tts_uri, 0, 4) == 'http') {
				if ($this->tts_config->download_type == 'flexible') { //jump to the file
					redirect($tts_uri);
				}
				else {  //download to local server and push to client
					$tts_config_array = json_decode($rs->config, TRUE);
					$file_name = my_uri_segment(3) . '.' . $tts_config_array['output_format'];
					$data = file_get_contents($tts_uri);
					force_download($file_name, $data);
				}
			}
			else {
				force_download($tts_uri, NULL);
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function view() {
		if (!my_check_permission('TTS Management')) {
			$this->db->where('user_ids', $_SESSION['user_ids']);
		}
		$query = $this->db->where('ids', my_uri_segment(3))->get('tts_log', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Tts/tts_view', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function admin_tts_view() {
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->view();
	}
	
	
	
	public function remove() {
		// my_check_demo_mode('alert_json');  //check if it's in demo mode
		if (!my_check_permission('TTS Management')) {  //user is only allowed to remove his own file
			$this->db->where('user_ids', $_SESSION['user_ids']);
		}
		$query = $this->db->where('ids', my_uri_segment(3))->get('tts_log', 1);
		if ($query->num_rows()) {
			$rs = $query->result();
			$this->load->library('m_tts');
			$result = $this->m_tts->deleteObject($rs);
			$this->db->where('ids', my_uri_segment(3))->delete('tts_log');
		}
		($result) ? $notice_text = my_caption('global_deleted_notice_message') : $notice_text = my_caption('tts_file_notice_delete_partly_success');
		echo '{"result":true, "title":"' . my_caption('global_deleted_notice_title') . '", "text":"'. $notice_text . '", "redirect":"CallBack"}';
	}
	
	
	
	public function get_language_detail() {
		$query = $this->db->where('enabled', 1)->like('engine', 'neural')->order_by('scheme', 'asc')->order_by('name', 'asc')->get('tts_resource');
		$result = array();
		if ($query->num_rows()) {
			$rs = $query->result();
			foreach ($rs as $row) {
				$language_array = array(
				  'ids' => $row->ids,
				  'scheme' => $row->scheme,
				  'language_name' => $row->language_name,
				  'language_code' => $row->language_code,
				  'voice_id' => $row->voice_id,
				  'engines' => $row->engine,
				  'gender' => str_replace('Male', my_caption('global_gender_male'), str_replace('Female', my_caption('global_gender_female'), $row->gender)),
				  'name' => $row->name,
				  'description' => $row->description,
				  'accessibility_standard' => $row->accessibility_standard,
				  'accessibility_neural' => $row->accessibility_neural
				);
				array_push($result, $language_array);
			}
		}
		echo json_encode($result);
	}
	
	
	
	public function admin_resource() {
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Tts/admin_resource');
	}
	
	
	
	public function admin_resource_bulk_action() {
		my_check_demo_mode();  //check if it's in demo mode
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$action = my_uri_segment(3);
		if ($action == 'tts_sync_aws' || $action == 'tts_sync_google') {
			$rs_tts_config = $this->db->get('tts_configuration', 1)->row();
			($action == 'tts_sync_aws') ? $tts_config_array = json_decode($rs_tts_config->aws, TRUE) : $tts_config_array = json_decode($rs_tts_config->google, TRUE);
			if ($tts_config_array['config_file'] != '') {
				if (!$this->check_file_exists($tts_config_array['config_file'])) {
					$this->session->set_flashdata('flash_danger', my_caption('tts_sync_resource_config_file_not_found'));
					redirect(base_url('tts/admin_resource'));
				}
			}
			else {
				$this->session->set_flashdata('flash_danger', my_caption('tts_sync_resource_not_config'));
				redirect(base_url('tts/admin_resource'));
			}
		}
		$this->load->library('m_tts');
		$result = TRUE;
		if ($action == 'tts_sync_aws') {  //sync from aws
			$result = $this->m_tts->syncResource('aws');
			($result) ? $notice = my_caption('tts_sync_resource_notice_success') : $notice = my_caption('tts_sync_resource_notice_fail');
		}
		elseif ($action == 'tts_sync_google') {  //sync from google
			$result = $this->m_tts->syncResource('google');
			($result) ? $notice = my_caption('tts_sync_resource_notice_success') : $notice = my_caption('tts_sync_resource_notice_fail');
		}
		elseif ($action == 'tts_sync_azure') {  //sync from azure
			$result = $this->m_tts->syncResource('azure');
			($result) ? $notice = my_caption('tts_sync_resource_notice_success') : $notice = my_caption('tts_sync_resource_notice_fail');
		}
		elseif (substr($action, 0, 11) == 'bulk_enable') { //bulk enabled
			$scheme = str_replace('bulk_enable_', '', $action);
			$this->db->where('scheme', $scheme)->where('enabled', '0')->update('tts_resource', array('enabled'=>'1'));
			$notice = my_caption('tts_resource_notice_enable_success');
		}
		elseif (substr($action, 0, 12) == 'bulk_disable') {  //bulk disable
		    $scheme = str_replace('bulk_disable_', '', $action);
			$this->db->where('scheme', $scheme)->where('enabled', '1')->update('tts_resource', array('enabled'=>'0'));
			$notice = my_caption('tts_resource_notice_disable_success');
		}
		elseif (substr($action, 0, 11) == 'bulk_delete') {  //bulk delete
		    $scheme = str_replace('bulk_delete_', '', $action);
			$this->db->where('scheme', $scheme)->delete('tts_resource');
			$notice = my_caption('tts_resource_notice_delete_success');
		}
		elseif (substr($action, 0, 9) == 'bulk_free' || substr($action, 0, 9) == 'bulk_payg') {  //other bulk operation
			$query = $this->db->get('tts_resource');
			$action = substr($action, 0, 9);
			if ($query->num_rows()) {
				$rs = $query->result();
				foreach ($rs as $row) {
					if ($action == 'bulk_free') {
						$accessibility_standard = $row->accessibility_standard;
						if (substr($accessibility_standard, 0, 4) != 'free') {
							($accessibility_standard == '') ? $accessibility_standard = 'free' : $accessibility_standard = 'free,' . $accessibility_standard;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_standard'=>$accessibility_standard));
						}
						$accessibility_neural = $row->accessibility_neural;
						if (substr($accessibility_neural, 0, 4) != 'free') {
							($accessibility_neural == '') ? $accessibility_neural = 'free' : $accessibility_neural = 'free,' . $accessibility_neural;
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_neural'=>$accessibility_neural));
						}
					}
					elseif ($action == 'bulk_payg') {
						$accessibility_standard = $row->accessibility_standard;
						if (!my_check_str_contains($accessibility_standard, 'payg')) {
							($accessibility_standard == '') ? $accessibility_standard = 'payg' : $accessibility_standard .= ',payg';
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_standard'=>$accessibility_standard));
						}
						$accessibility_neural = $row->accessibility_neural;
						if (!my_check_str_contains($accessibility_neural, 'payg')) {
							($accessibility_neural == '') ? $accessibility_neural = 'payg' : $accessibility_neural .= ',payg';
							$this->db->where('id', $row->id)->update('tts_resource', array('accessibility_neural'=>$accessibility_neural));
						}
					}
				}
			}
            (substr($action, 0, 9) == 'bulk_free') ? $notice = my_caption('tts_resource_notice_set_free') : $notice = my_caption('tts_resource_notice_set_payg');	
		}
		elseif (substr($action, 0, 11) == 'bulk_revoke') {
			$this->db->update('tts_resource', array('accessibility_standard'=>'', 'accessibility_neural'=>''));
			$notice = my_caption('tts_resource_notice_revoke');
		}
		else {  //unknown action
			$result = FALSE;
			$notice = my_caption('tts_notice_unknown_action');
		}
		if ($result) {
			$this->session->set_flashdata('flash_success', $notice);
		}
		else {
			$this->session->set_flashdata('flash_danger', $notice);
		}
		redirect(base_url('tts/admin_resource'));
	}
	
	
	
	public function admin_resource_edit() {
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_uri_segment(3))->get('tts_resource', 1);
		if ($query->num_rows()) {
			$data['rs'] = $query->row();
			my_load_view($this->setting->theme, 'Tts/admin_resource_edit', $data);
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function admin_resource_edit_action() {
		my_check_demo_mode();  //check if it's in demo mode
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$query = $this->db->where('ids', my_post('ids'))->get('tts_resource', 1);
		if ($query->num_rows()) {
			$this->form_validation->set_rules('tts_voice_name', my_caption('tts_voice_name'), 'trim|required|max_length[50]');
			$this->form_validation->set_rules('tts_voice_description', my_caption('global_description'), 'trim|max_length[255]');
			if ($this->form_validation->run() == FALSE) {
				$data['rs'] = $query->row();
				my_load_view($this->setting->theme, 'Tts/admin_resource_edit', $data);
			}
			else {
				$scope_standard_array = $this->input->post('access_scope_standard[]');
				$scopes_standard = '';
				foreach ($scope_standard_array as $scope) {
					$scopes_standard .= $scope . ',';
				}
				$scope_neural_array = $this->input->post('access_scope_neural[]');
				$scopes_neural = '';
				foreach ($scope_neural_array as $scope) {
					$scopes_neural .= $scope . ',';
				}
				(my_post('tts_resource_enabled') == '1') ? $enabled = 1 : $enabled = 0;
				$update_array = array(
				  'enabled' => $enabled,
				  'name' => my_post('tts_voice_name'),
				  'description' => my_post('tts_voice_description'),
				  'accessibility_standard' => rtrim($scopes_standard, ','),
				  'accessibility_neural' => rtrim($scopes_neural, ',')
				);
				$this->db->where('ids', my_post('ids'))->update('tts_resource', $update_array);
				$this->session->set_flashdata('flash_success', my_caption('tts_resource_notice_success'));
				redirect('tts/admin_resource_edit/' . my_post('ids'));
			}
		}
		else {
			echo my_caption('global_no_entries_found');
		}
	}
	
	
	
	public function admin_tts_list() {
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Tts/admin_tts_list');
	}
	
	
	
	public function admin_configuration() {
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		my_load_view($this->setting->theme, 'Tts/admin_configuration');
	}
	
	
	
	public function admin_configuration_action() {
		my_check_demo_mode();  //check if it's in demo mode
		(!my_check_permission('TTS Management')) ? die(my_caption('global_not_enough_permission')) : null; //check permission
		$this->form_validation->set_rules('ttsc_preview_delay', my_caption('tts_configuration_preview_delay'), 'trim|required|integer|greater_than[-1]|less_than[9]');
		$this->form_validation->set_rules('ttsc_maximum_characters', my_caption('tts_configuration_maximum_characters'), 'trim|required|integer|greater_than[0]|less_than[100000]');
		$this->form_validation->set_rules('ttsc_maximum_characters_preview', my_caption('tts_configuration_maximum_characters_preivew'), 'trim|required|integer|greater_than[0]|less_than[100000]');
		$this->form_validation->set_rules('ttsc_payg_price', my_caption('tts_configuration_payg_price'), 'trim|required|greater_than[0]|numeric');
		$this->form_validation->set_rules('ttsc_payg_characters', my_caption('tts_configuration_payg_characters_included'), 'trim|required|greater_than[0]|integer');
		if (my_post('ttsc_storage_solution') == 'S3') {
			$this->form_validation->set_rules('ttsc_aws_config_file', my_caption('tts_configuration_sp_config_file_path'), 'trim|required');
		}
		if (my_post('ttsc_storage_solution') == 'wasabi') {
			$this->form_validation->set_rules('ttsc_wasabi_config_file', my_caption('tts_configuration_sp_config_file_path'), 'trim|required');
		}
		if (my_post('ttsc_aws_config_file') != '') {
			$this->form_validation->set_rules('ttsc_aws_config_file', my_caption('tts_configuration_sp_config_file_path'), 'trim|callback_check_file_exists');
			$this->form_validation->set_rules('ttsc_aws_bucket', my_caption('tts_configuration_sp_bucket'), 'trim|required');
		}
		if (my_post('ttsc_google_config_file') != '') {
			$this->form_validation->set_rules('ttsc_google_config_file', my_caption('tts_configuration_sp_config_file_path'), 'trim|callback_check_file_exists');
		}
		if (my_post('ttsc_wasabi_config_file') != '') {
			$this->form_validation->set_rules('ttsc_wasabi_config_file', my_caption('tts_configuration_sp_config_file_path'), 'trim|callback_check_file_exists');
			$this->form_validation->set_rules('ttsc_wasabi_bucket', my_caption('tts_configuration_sp_bucket'), 'trim|required');
		}
		if ($this->form_validation->run() == FALSE) {
			$this->session->set_flashdata('flash_danger', my_caption('tts_configuration_notice_fail'));
			my_load_view($this->setting->theme, 'Tts/admin_configuration');
		}
		else {
			$this->tts_model->save_configuration();
			$this->session->set_flashdata('flash_success', my_caption('tts_configuration_notice_success'));
			redirect(base_url('tts/admin_configuration'));
		}
	}
	
	
	
	protected function tts_basic_check($text) {
		if (trim($text) == '') {
			$res_array = array('result'=>FALSE, 'message'=>my_caption('tts_text_required'));
		}
		else {
			$res_array = array('result'=>TRUE);
		}
		if ($res_array['result'] == TRUE && strlen(trim(preg_replace('/\s+/', ' ', $text))) > $this->tts_config->maximum_character) {
			$res_array = array('result'=>FALSE, 'message'=>my_caption('tts_notice_character_limit') . $this->tts_config->maximum_character);
		}
		return $res_array;		
	}
	
	
	
	public function check_file_exists($file_path) {
		if (file_exists($file_path)) {
			return TRUE;
		}
		else {
			$this->form_validation->set_message('check_file_exists', my_caption('tts_configuration_notice_file_not_exist'));
			return FALSE;
		}
	}
	
	
	
	protected function text_builder($text, $scheme, $ssml_mode) {
		$text_array = array();
		if ($ssml_mode) {
			$text_array['text_type'] = 'ssml';
			$text_array['text_text'] = $text;
		}
		else {
			$ssml_property = '';
			$text_check_result = my_tts_check_ssml(my_post('tts_text'), my_post('synthesize_type'));
			$text_array['text_type'] = $text_check_result['type'];
			$text_array['text_text'] = $text_check_result['text'];
			if (my_post('tts_ssml_volume') != 'default') {
				if (my_check_str_contains('x-soft,soft,medium,loud,x-loud', my_post('tts_ssml_spk_rate'))) {
					$ssml_property = ' volume="' . my_post('tts_ssml_volume') . '"';
				}
			}
			if (my_post('tts_ssml_spk_rate') != 'default') {
				if (my_check_str_contains('x-slow,x-slow,medium,fast,x-fast', my_post('tts_ssml_spk_rate'))) {
					$ssml_property .= ' rate="' . my_post('tts_ssml_spk_rate') . '"';
				}
			}
			if ($text_array['text_type'] == 'ssml' || $ssml_property != '') {
				$text_array['text_type'] = 'ssml';
				if ($scheme == 'azure') {
					(my_post('tts_ssml_spk_rate') != 'default' || my_post('tts_ssml_volume') != 'default') ? $text_array['text_text'] = '<prosody' . $ssml_property . '>' . $text_array['text_text'] . '</prosody>' : null;
				}
				else {
					$text_array['text_text'] = '<speak><prosody' . $ssml_property . '>' . $text_array['text_text'] . '</prosody></speak>';
				}
			}
		}
		//mb_strlen($text_array['text_text'], 'utf-8') //old use
		$text_array['text_length'] = preg_match_all ('/[^ ]/' , $text_array['text_text'], $matches);  //all handle with utf-8
		return $text_array;
	}
	
	

	
	protected function update_statitics($type, $characters_count) {
		$rs_statitics =  my_tts_generate_user_statitics(); //check & if not exist then generate
		if ($type == 'preview') {
			$characters_preview_used = $rs_statitics->characters_preview_used + $characters_count;
			$characters_production_used = $rs_statitics->characters_production_used;
			$voice_generated = $rs_statitics->voice_generated;
		}
		else {  //production
			$characters_preview_used = $rs_statitics->characters_preview_used;
			$characters_production_used = $rs_statitics->characters_production_used + $characters_count;
			$voice_generated = $rs_statitics->voice_generated + 1;
		}
		$update_data = array(
		  'characters_preview_used' => $characters_preview_used,
		  'characters_production_used' => $characters_production_used,
		  'voice_generated' => $voice_generated
		);
		$this->db->where('user_ids', $_SESSION['user_ids'])->update('tts_statitics', $update_data);
		return TRUE;
	}
	
}
?>