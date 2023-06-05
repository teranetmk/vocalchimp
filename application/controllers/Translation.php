<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Translation extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->load->helper('my_basic');
		$this->load->helper('my_tts_helper');
		$this->load->model('translation_model');
    }

	public function start($newform = false) {
		if ($_SESSION['user_ids']) 
		{
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
		}

		$this->load->library('m_google_translate');
		$data = [];
		$data['supported_languages'] = $this->m_google_translate->get_supported_languages();
		if ($newform != false) {
			my_load_view($this->setting->theme, 'Translation/newform', $data);
			return;
		}
		$data['translations'] = $this->translation_model->get_list();
		my_load_view($this->setting->theme, 'Translation/start', $data);
	}

	public function start_action() {
		$translationArray = [];
		$translationArray['source_lang'] = $this->input->post('original_lang');
		$translationArray['target_lang'] = $this->input->post('target_lang');
		$translationArray['contents'] = $this->input->post('script');
		$this->load->library('m_google_translate');
		echo $this->m_google_translate->get_translated_text($translationArray);
		exit;
	}

	public function final_action() {
		$translationArray = [];
		$translationArray['name'] = $this->input->post('name', FALSE);
		$translationArray['native_language'] = $this->input->post('original_lang');
		$translationArray['native_text'] = $this->input->post('script');
		$translationArray['translated_language'] = $this->input->post('target_lang');
		$translationArray['translated_text'] = $this->input->post('target_script');
		$this->translation_model->save_translation($translationArray);
	}

	public function del_action() {
		$id = $this->input->post('id');
		$this->translation_model->delete($id);
		echo 'DONE'; exit;
	}

	public function view($id) {
		if (is_numeric($id)) {
			$row = $this->translation_model->find_by_id($id);
			if ($row) {
				$divarea = <<<EOD
<div class="row">
<div class="col-4"><b>Name</b></div>
<div class="col-8">{$row->name}</div>
<div class="col-4"><b>Original Language</b></div>
<div class="col-8"><span class="langspan">{$row->native_language}</span></div>
<div class="col-4"><b>Original Text</b></div>
<div class="col-8">{$row->native_text}</div>
<div class="col-4"><b>Translated to</b></div>
<div class="col-8"><span class="langspan">{$row->translated_language}</span></div>
<div class="col-4"><b>Translated text</b></div>
<div class="col-8">{$row->translated_text}</div>
</div>
EOD;
			echo $divarea;
			return;
			} else {
				show_404();
			}
		} else {
			throw new \Exception('Not Found', 404);
		}
	}

	public function delete() {

	}

}
