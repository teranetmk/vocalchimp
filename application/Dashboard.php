<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {
	
    public function __construct() {
		parent::__construct();
		
    }
	
	
	
	public function index() {
		$data = array();
		if (my_check_permission('TTS Management') || my_check_permission('User Management')) {
			$rs = $this->db->order_by('id', 'desc')->get('user', 8)->result();
			$data['rs_user'] = $rs;
			$query_earning = $this->db->where('callback_status', 'success')->select_sum('amount')->get('payment_log');
			$rs_earning = $query_earning->result();
			(!is_null($rs_earning[0]->amount)) ? $data['earnings'] = $rs_earning[0]->amount : $data['earnings'] = 0;
			$data += $this->dashboard_statistics();
			$data += $this->dashboard_tts_statistics();
		}
		my_load_view($this->setting->theme, 'dashboard', $data);
	}
	
	
	
	public function search_action() {
		redirect(base_url('admin/list_user/search' . '/' . my_post('keyword')));
	}
	
	
	
	protected function dashboard_statistics() {
		$statistics['users_amount'] = $this->db->count_all_results('user');
		$statistics['user_pending_amount'] = $this->db->where('status', 0)->count_all_results('user');
		$statistics['user_today_amount'] = $this->db->where('created_time>=', my_conversion_from_local_to_server_time(date('Y-m-d'), $this->user_timezone, 'Y-m-d H:i:s'))->count_all_results('user');
		$statistics['user_online_amount'] = $this->db->where('online', 1)->count_all_results('user');
		
		$rs = $this->db->where('type', 'signup_last_six_days')->get('statistics', 1)->row();
		$signup_last_six_days  = json_decode($rs->value, TRUE);
		$signup_last_six_days_date = '';
		$signup_last_six_days_amount = '';
		foreach ($signup_last_six_days as $date=>$amount) {
			$signup_last_six_days_date .= my_conversion_from_server_to_local_time($date, $this->user_timezone, $this->user_date_format) . ',';
			$signup_last_six_days_amount .= $amount . ',';
		}
		$signup_last_six_days_date .= 'Today';
		$signup_last_six_days_amount .= $statistics['user_today_amount'];
		$statistics['signup_last_six_days_date'] = $signup_last_six_days_date;
		$statistics['signup_last_six_days_amount'] = $signup_last_six_days_amount;
		
		return $statistics;
	}
	
	
	
	public function dashboard_tts_statistics() {
		$this->db->select_sum('payg_purchased');
		$this->db->select_sum('characters_preview_used');
		$this->db->select_sum('characters_production_used');
		$this->db->select_sum('voice_generated');
		$statistics_result = $this->db->get('tts_statitics')->result();
		$tts_statistics['payg_purchased'] = intval($statistics_result[0]->payg_purchased/1000000) . 'M';
		$characters_preview_used = $statistics_result[0]->characters_preview_used;
		($characters_preview_used > 10000) ? $characters_preview_used = doubleval($characters_preview_used/1000) . 'k' : null;
		$tts_statistics['characters_preview_used'] = $characters_preview_used;
		$characters_production_used = $statistics_result[0]->characters_production_used;
		($characters_production_used > 10000) ? $characters_production_used = doubleval($characters_production_used/1000) . 'k' : null;
		$tts_statistics['characters_production_used'] = $characters_production_used;
		$tts_statistics['voice_generated'] = $statistics_result[0]->voice_generated;
		return $tts_statistics;
	}

}
?>