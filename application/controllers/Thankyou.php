<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Thankyou extends CI_Controller {

	
	public function __construct() {
        parent::__construct();
        $this->setting = my_global_setting('all_fields');
	
    }

    public function index() {

        $this->load->helper('my_tts');
        if(!$_SESSION['user_ids']) {
            redirect('/home/pricing');
        }
        my_load_view($this->setting->theme, 'User/thankyou');
    }


	
	
}
?>
