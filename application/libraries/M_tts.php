<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();
$CI->load->library('m_aws_polly');
$CI->load->library('m_google_tts');
$CI->load->library('m_azure_tts');

class M_tts {

 
	public function syncResource($provider) {
		global $CI;
		$result = TRUE;
		if ($provider == 'aws') {
			$result = $CI->m_aws_polly->syncResource();
		}
		if ($provider == 'google') {
			$result = $CI->m_google_tts->syncResource();
		}
		if ($provider == 'azure') {
			$result = $CI->m_azure_tts->syncResource();
		}
		return $result;
	}
	
	
	
	public function synthesis($config) {
		global $CI;
		if ($config['scheme'] == 'aws') {  //aws
			if ($config['storage'] == 'S3' && $config['synthesize_type'] == 'save') {
				$config['remote_saved'] = TRUE;
				$res = $CI->m_aws_polly->synthesizeS3($config);
			}
			else {
				$config['remote_saved'] = FALSE;
				$res = $CI->m_aws_polly->synthesizeLocal($config);
			}
		}
		elseif ($config['scheme'] == 'google') { //google
		    $config['remote_saved'] = FALSE;
			$res = $CI->m_google_tts->synthesize($config);
		}
		elseif ($config['scheme'] == 'azure') {  //azure
			$config['remote_saved'] = FALSE;
			$res = $CI->m_azure_tts->synthesize($config);
		}
		if ($res['result'] && $config['synthesize_type'] == 'save' && $config['storage'] != 'local' && $config['remote_saved'] == FALSE) {  //synthesis successfully
			if ($config['storage'] == 'S3') {   //save to aws S3, change the tts_uri after success, unnecessary to handle failure as once it's failed, the local file won't be removed.
				$CI->load->library('m_aws_s3');
				$res_storage = $CI->m_aws_s3->putObject($config);
			}
			elseif ($config['storage'] == 'wasabi') { //save to wasabi, unnecessary to handle failure
				$CI->load->library('m_wasabi');
				$res_storage = $CI->m_wasabi->putObject($config);
			}
			if ($res_storage['result']) {  //remove local file, if fail to upload to remote, keep local file
				$tts_uri = $config['file_path'] . $config['ids'] . '.' . $config['output_format'];
				try {unlink(FCPATH . $tts_uri);} catch(\Exception $e) {}  //remove file
				$res['tts_uri'] = $res_storage['object_uri'];
			}
		}
		return $res;
	}
	
	
	
	public function deleteObject($rs) {
		global $CI;
		$storage_array_local = array();
		$storage_array_s3 = array();
		$storage_array_wasabi = array();
		foreach($rs as $row) {
			$storage_config = explode('/', $row->storage);
			switch($storage_config[0]) {
				case 'local' :
				  array_push($storage_array_local, $row->tts_uri);
				  break;
				case 'S3' :
				  $url_array = array_reverse(explode('/', $row->tts_uri));
				  ($storage_config[3] != '') ? $full_key = $storage_config[3] . '/' . $url_array[0] : $full_key = $url_array[0];
				  array_push($storage_array_s3, array($storage_config[2], $full_key));
				  break;
				case 'wasabi' :
				  $url_array = array_reverse(explode('/', $row->tts_uri));
				  ($storage_config[3] != '') ? $full_key = $storage_config[3] . '/' . $url_array[0] : $full_key = $url_array[0];
				  array_push($storage_array_wasabi, array($storage_config[2], $full_key));
				  break;
			}
		}
		if (!empty($storage_array_local)) {
			foreach ($storage_array_local as $tts_uri) {
				unlink(FCPATH . $tts_uri);
			}
		}
		if (!empty($storage_array_s3)) {
			$CI->load->library('m_aws_s3');
			$result = $CI->m_aws_s3->deleteObject($storage_array_s3);
		}
		if (!empty($storage_array_wasabi)) {
			$CI->load->library('m_wasabi');
			$result = $CI->m_wasabi->deleteObject($storage_array_wasabi);
		}
		
		$result = TRUE;
		return $result;
	}
	
	
}
?>