<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();


class M_azure_tts {
	
	public function syncResource() {  //get voice list from azure, then insert into table 'tts_resource'
		global $CI;
		$azure_config_array = json_decode($CI->tts_config->azure, TRUE);
		$resource_url = 'https://' . $azure_config_array['region'] . '.tts.speech.microsoft.com/cognitiveservices/voices/list';
		$header = ['Ocp-Apim-Subscription-Key: ' . $azure_config_array['subscription_key']];
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $resource_url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		try {
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			if ($http_code == 200) {
				$voice_list_array = json_decode($response, TRUE);
				foreach ($voice_list_array as $voice) {
					$language_code = $voice['Locale'];
					$language_code == 'zh-HK' ? $language_code = 'yue-HK' : null;
					$language_code == 'zh-TW' ? $language_code = 'cmn-TW' : null;
					$language_code == 'zh-CN' ? $language_code = 'cmn-CN' : null;
					$gender = $voice['Gender'];
					($voice['ShortName'] == 'he-IL-HilaNeural') ? $gender = 'Female' : null; //there is an error about gender on Azure, correct it here
					$query = $CI->db->where('scheme', 'azure')->where('language_code', $language_code)->where('voice_id', $voice['ShortName'])->get('tts_resource', 1);
					if (!$query->num_rows()) {
						$insert_data = array(
						  'ids' => my_random(),
						  'scheme' => 'azure',
						  'language_name' => my_tts_language_code_to_name($language_code),
						  'language_code' => $language_code,
						  'voice_id' => $voice['ShortName'],
						  'engine' => strtolower($voice['VoiceType']),
						  'gender' => $gender,
						  'name' => $voice['LocalName'],
						  'description' => '',
						  'enabled' => 1,
						  'stuff' => '',
						  'accessibility_standard' => 'payg',
						  'accessibility_neural' => 'payg'
						);
						$CI->db->insert('tts_resource', $insert_data);
					}
					else {
						$rs = $query->row();
						if ($rs->language_name == 'Unknown') {  //try to update 'unknown' if necessary and possible
							$CI->db->where('id', $rs->id)->update('tts_resource', array('language_name'=>my_tts_language_code_to_name($language_code)));
						}
					}
				}
				$result = TRUE;
			}
			else {  // this is an exception of http code
				log_message('error', '(syncResource) http error code from azure: ' . $http_code);
				$result = FALSE;
			}
		}
		catch (Exception $e) {  // this is an exception of curl
			log_message('error', $e->getMessage());
			$result = FALSE;
		}
		return $result;
	}
	
	
	
	public function synthesize($config) {
		global $CI;
		$azure_config_array = json_decode($CI->tts_config->azure, TRUE);
		$resource_url = 'https://' . $azure_config_array['region'] . '.tts.speech.microsoft.com/cognitiveservices/v1';
		$header = [
		  'Ocp-Apim-Subscription-Key: ' . $azure_config_array['subscription_key'],
		  'Content-Type: application/ssml+xml',
		  'X-Microsoft-OutputFormat: audio-24khz-48kbitrate-mono-mp3',
		  'User-Agent: CyberBukit'
		];
		$language_code = $config['language_code'];
		$language_code == 'yue-HK' ? $language_code = 'zh-HK' : null;
		$language_code == 'cmn-TW' ? $language_code = 'zh-TW' : null;
		$language_code == 'cmn-CN' ? $language_code = 'zh-CN' : null;
		if ($config['ssml_mode']) {  //pure ssml mode
			$data = $config['text'];
		}
		else {
			$data = "<speak version='1.0' xml:lang='" . $language_code . "'><voice xml:lang='" . $language_code . "' xml:gender='" . $config['gender'] . "' name='" . $config['voice_id'] . "'>" . str_replace('&', '&amp;', $config['text']) . "</voice></speak>";
		}
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $resource_url);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $data); 
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		try {
			$response = curl_exec($curl);
			$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			curl_close($curl);
			if ($http_code == 200) {
				$tts_uri = $config['file_path'] . $config['ids'] . '.' . $config['output_format'];
				file_put_contents(FCPATH . $tts_uri, $response); //no matter where to save in next step, currently need to save to local first, if it fails to save to remote server, then use this local file
				$res = array('result'=>TRUE, 'message'=>'', 'tts_uri'=>$tts_uri);
			}
			else {  // this is an exception of http code
				log_message('error', '(synthesize) http error code from azure: ' . $http_code);
				$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
			}
		}
		catch (Exception $e) { // this is an exception of curl
		    log_message('error', $e->getMessage());
			$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
		}
		return $res;
	}
	
	
}
?>