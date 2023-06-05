<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH . 'vendor/autoload.php');

use Aws\Exception\AwsException;
use Aws\Polly\PollyClient;
use Aws\Credentials\CredentialProvider;

$CI = &get_instance();

class M_aws_polly {
	
	public function init() {
		global $CI;
		$aws_config_array = json_decode($CI->tts_config->aws, TRUE);
		$profile = 'default';
		$path = $aws_config_array['config_file'];
		$provider = CredentialProvider::ini($profile, $path);
		$provider = CredentialProvider::memoize($provider);
		try {
			$client = new Aws\Polly\PollyClient([
			  'credentials' => $provider,
			  'version' => 'latest',
			  'region' => $aws_config_array['region']
			]);
		}
		catch (AwsException $e) {
			log_message('error', $e->getMessage());
			$client = FALSE;
		}
		return $client;
	}
	
	
	
	public function syncResource() {  //get voice list from aws, then insert into table 'tts_resource'
		global $CI;
		$language_code_array = explode('|', 'arb|cmn-CN|cy-GB|da-DK|de-DE|en-AU|en-GB|en-GB-WLS|en-IN|en-US|es-ES|es-MX|es-US|fr-CA|fr-FR|is-IS|it-IT|ja-JP|hi-IN|ko-KR|nb-NO|nl-NL|pl-PL|pt-BR|pt-PT|ro-RO|ru-RU|sv-SE|tr-TR');
		$client = $this->init();
		if ($client) {
			$tts_config_array = json_decode($CI->tts_config->pricing_model, TRUE);
			foreach ($language_code_array as $language_code) {
				try {
					$res = $client->describeVoices([
					  'IncludeAdditionalLanguageCodes' => TRUE,
					  'LanguageCode' => $language_code
					]);
					if ($res['@metadata']['statusCode'] == 200) {
						foreach ($res['Voices'] as $voice) {
							$engines = '';
							foreach ($voice['SupportedEngines'] as $engine) {
								$engines .= $engine . ',';
							}
							$engines = rtrim($engines, ',');
							($language_code == 'arb') ? $language_code = 'ar-XA' : null;
							$query = $CI->db->where('scheme', 'aws')->where('language_code', $language_code)->where('voice_id', $voice['Id'])->get('tts_resource', 1);
							if (!$query->num_rows()) {
								//insert db
								$insert_data = array(
								  'ids' => my_random(),
								  'scheme' => 'aws',
								  'language_name' => my_tts_language_code_to_name($language_code),
								  'language_code' => $language_code,
								  'voice_id' => $voice['Id'],
								  'engine' => $engines,
								  'gender' => $voice['Gender'],
								  'name' => $voice['Name'],
								  'description' => '',
								  'enabled' => 1,
								  'stuff' => '',
								  'accessibility_standard' => 'payg',
								  'accessibility_neural' => 'payg'
								);
								$CI->db->insert('tts_resource', $insert_data);
							}
							else {  //check if engine type is included
								$rs = $query->row();
								if ($rs->language_name == 'Unknown') {  //try to update 'unknown' if necessary and possible
									$CI->db->where('id', $rs->id)->update('tts_resource', array('language_name'=>my_tts_language_code_to_name($language_code)));
								}
								if ($rs->engine != $engines) {
									$CI->db->where('id', $rs->id)->update('tts_resource', array('engine'=>$engines));
								}
							}
						}
					}
					$result = TRUE;
				}
				catch (AwsException $e) {  //exception when get voice
				    log_message('error', $e->getMessage());
					$result = FALSE;
					break;
				}
			}
		}
		else {  //unable to init
			$result = FALSE;
		}
		return $result;
	}
	
	
	
	public function synthesizeLocal($config) {  //save the file to local
		$client = $this->init();
		if ($client) {
			try {
				$language_code = $config['language_code'];
				($language_code == 'ar-XA') ? $language_code = 'arb' : null;
				$res = $client->synthesizeSpeech([
				  'Engine' => $config['engine'],
				  'LanguageCode' => $language_code,
				  'OutputFormat' => $config['output_format'],
				  'TextType' => $config['text_type'],
				  'Text' => $config['text'],
				  'VoiceId' => $config['voice_id']
				]);
				$audioData = $res->get('AudioStream')->getContents();
				$tts_uri = $config['file_path'] . $config['ids'] . '.' . $config['output_format'];
				file_put_contents(FCPATH . $tts_uri, $audioData);
				$res = array('result'=>TRUE, 'message'=>'', 'tts_uri'=>$tts_uri);
			}
			catch (AwsException $e) {  //exception when synthesize
			    log_message('error', $e->getMessage());
				$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
			}
		}
		else {  //unable to init
			$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
		}
		return $res;
	}
	
	
	
	public function synthesizeS3($config) {  //save the file to S3
		global $CI;
		$aws_config_array = json_decode($CI->tts_config->aws, TRUE);
		$client = $this->init();
		if ($client) {
			try {
				$language_code = $config['language_code'];
				($language_code == 'ar-XA') ? $language_code = 'arb' : null;
				($aws_config_array['folder'] != '') ? $key_prefix = $aws_config_array['folder'] . '/' : $key_prefix = '';
				$res = $client->startSpeechSynthesisTask([
				  'Engine' => $config['engine'],
				  'LanguageCode' => $language_code,
				  'OutputFormat' => $config['output_format'],
				  'TextType' => $config['text_type'],
				  'Text' => $config['text'],
				  'VoiceId' => $config['voice_id'],
				  'OutputS3BucketName' => $aws_config_array['bucket'],
				  'OutputS3KeyPrefix' => $key_prefix
				]);
				if ($res['@metadata']['statusCode']=='200') {
					$res = array('result'=>TRUE, 'message'=>'', 'tts_uri'=>$res['SynthesisTask']['OutputUri']);
				}
				else {
					$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_remote'), 'tts_uri'=>'');
				}
			}
			catch (AwsException $e) {  //exception when synthesize
			    log_message('error', $e->getMessage());
				$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
			}
		}
		else {  //unable to init
			$res = array('result'=>FALSE, 'message'=>my_caption('tts_notice_error_local'), 'tts_uri'=>'');
		}
		return $res;
	}

	
	
	
}
?>