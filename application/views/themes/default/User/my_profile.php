<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->helper('my_basic_data');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <h1 class="h3 mb-4 text-gray-800"><?=my_caption('mp_title')?></h1>
  <?php echo form_open_multipart(base_url('user/my_profile_action/'), ['method'=>'POST']); ?>
  <div class="row">
    <div class="col-lg-8">
	  <?php
		$data['rs'] = $rs;
		$data['card_title'] = my_caption('mp_title');
	    my_load_view($this->setting->theme, 'Generic/show_flash_card', $data);
		my_load_view($this->setting->theme, 'Generic/user_profile', $data);
	   ?>
	</div>
  </div>
  <?php echo form_close(); ?>
</div>
<?php my_load_view($this->setting->theme, 'footer')?>
