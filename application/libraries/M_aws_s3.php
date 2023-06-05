<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(FCPATH . 'vendor/autoload.php');

use Aws\S3\S3Client;  
use Aws\Exception\AwsException;
use Aws\Credentials\CredentialProvider;

$CI = &get_instance();

class M_aws_s3 {


	public function init($aws_config) {
		global $CI;
		$aws_config_array = json_decode($aws_config, TRUE);
		$profile = 'default';
		$path = $aws_config_array['config_file'];
		$provider = CredentialProvider::ini($profile, $path);
		$provider = CredentialProvider::memoize($provider);
		try {
			$client = new Aws\S3\S3Client([
			  'credentials' => $provider,
			  'version' => 'latest',
			  'region'  => $aws_config_array['region']
			]);
		}
		catch (AwsException $e) {
			log_message('error', $e->getMessage());
			$client = FALSE;
		}
		return $client;
	}


	
	public function putObject($config) {
		global $CI;
		$aws_config = $CI->db->get('tts_configuration')->row()->aws;
		$aws_config_array = json_decode($aws_config, TRUE);
		$client = $this->init($aws_config);
		if ($client) {
			try {
				($aws_config_array['folder'] != '') ? $full_key = $aws_config_array['folder'] . '/' . $config['ids'] . '.' . $config['output_format'] : $full_key = $config['ids'] . '.' . $config['output_format'];
				$res = $client->putObject([
				  'Bucket' => $aws_config_array['bucket'],
				  'Key' => $full_key,
				  'SourceFile' => FCPATH . $config['file_path'] . $config['ids'] . '.' . $config['output_format'],
				  'ACL' => 'public-read'
				]);
				if ($res['@metadata']['statusCode'] == 200) {
					$result = array('result'=>TRUE, 'message'=>'', 'object_uri'=>$res['ObjectURL']);
				}
				else {
					$result = array('result'=>FALSE, 'message'=>'', 'object_uri'=>'');
				}
			}
			catch (S3Exception $e) {
				log_message('error', $e->getMessage());
				$result = array('result'=>FALSE, 'message'=>'', 'object_uri'=>'');
			}
		}
		else {
			$result = array('result'=>FALSE, 'message'=>'', 'object_uri'=>'');
		}
		return $result;
	}
	
	
	
	public function deleteObject($obj_array) {
		global $CI;
		$aws_config = $CI->db->get('tts_configuration')->row()->aws;
		$client = $this->init($aws_config);
		if ($client) {
			foreach ($obj_array as $obj) {
				try {
					$result = $client->deleteObject(array('Bucket'=>$obj[0], 'Key'=>$obj[1]));
					$result = TRUE;
				}
				catch (S3Exception $e) {
					log_message('error', $e->getMessage());
					$result = FALSE;
				}
			}
		}
		else {
			$result = FALSE;
		}
		return $result;
	}
	
	
}
?>