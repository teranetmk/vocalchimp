<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translation_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
	}

	public function save_translation($data) {
		$insert_array = array(
		  'user_ids' => $_SESSION['user_ids'],
		  'name' => $data['name'],
		  'native_language' => $data['native_language'],
		  'native_text' => $this->security->xss_clean($data['native_text']),
		  'translated_language' => $data['translated_language'],
		  'translated_text' => $this->security->xss_clean($data['translated_text']),
		  'created_time' => my_server_time()
		);
			$this->db->insert('translations', $insert_array);
		}

	public function get_list() {
		return $this->db->where('user_ids', $_SESSION['user_ids'])->order_by('id', 'DESC')->get('translations')->result();
	}

	public function delete($id) {
		$this->db->delete('translations', ['id' => $id]);
	}

	public function find_by_id($id) {
		$row = $this->db->where('user_ids', $_SESSION['user_ids'])->where('id', $id)->get('translations', 1)->row();
		return $row;
	}
}
