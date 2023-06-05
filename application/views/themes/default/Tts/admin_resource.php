<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
	<div class="col-lg-3 text-left">
	  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('tts_resource_list')?></h1>
	</div>
	<div class="col-lg-9 text-right">
	  <div class="btn-group">
	    <div class="dropdown">
	      <button class="btn btn-primary dropdown-toggle mr-2" type="button" id="dropdownMenuButton1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=my_caption('tts_sync_resource')?> </button>
          <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton1">
	        <button class="dropdown-item" id="tts_sync_aws" value="<?=my_caption('tts_sync_resource_alert_aws')?>"><?=my_caption('tts_sync_resource_aws')?></button>
			<button class="dropdown-item" id="tts_sync_google" value="<?=my_caption('tts_sync_resource_alert_google')?>"><?=my_caption('tts_sync_resource_google')?></button>
			<button class="dropdown-item" id="tts_sync_azure" value="<?=my_caption('tts_sync_resource_alert_azure')?>"><?=my_caption('tts_sync_resource_azure')?></button>
		  </div>
		</div>
		<div class="dropdown">
		  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?=my_caption('global_bulk_actions')?> </button>
		  <div class="dropdown-menu animated--fade-in" aria-labelledby="dropdownMenuButton2">
		    <button class="dropdown-item" id="bulk_enable_aws" value="<?=my_caption('tts_resource_bulk_enable_query_aws')?>"><?=my_caption('tts_resource_bulk_enable_aws')?></button>
			<button class="dropdown-item" id="bulk_disable_aws" value="<?=my_caption('tts_resource_bulk_disable_query_aws')?>"><?=my_caption('tts_resource_bulk_disable_aws')?></button>
			<button class="dropdown-item" id="bulk_delete_aws" value="<?=my_caption('tts_resource_bulk_delete_query_aws')?>"><?=my_caption('tts_resource_bulk_delete_aws')?></button>
			<div class="dropdown-divider"></div>
			<button class="dropdown-item" id="bulk_enable_google" value="<?=my_caption('tts_resource_bulk_enable_query_google')?>"><?=my_caption('tts_resource_bulk_enable_google')?></button>
			<button class="dropdown-item" id="bulk_disable_google" value="<?=my_caption('tts_resource_bulk_disable_query_google')?>"><?=my_caption('tts_resource_bulk_disable_google')?></button>
			<button class="dropdown-item" id="bulk_delete_google" value="<?=my_caption('tts_resource_bulk_delete_query_google')?>"><?=my_caption('tts_resource_bulk_delete_google')?></button>
			<div class="dropdown-divider"></div>
			<button class="dropdown-item" id="bulk_enable_azure" value="<?=my_caption('tts_resource_bulk_enable_query_azure')?>"><?=my_caption('tts_resource_bulk_enable_azure')?></button>
			<button class="dropdown-item" id="bulk_disable_azure" value="<?=my_caption('tts_resource_bulk_disable_query_azure')?>"><?=my_caption('tts_resource_bulk_disable_azure')?></button>
			<button class="dropdown-item" id="bulk_delete_azure" value="<?=my_caption('tts_resource_bulk_delete_query_azure')?>"><?=my_caption('tts_resource_bulk_delete_azure')?></button>
			<div class="dropdown-divider"></div>
			<button class="dropdown-item" id="bulk_free" value="<?=my_caption('tts_resource_bulk_set_free_query')?>"><?=my_caption('tts_resource_bulk_set_free')?></button>
			<button class="dropdown-item" id="bulk_payg" value="<?=my_caption('tts_resource_bulk_set_payg_query')?>"><?=my_caption('tts_resource_bulk_set_payg')?></button>
			<button class="dropdown-item" id="bulk_revoke" value="<?=my_caption('tts_resource_bulk_revoke_query')?>"><?=my_caption('tts_resource_bulk_revoke')?></button>
		  </div>
	    </div>
	  </div>
    </div> 	
  </div>

  <div class="row">
    <div class="col-lg-12">
	  <?php my_load_view($this->setting->theme, 'Generic/show_flash_card');?>
	  <div class="card shadow mb-4">
	    <div class="card-header py-3">
		  <h6 class="m-0 font-weight-bold text-primary"><?=my_caption('tts_resource_list')?></h6>
        </div>
        <div class="card-body">
		  <div class="table-responsive">
		    <table id="dataTable_tts_resource" class="table table-bordered">
			  <thead>
			    <tr>
				  <th width="8%"><?=my_caption('global_status')?></th>
				  <th width="12%"><?=my_caption('tts_scheme')?></th>
				  <th width="12%"><?=my_caption('tts_engine')?></th>
				  <th width="16%"><?=my_caption('tts_language_name')?></th>
				  <th width="12%"><?=my_caption('tts_gender')?></th>
				  <th width="12%"><?=my_caption('tts_voice_name')?></th>
				  <th width="20%"><?=my_caption('global_description')?></th>
				  <th width="8%"><?=my_caption('global_actions')?></th>
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
