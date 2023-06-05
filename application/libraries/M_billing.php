<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$CI = &get_instance();

class M_billing {

 
	public function billing($config) {
		global $CI;
		if (my_check_permission('TTS Management')) {  // TTS admin is alway free to use
			$result_array = array('result'=>TRUE);
		}
		else {
			// checking steps:
			// 1. Is it free?
			// 2. If not, Is it one-time package or subscription? If yes, Is the user eligible for it?
			// 3. If not, Is Pay-As-You-Go? If yes, Does the user have charge the a package or have enough balance?
			// If all above are not suitable, refuse to access
			$query = $CI->db->where('ids', $config['voice_ids'])->where('enabled', 1)->get('tts_resource', 1);
			if ($query->num_rows()) {
				$rs = $query->row();
				($config['engine'] == 'standard') ? $accessibility = $rs->accessibility_standard : $accessibility = $rs->accessibility_neural;
				$checking_processing = TRUE;
				
				
				// Is it free?
				if (my_check_str_contains($accessibility, 'free')) {
					$result_array = array('result'=>TRUE); //all set, success
					$checking_processing = FALSE;
				}
				
				// If not, Is it one-time package or subscription? If yes, Is the user eligible for it?
				if ($checking_processing) { // not free, need further checking
					if ($accessibility != 'payg') {  // it's not payg only
						$accessibility_array = explode(',', $accessibility);
						foreach ($accessibility_array as $package_ids) {
							if ($package_ids != 'payg') {
								if ($this->deduct_package_balance($config, $package_ids)) {
									$result_array = array('result'=>TRUE);
									$checking_processing = FALSE;
									break;
								}
							}
						}
					}
				}
				
				// Here is the last step, If not, Is Pay-As-You-Go? If yes, Does the user have charge the a package or have enough balance?
				if ($checking_processing) {  // not eligibile for purchase or subscription, check Pay-As-You-Go

					if (my_check_str_contains($accessibility, 'payg')) {  //pay as you go is available for this voice
						$payg_billing_result = $this->payg_billing($config);
						if ($payg_billing_result['result']) {
							$result_array = array('result'=>TRUE); //all set, success
						}
						else {
							$result_array = $payg_billing_result;
						}
					}
					else {
						$result_array = array('result'=>FALSE, 'message'=>my_caption('tts_billing_notice_not_eligible'));
					}
				}
				
			}
			else {
				$result_array = array('result'=>FALSE, 'message'=>my_caption('tts_billing_notice_unavailable_resource') . $config['voice_ids']);
			}
		}
		return $result_array;
	}
	
	
	
	protected function payg_billing($config) {
		global $CI;
		$result = FALSE;
		$query_statitics = $CI->db->where('user_ids', $_SESSION['user_ids'])->get('tts_statitics', 1);
		if ($query_statitics->num_rows()) {
			$deduct_payg_balance_result = $this->deduct_payg_balance($config);
			if ($deduct_payg_balance_result) {
				$result_array = array('result'=>TRUE);
				$result = TRUE;
			}
		}
		if (!$result) { //fail to deduct balance (because balance is not engough or it's a new user)
			if (!$query_statitics->num_rows()) { my_tts_generate_user_statitics(); }  //new user, generate basic statitics record
			$buy_payg_package_result_array = $this->buy_payg_package($config);
			if ($buy_payg_package_result_array['result']) {  //succeed to buy a payg package
				$this->deduct_payg_balance($config);  //deduct again after purchase, do succeed(no failure) at this step
				$result_array = array('result'=>TRUE);
			}
			else {  //fail to buy package
				$result_array = $buy_payg_package_result_array;
			}
		}
		return $result_array;
	}
	
	
	
	protected function deduct_payg_balance($config) {
		global $CI;
		$rs_statitics = $CI->db->where('user_ids', $_SESSION['user_ids'])->get('tts_statitics', 1)->row();
		$balance_in_statitics = $rs_statitics->payg_balance;
		($config['engine'] == 'standard') ? $balance_in_statitics = $balance_in_statitics - $config['characters_count'] : $balance_in_statitics = $balance_in_statitics - 4*$config['characters_count'];
		if ($balance_in_statitics >= 0) {  //remain amount of payg package is enough
		    if ($config['synthesize_type'] == 'save') {
				$CI->db->where('user_ids', $_SESSION['user_ids'])->update('tts_statitics', array('payg_balance'=>$balance_in_statitics));
			}
			$result = TRUE;
		}
		else {
			$result = FALSE;
		}
		return $result;  //just a value, no need to return as a array
	}
	
	
	
	protected function deduct_package_balance($config, $package_ids) {
		global $CI;
		$package_type = my_get_payment_item($package_ids, 'type');
		if (!$package_type) {
			$result = FALSE;
		}
		else {
			if ($package_type == 'purchase') {  //type of purchase
				$tbl = 'payment_purchased';
				$query = $CI->db->where('user_ids', $_SESSION['user_ids'])->where('used_up', 0)->where('item_ids', $package_ids)->get('payment_purchased', 1);
			}
			elseif ($package_type == 'subscription') {  //type of subscription
				$tbl = 'payment_subscription';
				$query = $CI->db->where('user_ids', $_SESSION['user_ids'])->where('used_up', 0)->where('item_ids', $package_ids)->get('payment_subscription', 1);
			}
			if ($query->num_rows()) {
				$rs = $query->row();
				$stuff_array = json_decode($rs->stuff, TRUE);
				if ($stuff_array['characters_limit'] != '' && $config['synthesize_type'] == 'save') {  //has limit for the package, need to deduct
					($config['engine'] == 'standard') ? $characters_used = $stuff_array['characters_used'] + $config['characters_count'] : $characters_used = $stuff_array['characters_used'] + $config['characters_count']; //4*$config['characters_count']
					($characters_used >= $stuff_array['characters_limit']) ? $used_up = 1 : $used_up = 0;
					$stuff_array['characters_used'] = $characters_used;
					$CI->db->where('id', $rs->id)->update($tbl, array('stuff'=>json_encode($stuff_array), 'used_up'=>$used_up));
					$result = TRUE;
				}
				else {  //no limit or just preview, checking pass
					$result = TRUE;
				}
			}
			else {
				$result = FALSE;
			}
		}
		return $result;
	}
	
	
	
	protected function buy_payg_package($config) {
		global $CI;
		$pricing_model_array = json_decode($CI->tts_config->pricing_model, TRUE);
		$currency = strtolower($pricing_model_array['currency']);
		$price = $pricing_model_array['price'];
		$characters = $pricing_model_array['characters'];
		$buy_result_json = my_user_reload($_SESSION['user_ids'], 'Cut', $currency, $price);
		$buy_result_array = json_decode($buy_result_json, TRUE);
		if ($buy_result_array['result']) {  //succeed to buy
			$insert_purchased_data = array(
			  'ids' => my_random(),
			  'user_ids' => $_SESSION['user_ids'],
			  'payment_ids' => str_repeat('0', 50),
			  'item_type' => 'consumption',
			  'item_ids' => str_repeat('0', 50),
			  'item_name' => $config['engine'] . ' voice package * 1',
			  'created_time' => my_server_time(),
			  'description' => $currency . $price
			);
			$CI->db->insert('payment_purchased', $insert_purchased_data);
		    $rs_statitics = $CI->db->where('user_ids', $_SESSION['user_ids'])->get('tts_statitics', 1)->row();
			$characters_balance = $rs_statitics->payg_balance + $characters;
			$characters_purchased = $rs_statitics->payg_purchased + $characters;
			$CI->db->where('id', $rs_statitics->id)->update('tts_statitics', array('payg_balance'=>$characters_balance, 'payg_purchased'=>$characters_purchased));
			$result_array = array('result'=>TRUE);
		}
		else {
			$result_array = array('result'=>FALSE, 'message'=>my_caption('tts_billing_notice_buy_package_fail'));
		}
		return $result_array;
	}
	
	
}
?>