<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('tts_file_view')?></h1>

  <div class="row">
    <div class="col-lg-9">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('tts_file_view')?></h6>
        </div>
        <div class="card-body">
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_file_view_identifier')?> :</span><br>
			  <?=my_esc_html($rs->ids)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_time')?> :</span><br>
			  <?=my_conversion_from_server_to_local_time(my_esc_html($rs->created_time), $this->user_timezone, $this->user_dtformat)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_language')?> :</span><br>
			  <?=my_esc_html($rs->language_name)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_voice')?> :</span><br>
			  <?=my_esc_html($rs->voice_name)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_engine')?> :</span><br>
			  <?=str_replace('neural', my_caption('tts_engine_neural'), str_replace('standard', my_caption('tts_engine_standard'), $rs->engine))?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_text_characters_count')?> :</span><br>
			  <?=my_esc_html($rs->characters_count)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('tts_file_uri')?> :</span><br>
			  <?php
			  $tts_uri = $rs->tts_uri;
			  (substr($tts_uri, 0 ,4) != 'http') ?	$tts_uri = base_url($tts_uri) :null;
			  echo '<a href="' . $tts_uri . '" target="_blank">' . $tts_uri . '</a>';
			  ?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('tts_text')?> :</span><br>
			  <textarea cols="40" rows="17" class="form-control"><?=my_esc_html($rs->text)?></textarea>
			</div>
		  </div>
		  <?php if (my_check_permission('TTS Management') && $this->router->method == 'admin_tts_view') { ?>
		  <div class="row mb-2">
		    <div class="col-lg-6">
			  <h5><?=my_caption('tts_admin_area')?></h5>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('global_user')?> :</span><br>
			  <a href="<?php echo base_url('admin/edit_user/') . $rs->user_ids?>"><?=my_user_setting($rs->user_ids, 'email_address')?></a>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_scheme')?> :</span><br>
			  <?=my_esc_html($rs->scheme)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_language_code')?> :</span><br>
			  <?=my_esc_html($rs->language_code)?>
			</div>
		    <div class="col-lg-6">
			  <span class="font-weight-bold"><?=my_caption('tts_voice_id')?> :</span><br>
			  <?=str_replace('{{engine}}', ucfirst($rs->engine), $rs->voice_id)?>
			</div>
		  </div>
		  <div class="row mb-5">
		    <div class="col-lg-12">
			  <span class="font-weight-bold"><?=my_caption('tts_file_storage')?> :</span><br>
			  <?=my_esc_html($rs->storage)?>
			</div>
		  </div>
		  <?php } ?>
		  <hr>
		  <div class="row">
			<div class="col-lg-6 offset-6 text-right">
			  <button type="button" class="btn btn-primary" onclick="window.history.back()"><?=my_caption('global_go_back')?></button>
			</div>
		  </div>
		</div>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>