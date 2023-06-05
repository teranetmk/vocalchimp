<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_esc_html($card_title)?></h6>
        </div>
        <div class="card-body">
		  <?php
			if ($rs->email_address_pending != '') {
				$pending_information = '<small class="text-danger text-mute">' . $rs->email_address_pending . my_caption('mp_pending_activation_notice') . '</small>';
			}
			else {
				$pending_information = '';
			}
		  ?>
		  <input type="hidden" id="base_url" name="base_url" value="<?=base_url()?>">
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('global_email_address')?></label>
			  <?php
			    echo my_esc_html($pending_information);
			    (!empty(set_value('email_address'))) ? $email_address = set_value('email_address') : $email_address = $rs->email_address;
				($this->config->item('my_demo_mode')) ? $email_address = '*****' . '' . substr($email_address, 5) : null;
			    $data = array(
				  'name' => 'email_address',
				  'id' => 'email_address',
				  'value' => $email_address,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('email_address', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('global_username')?></label>
			  <?php
			    (!empty(set_value('username'))) ? $username = set_value('username') : $username = $rs->username;
			    $data = array(
				  'name' => 'username',
				  'id' => 'username',
				  'value' => $username,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('username', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('mp_first_name_label')?></label>
			  <?php
			    (!empty(set_value('first_name'))) ? $first_name = set_value('first_name') : $first_name = $rs->first_name;
			    $data = array(
				  'name' => 'first_name',
				  'id' => 'first_name',
				  'value' => $first_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('first_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('mp_last_name_label')?></label>
			  <?php
			    (!empty(set_value('last_name'))) ? $last_name = set_value('last_name') : $last_name = $rs->last_name;
			    $data = array(
				  'name' => 'last_name',
				  'id' => 'last_name',
				  'value' => $last_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('last_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('mp_company_label')?></label>
			  <?php
			    (!empty(set_value('company'))) ? $company = set_value('company') : $company = $rs->company;
			    $data = array(
				  'name' => 'company',
				  'id' => 'company',
				  'value' => $company,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('company', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('mp_company_number_label')?></label>
			  <?php
			    (!empty(set_value('company_number'))) ? $company_number = set_value('company_number') : $company_number = $rs->company_number;
			    $data = array(
				  'name' => 'company_number',
				  'id' => 'company_number',
				  'value' => $company_number,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('company_number', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('mp_date_format_label')?></label>
			  <?php
			    (!empty(set_value('date_format'))) ? $date_format = set_value('date_format') : $date_format = $rs->date_format;
			    $options = array (
				  'Y-m-d' => 'Y-m-d',
				  'd-m-Y' => 'd-m-Y',
				  'd/m/Y' => 'd/m/Y',
				  'm-d-Y' => 'm-d-Y',
				  'm/d/Y' => 'm/d/Y'
				);
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('date_format', $options, $date_format, $data);
				echo form_error('date_format', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<div class="col-lg-6">
			  <label><?=my_caption('mp_timezone_label')?></label>
			  <?php
			    (!empty(set_value('timezone'))) ? $timezone = set_value('timezone') : $timezone = $rs->timezone;
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('timezone', my_timezone_list(), $timezone, $data);
				echo form_error('timezone', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
		    <div class="col-lg-7">
			  <?php
			  $gdpr_array = json_decode($this->setting->gdpr, TRUE);
			  if (!$_SESSION['is_admin'] && $gdpr_array['allow_remove']) { ?>
			    <button type="button" class="btn btn-danger mt-2" onclick="actionQuery('<?=my_caption('global_are_you_sure')?>', '<?=my_js_escape(my_caption('mp_remove_confirm'))?>', '', '<?=base_url('user/remove_self')?>')"><?=my_caption('mp_remove_account')?></button>
			    <a href="<?=base_url('user/gdpr_export')?>" class="btn btn-warning mt-2 ml-2"><?=my_caption('mp_export_my_data')?></a>
			  <?php } ?>
			</div>
			<div class="col-lg-5 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_submit_block',
				  'id' => 'btn_submit_block',
				  'value' => my_caption('global_save_changes'),
				  'class' => 'btn btn-primary mt-2'
			    );
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		</div>
      </div>