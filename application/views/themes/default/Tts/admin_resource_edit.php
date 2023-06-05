<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('tts_resource_edit')?></h1>

  <div class="row">
    <div class="col-lg-9">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('tts_resource_edit')?></h6>
        </div>
		<?php echo form_open(base_url('tts/admin_resource_edit_action/'), ['method'=>'POST']); ?>
		<input type="hidden" name="ids" id="ids" value="<?=my_esc_html($rs->ids)?>">
        <div class="card-body">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_scheme')?> :</span><br>
			  <?=my_esc_html($rs->scheme)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_engine')?> :</span><br>
			  <?=my_esc_html($rs->engine)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_language_name')?> :</span><br>
			  <?=my_esc_html($rs->language_name)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_gender')?> :</span><br>
			  <?=my_esc_html($rs->gender)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_language_code')?> :</span><br>
			  <?=my_esc_html($rs->language_code)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_voice_id')?> :</span><br>
			  <?=my_esc_html($rs->voice_id)?>
			</div>
		  </div>
	
		  <div class="row form-group mb-4">
		    <div class="col-lg-12">
			  <div class="custom-control custom-checkbox mt-3">
			    <?php
				  (set_value('tts_resource_enabled') == '') ? $tts_resource_enabled = $rs->enabled : $tts_resource_enabled = set_value('tts_resource_enabled');
				  $data = array(
				    'name' => 'tts_resource_enabled',
					'id' => 'tts_resource_enabled',
					'value' => '1',
					'checked' => $tts_resource_enabled,
					'class' => 'custom-control-input'
				  );
				  echo form_checkbox($data);
				?>
                <label class="custom-control-label" for="tts_resource_enabled"><?=my_caption('global_enabled')?></label>
              </div>
			</div>
		  </div>
		  <div class="row form-group mb-4">
		    <div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('tts_voice_name')?></label>
			  <?php
			    (set_value('tts_voice_name') == '') ? $tts_voice_name = $rs->name : $tts_voice_name = set_value('tts_voice_name');
			    $data = array(
				  'name' => 'tts_voice_name',
				  'id' => 'tts_voice_name',
				  'value' => $tts_voice_name,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('tts_voice_name', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		    <div class="col-lg-6">
			  <label><?=my_caption('global_description')?></label>
			  <?php
			    (set_value('tts_voice_description') == '') ? $tts_voice_description = $rs->description : $tts_voice_description = set_value('tts_voice_description');
			    $data = array(
				  'name' => 'tts_voice_description',
				  'id' => 'tts_voice_description',
				  'value' => $tts_voice_description,
				  'class' => 'form-control'
				);
				echo form_input($data);
				echo form_error('tts_voice_description', '<small class="text-danger">', '</small>');
			  ?>
			</div>
		  </div>
		  <div class="row form-group mb-5">
		    <?php
			  $payment_item_array = my_get_all_payment_items();
			  if (my_check_str_contains($rs->engine, 'standard')) { ?>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('tts_accessibility_standard')?></label>
			  <?php
			    $accessibility_standard_options = array('free'=>'Free', 'payg'=>'Pay-As-You-Go');
			    $accessibility_standard_options += $payment_item_array;
				$access_scope_array = [];
				if ($this->router->fetch_method() == 'admin_resource_edit_action') {  //action page
					foreach ($accessibility_standard_options as $key => $value) {
						if (set_select('access_scope_standard[]' , $key)) {
							array_push($access_scope_array, $key);
						}
					}
				}
				else {  //default loading page
					$access_scope_array = explode(',', $rs->accessibility_standard);
					foreach ($access_scope_array as $scope) {
						array_push($access_scope_array, $scope);
					}
				}		
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
				echo form_multiselect('access_scope_standard[]', $accessibility_standard_options, $access_scope_array, $data);
				echo form_error('access_scope_standard[]', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<?php
			  }
			  if (my_check_str_contains($rs->engine, 'neural')) {
			?>
			<div class="col-lg-6">
			  <label><span class="text-danger">*</span> <?=my_caption('tts_accessibility_neural')?></label>
			  <?php
			    $accessibility_neural_options = array('free'=>'Free', 'payg'=>'Pay-As-You-Go');
			    $accessibility_neural_options += $payment_item_array;
				$access_scope_array = [];
				if ($this->router->fetch_method() == 'admin_resource_edit_action') {  //action page
					foreach ($accessibility_neural_options as $key => $value) {
						if (set_select('access_scope_neural[]' , $key)) {
							array_push($access_scope_array, $key);
						}
					}
				}
				else {  //default loading page
					$access_scope_array = explode(',', $rs->accessibility_neural);
					foreach ($access_scope_array as $scope) {
						array_push($access_scope_array, $scope);
					}
				}		
			    $data = array(
			      'class' => 'form-control selectpicker'
			    );
				echo form_multiselect('access_scope_neural[]', $accessibility_neural_options, $access_scope_array, $data);
				echo form_error('access_scope_neural[]', '<small class="text-danger">', '</small>');
			  ?>
			</div>
			<?php } ?>
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
		</div>
		<?php echo form_close(); ?>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>