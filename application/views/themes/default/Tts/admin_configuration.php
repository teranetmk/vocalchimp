<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('tts_configuration_title')?></h1>
	</div>
  </div>

  <div class="row">
    <div class="col-lg-9 col-md-12">
	  <?php
	    my_load_view($this->setting->theme, 'Generic/show_flash_card');
	  ?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('tts_configuration_title')?></h6>
        </div>
        <div class="card-body">
		  <?php
		    echo form_open(base_url('tts/admin_configuration_action/'), ['method'=>'POST']);
		  ?>
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_global_setting')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_default_language')?></label>
			  <?php
			    (set_value('ttsc_default_language') != '') ? $ttsc_default_language = set_value('ttsc_default_language') : $ttsc_default_language = $this->tts_config->default_language;
			    $data = array(
				  'id' => 'ttsc_default_language',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_default_language', my_tts_language_list('all'), $ttsc_default_language, $data);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_preview_delay')?></label>
			  <?php
			    (set_value('ttsc_preview_delay') != '') ? $ttsc_preview_delay = set_value('ttsc_preview_delay') : $ttsc_preview_delay = $this->tts_config->preview_delay;
			    $option_array = array(
				  '0' => 'No-Delay',
				  '1' => '1 Second',
				  '2' => '2 Seconds',
				  '3' => '3 Seconds',
				  '4' => '4 Seconds'
				);
				$data = array(
				  'id' => 'ttsc_preview_delay',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_preview_delay', $option_array, $ttsc_preview_delay, $data);
				echo form_error('ttsc_preview_delay', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_maximum_characters_preivew')?></label>
			  <?php
			    (set_value('ttsc_maximum_characters_preview') != '') ? $ttsc_maximum_characters_preview = set_value('ttsc_maximum_characters_preview') : $ttsc_maximum_characters_preview = $this->tts_config->maximum_character_preview;
			    $data = array(
				  'name' => 'ttsc_maximum_characters_preview',
				  'id' => 'ttsc_maximum_characters_preview',
				  'value' => $ttsc_maximum_characters_preview,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_maximum_characters_preview', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_maximum_characters')?></label>
			  <?php
			    (set_value('ttsc_maximum_characters') != '') ? $ttsc_maximum_characters = set_value('ttsc_maximum_characters') : $ttsc_maximum_characters = $this->tts_config->maximum_character;
			    $data = array(
				  'name' => 'ttsc_maximum_characters',
				  'id' => 'ttsc_maximum_characters',
				  'value' => $ttsc_maximum_characters,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_maximum_characters', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_storage_solution')?></label>
			  <?php
			    (set_value('ttsc_storage_solution') != '') ? $ttsc_storage_solution = set_value('ttsc_storage_solution') : $ttsc_storage_solution = $this->tts_config->storage;
			    $options = array(
				  'local' => 'Local Sever (highly recommended)',
				  'S3' => 'AWS S3 (not recommended)',
				  'wasabi' => 'Wasabi (for large scale uses only)'
				);
			    $data = array(
				  'id' => 'ttsc_storage_solution',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_storage_solution', $options, $ttsc_storage_solution, $data);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_clean_up_setting')?></label>
			  <?php
			    (set_value('ttsc_clean_up_setting') != '') ? $ttsc_clean_up_setting = set_value('ttsc_clean_up_setting') : $ttsc_clean_up_setting = $this->tts_config->clean_up;
			    $options = array(
				  '0' => 'Never',
				  '1' => 'Files older than 1 Days',
				  '3' => 'Files older than 7 Days',
				  '30' => 'Files older than 30 Days',
				  '90' => 'Files older than 90 Days',
				  '180' => 'Files older than 180 Days',
				  '365' => 'Files older than 365 Days',
				);
			    $data = array(
				  'id' => 'ttsc_clean_up_setting',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_clean_up_setting', $options, $ttsc_clean_up_setting, $data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_ssml_support')?></label>
			  <?php
			    (set_value('ttsc_ssml_support') != '') ? $ttsc_ssml_support = set_value('ttsc_ssml_support') : $ttsc_ssml_support = $this->tts_config->ssml;
			    $options = array(
				  '0' => 'Partially Support (highly recommended)',
				  '1' => 'Fully Support (not recommended)',
				);
			    $data = array(
				  'id' => 'ttsc_ssml_support',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_ssml_support', $options, $ttsc_ssml_support, $data);
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_enabled_engine')?></label>
			  <?php
			    (set_value('ttsc_engine') != '') ? $ttsc_engine = set_value('ttsc_engine') : $ttsc_engine = $this->tts_config->engine;
			    $options = array(
				  'both' => 'Both',
				  'standard' => 'Standard Voice Only',
				  'neural' => 'AI Voice Only'
				);
			    $data = array(
				  'id' => 'ttsc_engine',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_engine', $options, $ttsc_engine, $data);
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_payg')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_payg_standard')?></label>
			  <?php
			    $pricing_model_array = json_decode($this->tts_config->pricing_model, TRUE);
			    $currency_options = my_currency_list();
				(set_value('ttsc_payg_currency') != '') ? $ttsc_payg_currency = set_value('ttsc_payg_currency') : $ttsc_payg_currency = $pricing_model_array['currency'];
				$data = array(
				  'class' => 'form-control selectpicker'
				);
				echo form_dropdown('ttsc_payg_currency', $currency_options, $ttsc_payg_currency, $data);
			  ?>
			</div>
		    <div class="col-lg-3">
			  <label><?=my_caption('tts_configuration_payg_price')?></label>
			  <?php
			    (set_value('ttsc_payg_price') != '') ? $ttsc_payg_price = set_value('ttsc_payg_price') : $ttsc_payg_price = $pricing_model_array['price'];
			    $data = array(
				  'name' => 'ttsc_payg_price',
				  'id' => 'ttsc_payg_price',
				  'value' => $ttsc_payg_price,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_payg_price', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-3">
			  <label><?=my_caption('tts_configuration_payg_characters_included')?></label>
			  <?php
			    (set_value('ttsc_payg_characters') != '') ? $ttsc_payg_characters = set_value('ttsc_payg_characters') : $ttsc_payg_characters = $pricing_model_array['characters'];
			    $data = array(
				  'name' => 'ttsc_payg_characters',
				  'id' => 'ttsc_payg_characters',
				  'value' => $ttsc_payg_characters,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_payg_characters', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_aws')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_config_file_path')?> <a href="<?=base_url('admin/print_my_path')?>" target="_blank"><?=my_caption('tts_configuration_sp_config_file_path_tips')?></a></label>
			  <?php
			    $aws_array = json_decode($this->tts_config->aws, TRUE);
				if ($this->config->item('my_demo_mode')) {
					$ttsc_aws_config_file = '******';
				}
				else {
					(set_value('ttsc_aws_config_file') != '') ? $ttsc_aws_config_file = set_value('ttsc_aws_config_file') : $ttsc_aws_config_file = $aws_array['config_file'];
			    }
				$data = array(
				  'name' => 'ttsc_aws_config_file',
				  'id' => 'ttsc_aws_config_file',
				  'value' => $ttsc_aws_config_file,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_aws_config_file', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_region')?></label>
			  <?php
			    (set_value('ttsc_aws_region') != '') ? $ttsc_aws_region = set_value('ttsc_aws_region') : $ttsc_aws_region = $aws_array['region'];
			    $options = array(
				  'us-east-1' => 'US East(N. Virginia) us-east-1',
				  'us-east-2' => 'US East(Ohio) us-east-2',
				  'us-west-1' => 'US West(N. California) us-west-1',
				  'us-west-2' => 'US West(Oregon)us-west-2',
				  'af-south-1' => 'Africa(Cape Town) af-south-1',
				  'ap-east-1' => 'Asia Pacific(Hong Kong) ap-east-1',
				  'ap-south-1' => 'Asia Pacific(Mumbai) ap-south-1',
				  'ap-northeast-2' => 'Asia Pacific(Seoul) ap-northeast-2',
				  'ap-southeast-1' => 'Asia Pacific(Singapore) ap-southeast-1',
				  'ap-southeast-2' => 'Asia Pacific(Sydney) ap-southeast-2',
				  'ap-northeast-1' => 'Asia Pacific(Tokyo) ap-northeast-1',
				  'ca-central-1' => 'Canada(Central) ca-central-1',
				  'eu-central-1' => 'EU(Frankfurt) eu-central-1',
				  'eu-west-1' => 'EU(Ireland) eu-west-1',
				  'eu-west-2' => 'EU(London) eu-west-2',
				  'eu-south-1' => 'EU(Milan) eu-south-1',
				  'eu-west-3' => 'EU(Paris) eu-west-3',
				  'eu-north-1' => 'EU(Stockholm) eu-north-1',
				  'me-south-1' => 'Middle East(Bahrain) me-south-1',
				  'sa-east-1' => 'South America(Sao Paulo) sa-east-1'
				);
			    $data = array(
				  'id' => 'ttsc_aws_region',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_aws_region', $options, $ttsc_aws_region, $data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_bucket')?></label>
			  <?php
			    (set_value('ttsc_aws_bucket') != '') ? $ttsc_aws_bucket = set_value('ttsc_aws_bucket') : $ttsc_aws_bucket = $aws_array['bucket'];
			    $data = array(
				  'name' => 'ttsc_aws_bucket',
				  'id' => 'ttsc_aws_bucket',
				  'value' => $ttsc_aws_bucket,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_aws_bucket', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_folder')?></label>
			  <?php
			    (set_value('ttsc_aws_folder') != '') ? $ttsc_aws_folder = set_value('ttsc_aws_folder') : $ttsc_aws_folder = $aws_array['folder'];
			    $data = array(
				  'name' => 'ttsc_aws_folder',
				  'id' => 'ttsc_aws_folder',
				  'value' => $ttsc_aws_folder,
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_google')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_config_file_path')?> <a href="<?=base_url('admin/print_my_path')?>" target="_blank"><?=my_caption('tts_configuration_sp_config_file_path_tips')?></a></label>
			  <?php
			    $google_array = json_decode($this->tts_config->google, TRUE);
				if ($this->config->item('my_demo_mode')) {
					$ttsc_google_config_file = '******';
				}
				else {
					(set_value('ttsc_google_config_file') != '') ? $ttsc_google_config_file = set_value('ttsc_google_config_file') : $ttsc_google_config_file = $google_array['config_file'];
			    }
				$data = array(
				  'name' => 'ttsc_google_config_file',
				  'id' => 'ttsc_google_config_file',
				  'value' => $ttsc_google_config_file,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_google_config_file', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_azure')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_config_subscription_key')?></label>
			  <?php
			    $azure_array = json_decode($this->tts_config->azure, TRUE);
				if ($this->config->item('my_demo_mode')) {
					$ttsc_azure_key = '******';
				}
				else {
					(set_value('ttsc_azure_key') != '') ? $ttsc_azure_key = set_value('ttsc_azure_key') : $ttsc_azure_key = $azure_array['subscription_key'];
			    }
				$data = array(
				  'name' => 'ttsc_azure_key',
				  'id' => 'ttsc_azure_key',
				  'value' => $ttsc_azure_key,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_azure_key', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_region')?> <?=my_caption('tts_configuration_azure_region_notice')?></label>
			  <?php
			    (set_value('ttsc_azure_region') != '') ? $ttsc_azure_region = set_value('ttsc_azure_region') : $ttsc_azure_region = $azure_array['region'];
			    $options = array(
				  'australiaeast' => 'Australia East',
				  'canadacentral' => 'Canada Central',
				  'eastus' => 'East US',
				  'centralindia' => 'Central India',
				  'southcentralus' => 'South Central US',
				  'southeastasia' => 'Southeast Asia',
				  'uksouth' => 'UK South',
				  'westeurope' => 'West Europe',
				  'westus2' => 'West US 2'
				);
			    $data = array(
				  'id' => 'ttsc_azure_region',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_azure_region', $options, $ttsc_azure_region, $data);
			  ?>
			</div>
		  </div>
		  <hr class="dotted">
		  <div class="row form-group mb-2">
		    <div class="col-lg-12">
			  <label><b><?=my_caption('tts_configuration_wasabi')?></b></label>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_config_file_path')?> <?=my_caption('tts_configuration_sp_config_file_path_tips')?></label>
			  <?php
			    $wasabi_array = json_decode($this->tts_config->wasabi, TRUE);
				if ($this->config->item('my_demo_mode')) {
					$ttsc_wasabi_config_file = '******';
				}
				else {
					(set_value('ttsc_wasabi_config_file') != '') ? $ttsc_wasabi_config_file = set_value('ttsc_wasabi_config_file') : $ttsc_wasabi_config_file = $wasabi_array['config_file'];
			    }
				$data = array(
				  'name' => 'ttsc_wasabi_config_file',
				  'id' => 'ttsc_wasabi_config_file',
				  'value' => $ttsc_wasabi_config_file,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_wasabi_config_file', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_region')?></label>
			  <?php
			    (set_value('ttsc_wasabi_region') != '') ? $ttsc_wasabi_region = set_value('ttsc_wasabi_region') : $ttsc_wasabi_region = $wasabi_array['region'];
			    $options = array(
				  'us-east-1' => 'us-east-1',
				  'us-east-2' => 'us-east-2',
				  'us-west-1' => 'us-west-1',
				  'eu-central-1' => 'eu-central-1',
				  'us-central-1' => 'us-central-1'
				);
			    $data = array(
				  'id' => 'ttsc_wasabi_region',
				  'class' => 'form-control'
				);
				echo form_dropdown('ttsc_wasabi_region', $options, $ttsc_wasabi_region, $data);
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_bucket')?></label>
			  <?php
			    (set_value('ttsc_wasabi_bucket') != '') ? $ttsc_wasabi_bucket = set_value('ttsc_wasabi_bucket') : $ttsc_wasabi_bucket = $wasabi_array['bucket'];
			    $data = array(
				  'name' => 'ttsc_wasabi_bucket',
				  'id' => 'ttsc_wasabi_bucket',
				  'value' => $ttsc_wasabi_bucket,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('ttsc_wasabi_bucket', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('tts_configuration_sp_folder')?></label>
			  <?php
			    (set_value('ttsc_wasabi_folder') != '') ? $ttsc_wasabi_folder = set_value('ttsc_wasabi_folder') : $ttsc_wasabi_folder = $wasabi_array['folder'];
			    $data = array(
				  'name' => 'ttsc_wasabi_folder',
				  'id' => 'ttsc_wasabi_folder',
				  'value' => $ttsc_wasabi_folder,
				  'class' => 'form-control'
				);
				echo form_input($data);
			  ?>
			</div>
		  </div>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <?php
			    $data = array(
				  'type' => 'submit',
				  'name' => 'btn_change',
				  'id' => 'btn_change',
				  'value' => my_caption('global_save_changes'),
				  'class' => 'btn btn-primary mr-2'
			    );
			    echo form_submit($data);
			  ?>
			</div>
		  </div>
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>