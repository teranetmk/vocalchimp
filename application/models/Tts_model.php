<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tts_model extends CI_Model {



	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set($this->config->item('time_reference'));
	}
	
	
	
	public function save_tts($config) {
		$insert_array = array(
		  'ids' => $config['ids'],
		  'user_ids' => $_SESSION['user_ids'],
		  'scheme' => $config['scheme'],
		  'engine' => $config['engine'],
		  'language_code' => $config['language_code'],
		  'voice_id' => $config['voice_id'],
		  'text' => $this->security->xss_clean($config['text']),
		  'tts_uri' => $config['tts_uri'],
		  'created_time' => my_server_time()
		);
		if ($config['synthesize_type'] == 'preview') {	
			if($_SESSION['user_ids']!='')
			{
				$this->db->insert('tts_preview_log', $insert_array);
			}
		}
		else {
			switch ($config['storage']) {
				case 'S3' :
				  $aws_array = json_decode($this->tts_config->aws, TRUE);
				  $storage = 'S3/' . $aws_array['region'] . '/' . $aws_array['bucket'] . '/' . $aws_array['folder'];
				  break;
				case 'wasabi' :
				  $wasabi_array = json_decode($this->tts_config->wasabi, TRUE);
				  $storage = 'wasabi/' . $wasabi_array['region'] . '/' . $wasabi_array['bucket'] . '/' . $wasabi_array['folder'];
				  break;
				default :
				  $storage = $config['storage'];
			}
			$insert_array['campaign'] = 'default';
			$insert_array['language_name'] = $config['language_name'];
			$insert_array['voice_name'] = $config['voice_name'];
			$insert_array['config'] = $config['tts_config'];
			$insert_array['storage'] = $storage;
			$insert_array['characters_count'] = $config['characters_count'];
			$this->db->insert('tts_log', $insert_array);
		}
		return TRUE;
	}
	
	
	
	public function save_configuration() {
		$pricing_model_array = array(
		  'enabled' => 1,
		  'currency' => my_post('ttsc_payg_currency'),
		  'price' => my_post('ttsc_payg_price'),
		  'characters' => my_post('ttsc_payg_characters')
		);
		$aws_array = array(
		  'config_file' => my_post('ttsc_aws_config_file'),
		  'region' => my_post('ttsc_aws_region'),
		  'bucket' => my_post('ttsc_aws_bucket'),
		  'folder' => my_post('ttsc_aws_folder')
		);
		$google_array = array(
		  'config_file' => my_post('ttsc_google_config_file')
		);
		$azure_array = array(
		  'region' => my_post('ttsc_azure_region'),
		  'subscription_key' => my_post('ttsc_azure_key')
		);
		$wasabi_array = array(
		  'config_file' => my_post('ttsc_wasabi_config_file'),
		  'region' => my_post('ttsc_wasabi_region'),
		  'bucket' => my_post('ttsc_wasabi_bucket'),
		  'folder' => my_post('ttsc_wasabi_folder')
		);
		$update_array = array(
		  'default_language' => my_post('ttsc_default_language'),
		  'preview_delay' => my_post('ttsc_preview_delay'),
		  'storage' => my_post('ttsc_storage_solution'),
		  'maximum_character' => my_post('ttsc_maximum_characters'),
		  'maximum_character_preview' => my_post('ttsc_maximum_characters_preview'),
		  'clean_up' => my_post('ttsc_clean_up_setting'),
		  'ssml' => my_post('ttsc_ssml_support'),
		  'engine' => my_post('ttsc_engine'),
		  'pricing_model' => json_encode($pricing_model_array),
		  'aws' => json_encode($aws_array),
		  'google' => json_encode($google_array),
		  'azure' => json_encode($azure_array),
		  'wasabi' => json_encode($wasabi_array)
		);
		$this->db->update('tts_configuration', $update_array);
		return TRUE;
	}
	
	

}