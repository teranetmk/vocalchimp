<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script>
let supportedlang = [];
<?php foreach($supported_languages as $lc => $ln): ?>
supportedlang['<?= $lc ?>'] = '<?= $ln ?>';
<?php endforeach; ?>
</script>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 text-left">
	  <h1 class="h3 mb-3 text-gray-800">My Translations</h1>
	</div>
  </div>
  <div class="row">
		<div class="col-lg-6 card shadow pr-4 ">
        <div class="card-body">
		  <?php $baseurl = isset($free) ? 'free/translate_start_action' : 'translation/start_action/';
		  echo form_open(base_url($baseurl), ['method'=>'POST', 'id'=>'translation_start', 'name'=>'translation_start']); ?>
		  <div class="form-group">
		  <label for="name"><b>NAME</b></label>
		  	<input type="text" name="name" class="form-control" id="name" placeholder="Give this translation a name" />
		  </div>
		  <div class="form-group">
		  <label for="translate_from"><b>ORIGINAL LANGUAGE</b></label>
		  <select id="translate_from" name="original_lang" class="form-control">
			<?php foreach($supported_languages as $lc => $ln): ?>
				<option value="<?= $lc ?>"><?= $ln ?></option>
			<?php endforeach; ?>
		  </select>
		  </div>
		  <div class="form-group">
		  <textarea class="form-control" cols="40" rows="8" name="script" placeholder="Enter the script to translate..."></textarea>
		  </div>
		  <div class="form-group">
		  <label for="translate_to"><b>TARGET LANGUAGE (select language to translate into)</b></label>
		  <select id="translate_to" name="target_lang" class="form-control">
			<?php foreach($supported_languages as $lc => $ln): ?>
				<option value="<?= $lc ?>" <?= ($lc == 'en') ? 'selected=selected' : '' ?>><?= $ln ?></option>
			<?php endforeach; ?>
		  </select>
		  </div>
		  <div class="form-group" id="translated-text-area">
		  </div>
<?php
$data = array(
'type' => 'submit',
'value' => 'Translate',
'id' => 'translate_text',
'class' => 'btn btn-primary mt-3'
);
echo '<div class="col-lg-6">' . form_submit($data) . '</div>';
echo form_close(); ?>
		 </div>
		</div>
		<style>.point85 { font-size:0.85rem; cursor:pointer; } .point85:hover { color: blue; } .row.pt-2 { border: 1px solid #c3c3c3; }</style>
	    <div class="col-lg-6 card shadow p-2">
		  <h6 class="pl-3 pt-2 font-weight-bold text-primary">Previously translated</h6>
        <div class="card-body">
		    <div class="row pt-2 pb-2 pl-1">
			<div class="col-8"><b>Name</b></div>
			<div class="col-4"><b>Actions</b></div>
			</div>
			  <?php foreach($translations as $translation): ?>
				<div class="row pt-2 pb-2 pl-1" data-id="<?= $translation->id ?>" data-csrf="<?php echo $this->security->get_csrf_hash(); ?>">
				<div class="col-8"><?= $translation->name ?></div>
				<div class="col-4">
					<span class="point85 viewtranslation"><i class="fa fa-eye"></i> View</span> |
					<span class="point85 deletetranslation"><i class="fa fa-trash"></i> Delete</span>
				</div>
				<div class="col-12 card mt-2"></div>
				</div>
			  <?php endforeach; ?>
          </div>
      </div>
	</div>
		</div>
      </div>
  </div>
</div>
  
<?php my_load_view($this->setting->theme, 'footer')?>