<div class="form-group mt-1">
  <label for="name"><b>TRANSLATION NAME</b></label>
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
  <label for="translate_to"><b>TARGET LANGUAGE (select language to translate into)</b></label>
  <select id="translate_to" name="target_lang" class="form-control">
	<?php foreach($supported_languages as $lc => $ln): ?>
		<option value="<?= $lc ?>" <?= ($lc == 'en') ? 'selected=selected' : '' ?>><?= $ln ?></option>
	<?php endforeach; ?>
  </select>
  </div>
<div class="col-lg-6 pb-3">
<button id='translate_text_under_tts' class='btn btn-primary mt-3'>Translate</button></div>
