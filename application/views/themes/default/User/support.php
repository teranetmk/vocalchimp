<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
	<div class="row">
    <div class="col-lg-12">
	  <div class="card shadow mb-4">
        <div class="card-body">
		  <div class="table-responsive">
		    <p>Email us your questions to <a href="mailto:support@vocalchimp.com">support@vocalchimp.com</a></p>
          </div>
		</div>
      </div>
	</div>
  </div>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>