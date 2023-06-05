<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('tts_title')?></h1>
  
  <div class="row mt-4 mb-4">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('tts_all_tts_file')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_list_tts_admin" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="12%"><?=my_caption('global_time')?></th>
				  <th width="13%"><?=my_caption('tts_language')?></th>
				  <th width="10%"><?=my_caption('tts_voice')?></th>
				  <th width="47%"><?=my_caption('tts_text_preview')?></th>
				  <th width="7%"><?=my_caption('tts_text_characters_count')?></th>
				  <th width="11%"><?=my_caption('global_actions')?></th>
			    </tr>
			  </thead>
			</table>
          </div>
		</div>
      </div>
	</div>
  </div>
  
</div>
<?php my_load_view($this->setting->theme, 'footer')?>