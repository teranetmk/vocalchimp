<?php
defined('BASEPATH') OR exit('No direct script access allowed');


if (!function_exists('my_tts_language_code_to_name')) {
	function my_tts_language_code_to_name($code) {
		$CI = &get_instance();
		$field = 'tts_language_name_' . $code;
		$language_name = $CI->lang->line($field);
		($language_name == '') ? $language_name = $CI->lang->line('tts_language_name_unknown') : null;
		return $language_name;
	}
}



if (!function_exists('my_tts_language_list')) {
	function my_tts_language_list($scheme) {
		$CI = &get_instance();
		$CI->db->distinct('language_name');
		if ($scheme != 'all') {
			$CI->db->where('scheme', $scheme);
		}
		$query = $CI->db->where('enabled', 1)->like('engine', 'neural')->order_by('language_name', 'asc')->get('tts_resource');
		if ($query->num_rows()) {
			if (!$CI->tts_config->default_language) {
				$result[0] = my_caption('tts_language_select_notice');
			}
			$rs = $query->result();
			foreach($rs as $row) {
				$result[$row->language_code] = $row->language_name;
			}
		}
		else {
			$result[0] = my_caption('tts_language_no_available');
		}
		return $result;
	}
}



if (!function_exists('my_tts_check_ssml')) {  //check whether the text is a ssml text, if yes, return the escaped one
	function my_tts_check_ssml($text, $synthesize_type = 'preview') {
		$text = trim($text);
		if (preg_match("/<[^<]+>/", $text)) {  // contains tag '<>'
			preg_match_all('/\<(.*?)\>/', $text, $matches);
			$i = 0;
			$ssml_array = array();
			foreach ($matches[1] as $match) {
				(substr($match, 0, 5) == 'break' || substr($match, 0, 4) == 'mark') ? $ssml_tag_array[$i] = '<' . $match . '/>' : $ssml_tag_array[$i] = '<' . $match . '>';
				$match = '<' . $match . '>';
				$text = str_replace($match, '[[[' . $i . ']]]', $text);
				$i++;
			}
			$text = my_tts_get_preview_text($text, $synthesize_type);
			$text = str_replace('"', '&quot;', $text);
			$text = str_replace('&', '&amp;', $text);
			$text = str_replace('\'', '&apos;', $text);
			$text = str_replace('<', '&lt;', $text);
			$text = str_replace('>', '&gt;', $text);			
			$i = 0;
			foreach ($ssml_tag_array as $ssml_tag) {
				$text = str_replace('[[[' . $i . ']]]', $ssml_tag, $text);
				$i++;
			}
			$result = array('type'=>'ssml', 'text'=>$text);
		}
		else {
			$text = my_tts_get_preview_text($text, $synthesize_type);
			$result = array('type'=>'text', 'text'=>$text);
		}
		return $result;
	}
}



if (!function_exists('my_tts_get_preview_text')) {
	function my_tts_get_preview_text($text, $synthesize_type) {
		$CI = &get_instance();
		if ($synthesize_type == 'preview' || $CI->config->item('my_demo_mode')) {  //preview mode or demo mode
		    ($synthesize_type == 'preview') ? $characters = $CI->tts_config->maximum_character_preview : $characters = 200;  // preview mode->50, demo mode->200
			if (my_tts_check_ascii($text)) {
				preg_match("/(?:\w+(?:\W+|$)){0," . $characters . "}/", $text, $matches);
				$text = $matches[0];
			}
			else {
				$text = mb_substr($text, 0, $characters, 'utf-8');
			}
		}
		return $text;
	}
}



if (!function_exists('my_tts_check_ascii')) {
	function my_tts_check_ascii($text) {
		$str_array = str_split($text);
		$result = TRUE;
		foreach ($str_array as $str) {
			if (!mb_detect_encoding($str, 'ASCII')) {
				$result = FALSE;
				break;
			}
		}
		return $result;
	}
}



if (!function_exists('my_tts_generate_user_statitics')) {
	function my_tts_generate_user_statitics($user_ids = '') {
		$CI = &get_instance();
		($user_ids == '') ? $user_ids = $_SESSION['user_ids'] : null;
		$query = $CI->db->where('user_ids', $user_ids)->get('tts_statitics', 1);
		if (!$query->num_rows()) {
			$insert_array = array(
			  'user_ids' => $user_ids,
			  'payg_balance' => 0,
			  'payg_purchased' => 0,
			  'characters_preview_used' => 0,
			  'characters_production_used' => 0,
			  'voice_generated' => 0
			);
			$CI->db->insert('tts_statitics', $insert_array);
			$rs = $CI->db->where('user_ids', $user_ids)->get('tts_statitics', 1)->row();
		}
		else {
			$rs = $query->row();
		}
		return $rs;
	}
}



if (!function_exists('my_tts_get_package_balance')) {
	function my_tts_get_package_balance($user_ids = '') {
		$CI = &get_instance();
		($user_ids == '') ? $user_ids = $_SESSION['user_ids'] : null;
		$amount_purchase = 0;
		$amount_subscription = 0;
		$standard_unlimited = FALSE;
		$neural_unlimited = FALSE;
		$query_purchase = $CI->db->where('item_type', 'purchase')->where('user_ids', $user_ids)->where('used_up', 0)->get('payment_purchased');
		if ($query_purchase->num_rows()) {
			$rs_purchase = $query_purchase->result();
			foreach ($rs_purchase as $purchase) {
				$stuff_array = json_decode($purchase->stuff, TRUE);
				($stuff_array['characters_limit'] != '') ? $amount_purchase += $stuff_array['characters_limit'] - $stuff_array['characters_used'] : $standard_unlimited = TRUE;
			}
		}
		$query_subscription = $CI->db->where('user_ids', $user_ids)->where('used_up', 0)->where('status!=', 'expired')->where('end_time>=', my_server_time('UTC', 'Y-m-d'))->get('payment_subscription');
		if ($query_subscription->num_rows()) {
			$rs_subscription = $query_subscription->result();
			foreach ($rs_subscription as $subscription) {
				$stuff_array = json_decode($subscription->stuff, TRUE);
				($stuff_array['characters_limit'] != '') ? $amount_subscription += $stuff_array['characters_limit'] - $stuff_array['characters_used'] : $neural_unlimited = TRUE;
			}
		}
		$balance_array['amount'] = $amount_purchase + $amount_subscription;
		
		($standard_unlimited) ? $standard_pkg = '; ' . my_caption('tts_character_unlimited_standard') : $standard_pkg = '';
		($neural_unlimited) ? $neural_pkg = '; ' . my_caption('tts_character_unlimited_neural') : $neural_pkg = '';
		$balance_array['package'] = $standard_pkg . $neural_pkg;
		
		return $balance_array;
	}
}


if (!function_exists('get_subscription_info')) {
	function get_subscription_info($user_ids = '') {
		$CI = &get_instance();
		($user_ids == '') ? $user_ids = $_SESSION['user_ids'] : null;

		$query_subscription = $CI->db->where('user_ids', $user_ids)->where('status!=', 'expired')->where('end_time>=', my_server_time('UTC', 'Y-m-d'))->order_by('id','desc')->limit(1)->get('payment_subscription');
		if ($query_subscription->num_rows()) {
			$rs_subscription = $query_subscription->row();
			$stuff_array = json_decode($rs_subscription->stuff, TRUE);
			if ($rs_subscription->used_up == 1) {
				$stuff_array['used_up'] = true;
			}
		} else {
			return false;
		}
		return $stuff_array;
	}
}


if (!function_exists('incomplete_check')) {
	function incomplete_check($user_ids = '') {
		$CI = &get_instance();
		($user_ids == '') ? $user_ids = $_SESSION['user_ids'] : null;
		if($user_ids!='')
		{
			$query_subscription = $CI->db->where('user_ids', $user_ids)->where('end_time>=', my_server_time('UTC', 'Y-m-d'))->where('status=', 'incomplete')->order_by('id','desc')->limit(1)->get('payment_subscription');
			if ($query_subscription->num_rows()) {
				$rs_subscription = $query_subscription->row();
					return true;
			}else{
				return false;
			}
		}
	}
}

?>