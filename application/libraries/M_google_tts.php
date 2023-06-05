<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH . 'vendor/autoload.php');

use Google\Cloud\TextToSpeech\V1\AudioConfig;
use Google\Cloud\TextToSpeech\V1\AudioEncoding;
use Google\Cloud\TextToSpeech\V1\SynthesisInput;
use Google\Cloud\TextToSpeech\V1\TextToSpeechClient;
use Google\Cloud\TextToSpeech\V1\VoiceSelectionParams;
$CI = &get_instance();

class M_google_tts {


	public function init() {
		global $CI;
		$google_config = json_decode($CI->tts_config->google, TRUE);
		try {
			$client = new TextToSpeechClient(['credentials' => json_decode(file_get_contents($google_config['config_file']), true)]);
		}
		catch (Exception $e) {
			log_message('error', $e->getMessage());
			$client = FALSE;
		}
		return $client;
	}



	public function syncResource() {   //get voice list from google, then insert into table 'tts_resource'
		global $CI;
		$client = $this->init();
		if ($client) {
			try {
				$voices = $client->listVoices()->getVoices();
				$tts_config_array = json_decode($CI->tts_config->pricing_model, TRUE);
				foreach ($voices as $voice) {
					$voice_id = $voice->getName();
					(substr($voice_id, 0, 6) == 'sr-rs-') ? $voice_id = str_replace('sr-rs-', 'sr-RS-', $voice_id) : null; //correct an inconsistent wording of Google
					(my_check_str_contains($voice_id, 'Standard')) ? $engine = 'standard' : $engine = 'neural';
					($voice->getSsmlGender() == 1) ? $gender = 'Male' : $gender = 'Female';
					foreach ($voice->getLanguageCodes() as $language_code) {
						$voice_id = str_replace('Wavenet', '{{engine}}' ,str_replace('Standard', '{{engine}}', $voice_id));
						$query = $CI->db->where('scheme', 'google')->where('language_code', $language_code)->where('voice_id', $voice_id)->get('tts_resource', 1);
						if (!$query->num_rows()) {
							$insert_data = array(
							  'ids' => my_random(),
							  'scheme' => 'google',
							  'language_name' => my_tts_language_code_to_name($language_code),
							  'language_code' => $language_code,
							  'voice_id' => $voice_id,
							  'engine' => $engine,
							  'gender' => $gender,
							  'name' => str_replace($language_code . '-{{engine}}-', '', $voice_id),
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
							if (($rs->engine == 'standard' && $engine == 'neural') || ($rs->engine == 'neural' && $engine == 'standard')) {
								$update_data = array(
								  'engine' => 'standard,neural'
								);
								$CI->db->where('id', $rs->id)->update('tts_resource', $update_data);
							}
						}
					}
				}
				$result = TRUE;
			}
			catch (Exception $e) {
				log_message('error', $e->getMessage());
				$result = FALSE;
			}
		}
		else {
			$result = FALSE;
		}
		return $result;
	}



	public function synthesize($config) {
		global $CI;
		$client = $this->init();
		if ($client) {
			$input = new SynthesisInput();
			$voice = new VoiceSelectionParams();
			$audioConfig = new AudioConfig();
			if ($config['text_type'] == 'text') {
				$input->setText($config['text']);
			}
			else {
				$input->setSsml($config['text']);
			}
			$voice->setLanguageCode($config['language_code']);
			($config['engine'] == 'standard') ? $voice_id = str_replace('{{engine}}', 'Standard', $config['voice_id']) : $voice_id = $voice_id = str_replace('{{engine}}', 'Wavenet', $config['voice_id']);
			$voice->setName($voice_id);
			$audioConfig->setAudioEncoding(AudioEncoding::LINEAR16);
/*			$audioConfig->setSampleRateHertz(64000); */
			try {
				$audioData = $client->synthesizeSpeech($input, $voice, $audioConfig);
				$tts_uri = $config['file_path'] . $config['ids'] . '.' . $config['output_format'];
				file_put_contents(FCPATH . $tts_uri, $audioData->getAudioContent()); //no matter where to save in next step, currently need to save to local first, if it fails to save to remote server, then use this local file
				$res = array('result'=>TRUE, 'message'=>'', 'tts_uri'=>$tts_uri);
			}
			catch (Exception $e) {
				log_message('error', $e->getMessage());
				$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
			}
		}
		else {
			$res = array('result'=>FALSE, 'message'=>'TTS Client could not connect. Please try again.', 'tts_uri'=>'');
		}
		return $res;
	}
}
?>
