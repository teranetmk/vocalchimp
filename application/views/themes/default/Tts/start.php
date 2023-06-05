<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 text-left">
	  <h1 class="h3 mb-3 text-gray-800">Generate Voiceovers</h1>
	  <p><b>Usage:</b> You have used <b id="charactors_used_number"><?= round($characters_used/1) ?></b> of <b>
	  	<?= $characters_limit/1 ?></b> characters</p>
	  <?= isset($used_up) ? '<p class="text-danger"><b>This plan is <b>EXHAUSTED</b>. <a href="/user/pay_now">Please click here to upgrade your plan</a></b></p>' : '' ?>
	</div>
  </div>
  <div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		  <h6 class="m-0 font-weight-bold text-primary">Generate Voiceovers</h6>
        </div>
        <div class="card-body">
		  <?php echo form_open(base_url('tts/start_action/'), ['method'=>'POST', 'id'=>'tts_start', 'name'=>'tts_start']); ?>
		  <input type="hidden" id="ttsc_preview_delay" name="ttsc_preview_delay" value="0">
		  <div class="row">
		    <div class="col-lg-4">
			  <div class="row mb-4">
			    <div class="col-lg-12">
				  <input type="hidden" name="tts_hidden_current_language_hidden" id="tts_hidden_current_language" value="<?=my_esc_html($this->tts_config->default_language)?>">
				  <input type="hidden" name="tts_engine" id="tts_engine" value="neural">
				  <input type="hidden" name="maximum_characters_notice" id="maximum_characters_notice" value="<?=my_caption('tts_notice_character_limit')?>">
				  <input type="hidden" name="tts_ssml_mode" id="tts_ssml_mode" value="0">
				  <input type="hidden" name="tts_text_input_position" id="tts_text_input_position">

				  <div class="mt-3">
				  	<label><span class="step">1</span><b>WOULD YOU LIKE TO TRANSLATE YOUR SCRIPT TO ANOTHER LANGUAGE?</b></label>
				  <input type="radio" name="translation_needed" id="translation_needed-yes" value="Yes" />
				  	<label for="translation_needed-yes">Yes</label>
				  <input type="radio" name="translation_needed" id="translation_needed-no" value="No" checked="checked" />
				  <label for="translation_needed-no">No</label>
				  	</div>
				</div>
				<div class="col-lg-12 lightblue d-none" id="translation_needed_area">
				</div>
			  </div>
			  <div class="row">
			  	<div class="col-lg-12 mb-3">
			  	<label><span class="step">2</span><b>CHOOSE YOUR VOICEOVER LANGUAGE</b></label>
				  <?php
					
					$data = array(
					  'id' => 'tts_language',
					  'class' => 'form-control'
					);
					
					echo form_dropdown('tts_language', my_tts_language_list('all'), $this->tts_config->default_language, $data);
				  ?>
				</div>
			    <div class="col-lg-12">
				  <label><span class="step">3</span><b>CHOOSE YOUR VOICE</b></label>
				  <?php
				  $data = array(
					      'id' => 'tts_voice_list',
					      'class' => 'form-control'
					    );
					    echo form_dropdown('tts_voice_list', '', '', $data);
				    ?>
				</div>
				<input name="tts_resource_ids" id="tts_resource_ids" type="hidden" />
			  </div>
			  <hr />
			  <div class="lightgrey">
			  <div class="row">
			        <div class="col-lg-12">
			  		<p><b>OPTIONAL:</b></p>
			  			<label><b>CHOOSE OUTPUT VOLUME</b></label>
				      <?php
					    $options = array(
					      'default' => my_caption('tts_ssml_volume_title') . ': Default',
					      'x-soft' => my_caption('tts_ssml_volume_title') . ': X-soft',
					      'soft' => my_caption('tts_ssml_volume_title') . ': Soft',
					      'medium' => my_caption('tts_ssml_volume_title') . ': Medium',
					      'loud' => my_caption('tts_ssml_volume_title') . ': Loud',
					      'x-loud' => my_caption('tts_ssml_volume_title') . ': X-loud',
					    );
					    $data = array(
					      'id' => 'tts_ssml_volume',
					      'class' => 'form-control'
					    );
					    echo form_dropdown('tts_ssml_volume', $options, 'default', $data);
				      ?>
				    </div>
				</div>
				<div class="row mt-3 pb-2">
			        <div class="col-lg-12">
					<label><b>CHOOSE SPEAKING RATE</b></label>
				      <?php
					    $options = array(
					      'default' => my_caption('tts_ssml_rate_title') . ': Default',
					      'x-slow' => my_caption('tts_ssml_rate_title') . ': X-slow',
					      'slow' => my_caption('tts_ssml_rate_title') . ': Slow',
					      'medium' => my_caption('tts_ssml_rate_title') . ': Medium',
					      'fast' => my_caption('tts_ssml_rate_title') . ': Fast',
					      'x-fast' => my_caption('tts_ssml_rate_title') . ': X-fast'
					    );
					    $data = array(
					      'id' => 'tts_ssml_spk_rate',
					      'class' => 'form-control'
					    );
					    echo form_dropdown('tts_ssml_spk_rate', $options, 'default', $data);
				      ?>
				    </div>
				  </div>
				  </div>
				  <div class="row mt-3">
				  	<span class="step">4</span>
				  <input type="hidden" name="synthesize_type" id="synthesize_type" value="save">
				  <?php
				    $data = array(
					  'type' => 'submit',
					  'name' => 'tts_btn_synthesize_to_preview',
					  'id' => 'tts_btn_synthesize_to_preview',
					  'value' => my_caption('tts_btn_synthesize_to_preview'),
					  'class' => 'btn btn-primary mt-3'
					);
					echo '<div class="col-lg-6">' . form_submit($data) . '</div>';
					
				    $data = array(
					  'type' => 'submit',
					  'name' => 'tts_btn_synthesize_to_file',
					  'id' => 'tts_btn_synthesize_to_file',
					  'value' => 'Save File',
					  'class' => 'btn btn-success mt-3 w-100'
					);
					echo '<div class="col-lg-6">' . form_submit($data) . '</div>';
					?>
				</div>
			</div>
		    <div class="col-lg-8">
		    	<div class="form-group" id="scriptarea">
			  <label><b class="mr-3">ENTER YOUR SCRIPT</b><?php if ($this->config->item('my_demo_mode')) { echo '<small class="text-danger">Please note that currently, it\'s running in the demo mode, only the first 200 words will be synthesized to file.</small>';}?></label>
			  <?php
			    $data = array(
				  'name' => 'tts_text',
				  'id' => 'tts_text',
				  'class' => 'form-control',
				  'placeholder' => 'Enter your script...',
				  'rows'=> 7
				);
				echo form_textarea($data);
			  ?>
				</div>
			  <div class="row mt-1">
			    <div class="col-lg-6">
				  <small><span id="tts_text_used">0</span> <?=my_caption('tts_characters_used_up_to')?> <span id="tts_text_character_limit"><?=my_esc_html($this->tts_config->maximum_character)?></span> <?=my_caption('tts_characters')?></small>
			    </div>
				<div class="col-lg-6 text-right">
				  <small>
				    <span id="tts_text_pause" class="mr-3"><a href="javascript:void(0)" class="btn btn-primary">Add 1 Second Pause</a></span>
				    <span id="tts_text_clear" class="mr-1"><a href="javascript:void(0)"><?=my_caption('tts_clear_text')?></a></span>
				  </small>
				</div>
			  </div>
			</div>
		  </div>
		  <?php echo form_close(); ?>
		</div>
      </div>
	</div>
  </div>
  
  <div class="row mt-4 mb-4">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary">My Voiceovers</h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_list_tts" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="12%"><?=my_caption('global_time')?></th>
				  <th width="13%"><?=my_caption('tts_language')?></th>
				  <th width="10%"><?=my_caption('tts_voice')?></th>
				  <th width="45%"><?=my_caption('tts_text_preview')?></th>
				  <th width="13%"><?=my_caption('global_actions')?></th>
			    </tr>
			  </thead>
			</table>
          </div>
		</div>
      </div>
	</div>
  </div>
  
  <div class="modal fade" id="tts_listen_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog" role="document">
	  <div class="modal-content">
	    <div class="modal-header">
		  <h5 class="modal-title"><?=my_caption('tts_player_title')?></h5>
		  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		    <i aria-hidden="true" class="fas fa-times"></i>
		  </button>
		</div>
		<div class="modal-body">
		  <div class="row">
		    <div class="col-lg-12 text-center mt-3 mb-3">
			  <audio controls id="tts_player" name="tts_player"></audio>
			</div>
		  </div>
		</div>
		<div class="modal-footer">
		  <a id="tts_view_text" href="" target="_blank" class="btn btn-primary mr-2"><?=my_caption('tts_text_view')?></a>
		  <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=my_caption('global_simple_input_modal_close_button')?></button>
		</div>
	  </div>
	</div>
 </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>