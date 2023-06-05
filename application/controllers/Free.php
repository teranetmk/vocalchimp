<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Free extends CI_Controller {

	public $tts_config;
	public $setting;

	//private $username 	= 'vocalchimp2'; // mailchimp
    //private $password 	= 'c466a425c3597cd0b87901b2a65244b1-us6'; // mailchimp
    private $mcurl 		= 'https://us6.api.mailchimp.com/3.0/';
    private $audienceID = '2d069deaaa';
    private $apiKey 	= 'c466a425c3597cd0b87901b2a65244b1-us6';
	public $test_credits;

    public function __construct() {
		parent::__construct();
		$this->load->helper('my_tts');
		$this->load->model('tts_model');
		$query = $this->db->get('tts_configuration', 1);
		$this->tts_config = $query->row();
		//echo "<pre>";print_r($this->tts_config);die;
		$this->setting = my_global_setting('all_fields');
		$this->test_credits = 1000;
	}
	

    private function _addMemberToTag($segmentId, $email) {
        $url = 'lists/'.$this->audienceID.'/members';
        $payload = json_encode(['email_address' => $email, 'status' => 'subscribed', 'merge_fields' => []], JSON_FORCE_OBJECT);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->mcurl.$url);
        //echo $this->mcurl.$url;
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        //curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_USERPWD, "user:{$this->apiKey}");
        $response = curl_exec($ch);
        unset($ch);
        $url2 = 'lists/'.$this->audienceID.'/segments/'.$segmentId.'/members';
        $payload = json_encode(['email_address' => $email]);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->mcurl.$url2);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        //curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_USERPWD, "user:{$this->apiKey}");
        $response = curl_exec($ch);
        unset($ch);
        return;
    }

    public function mailchimpTesting()
    {
    	//$email = 'xyz@mailinator.com';
    	//$this->_mcprocess('Free Trial', $email);die;

    	//delete member from mailchimp
    	/*$mcurl = 'https://us6.api.mailchimp.com/3.0/';
    	$url = $mcurl.'lists/'.$this->audienceID.'/members/'.md5($email);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		curl_setopt($ch, CURLOPT_USERPWD, "user:{$this->apiKey}");
		$response = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		print_r($response);*/
		
        echo 'test';
    }

    private function _mcprocess($segment, $email) {
    	$url = 'lists/'.$this->audienceID.'/segments';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->mcurl.$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_USERPWD, "user:{$this->apiKey}");
        $response = curl_exec($ch);
        unset($ch);
        $repmc = json_decode($response);
        foreach($repmc->segments as $seg) {
            if($segment == $seg->name) {
                $this->_addMemberToTag($seg->id, $email);
                return true;
            }
        }
        $payload = json_encode(['name' => $segment, 'static_segment' => []], JSON_FORCE_OBJECT);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->mcurl.$url);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //timeout after 30 seconds
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        //curl_setopt($ch, CURLOPT_USERPWD, "{$this->username}:{$this->password}");
        curl_setopt($ch, CURLOPT_USERPWD, "user:{$this->apiKey}");
        $response = curl_exec($ch);
        unset($ch);
        $repmc = json_decode($response);
        $this->_addMemberToTag($repmc->id, $email);
        return true;
	}
	
	
	
	
	
	public function index() {
		redirect(base_url('/free/start'));
	}

	public function get120() {
		my_load_view('default', 'Free/get120');
	}

	public function get120_action() {
		$email = my_post('email');
		$this->_mcprocess('Free Trial', $email);
		$this->load->helper('cookie');
		set_cookie('vd_a', '1', 259200); // This will go under MailChimp
		set_cookie('vd_u', base64_encode('1000') . 'FRCcehmrn', 259200);
		set_cookie('vd_email', base64_encode($email) . 'FRZcehmrn', 259200);
		redirect(base_url('/free/start'));
	}
	
	public function start() {

		$this->load->helper('cookie');
		if (get_cookie('vd_a')) {
			$data = [];
			$data['credits'] = base64_decode(str_replace('FRCcehmrn', '', get_cookie('vd_u')));
			$data['email'] = get_cookie('email');
			if ($data['credits'] < 0) {
				$this->session->set_flashdata('flash_red', '<b>Your Free trial has expired.</b> Please choose a plan below to continue using VocalChimp!');
				redirect(base_url('home/pricing'));
			}
		} else {
			$data['credits'] = $this->test_credits;
			$data['load120'] = true;
		}
		my_load_view('default', 'Free/start', $data);
	}

	public function start_action() {
		$this->load->helper('cookie');
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
				$words = substr_count($tts_text, ' ') + 1;

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
				$this->load->library('m_tts');
				$res = $this->m_tts->synthesis($config);
				if ($res['result']) {  //synthesis successfully
					$config['tts_uri'] = $res['tts_uri']; //file path
					($config['synthesize_type'] == 'preview') ? $res['tts_uri'] = base_url($res['tts_uri']) : null;  //for play preview only
					$this->tts_model->save_tts($config);  //save to db
				}
				$res['word_count'] = $words;
				$wordcount = base64_decode(str_replace('FRCcehmrn', '', get_cookie('vd_u')));
				// $res['word_count'] = $wordcount-$words;

				// calculate by  characters
				$res['word_count'] = $wordcount - strlen(str_replace(' ', '', $tts_text));
				set_cookie('vd_u', base64_encode($res['word_count']) . 'FRCcehmrn', 604800);
				$res = json_encode($res);
			}
		}
		echo my_esc_html($res);
	}




	protected function tts_basic_check($text) {
		if (trim($text) == '') {
			$res_array = array('result'=>FALSE, 'message'=>my_caption('tts_text_required'));
		}
		else {
			$res_array = array('result'=>TRUE);
		}
		if ($res_array['result'] == TRUE && strlen(trim(preg_replace('/\s+/', ' ', $text))) > $this->test_credits) {
			$res_array = array('result'=>FALSE, 'message'=>my_caption('tts_notice_character_limit') . $this->test_credits);
		}
		return $res_array;		
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
		$text_array['text_length'] = mb_strlen($text_array['text_text'], 'utf-8');  //all handle with utf-8
		return $text_array;
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



	public function translate_start($newform = false) {
		$this->load->library('m_google_translate');
		$data = [];
		$data['supported_languages'] = $this->m_google_translate->get_supported_languages();
		if ($newform != false) {
			my_load_view($this->setting->theme, 'Translation/newform', $data);
			return;
		}
		$data['translations'] = $this->translation_model->get_list();
		$data['free'] = true;
		my_load_view($this->setting->theme, 'Translation/start', $data);
	}

	public function translate_start_action() {
		$translationArray = [];
		$translationArray['source_lang'] = $this->input->post('original_lang');
		$translationArray['target_lang'] = $this->input->post('target_lang');
		$translationArray['contents'] = $this->input->post('script');
		$this->load->library('m_google_translate');
		echo $this->m_google_translate->get_translated_text($translationArray);
		exit;
	}

}
