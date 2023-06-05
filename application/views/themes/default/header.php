<?php
  defined('BASEPATH') OR exit('No direct script access allowed'); 
  require_once('menu_builder.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title><?php echo my_caption('dashboard_html_title') . ' - ' . $this->setting->sys_name; ?></title>
  <link rel="shortcut icon" href="<?=base_url()?>upload/favicon.ico" type="image/x-icon">
  <link href="<?=base_url()?>assets/themes/default/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="<?=base_url()?>assets/themes/default/vendor/bootstrap-select/css/bootstrap-select.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/vendor/sweetalert2/sweetalert2.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/vendor/summernote/summernote.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/custom.css?v=2.5" rel="stylesheet">
  <?php if (!empty($this->setting->dashboard_custom_css)) { ?>
    <link type="text/css" href="<?=$this->setting->dashboard_custom_css?>" rel="stylesheet">
  <?php } ?>
  <!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5DMXH48');</script>
<!-- End Google Tag Manager -->
<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-1P47JGM5MF"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-1P47JGM5MF');
</script>
    <!-- Facebook Pixel Code -->
    <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '214326412412841');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=214326412412841&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Facebook Pixel Code -->
</head>

<body id="page-top">
  <div id="wrapper">
    <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="javascript:void(0)">
        <div class="sidebar-brand-icon">
          <img src="<?=base_url('upload/favicon.ico')?>" class="icon-view">
        </div>
        <div class="sidebar-brand-text mx-3"><?=my_esc_html($this->setting->sys_name)?></div>
      </a>
	  <?php
	    if (my_check_permission('TTS Management') || my_check_permission('User Management')) {
	  ?>
      <hr class="sidebar-divider my-0">
	  <?php ($this->router->fetch_class() == 'dashboard') ? $active = ' active' : $active = ''; ?>
      <li class="nav-item<?=my_esc_html($active)?>">
        <a class="nav-link" href="<?=base_url('dashboard')?>">
          <i class="fas fa-fw fa-tachometer-alt"></i>
          <span><?=my_caption('menu_sidebar_dashboard')?></span></a>
      </li>
	  <?php
	    }
	  ?>
	  <hr class="sidebar-divider">
      <div class="sidebar-heading">
        <?=my_caption('menu_sidebar_user_panel')?>
      </div>
	  <?php
	    // show user panel
	    echo my_menu_display($menu_user_panel, 'user_panel');
	    //show admin panel
		if ($menu_admin_panel_display == 1) {
			$admin_panel_header = '<hr class="sidebar-divider"><div class="sidebar-heading">' . my_caption('menu_sidebar_admin_panel') . '</div>';
			echo my_esc_html($admin_panel_header . my_menu_display($menu_admin_panel, 'admin_panel'));
		}
	  ?>
      <hr class="sidebar-divider d-none d-md-block">
      <li class="nav-item">
        <a class="nav-link" href="javascript:void(0)" onclick="actionQuery('<?=my_caption('global_signout_query_title')?>', '<?=my_caption('global_signout_query_text')?>', '', '<?=base_url('generic/sign_out')?>')">
          <i class="fa fa-sign-out-alt"></i>
          <span><?=my_caption('menu_sidebar_topbar_signout')?></span>
		</a>
      </li>
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>
    </ul>
	
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>
		  <?php
		  if ($_SESSION['is_admin']) {
			  $enhanced_safety_result = my_enhanced_safety(); 
			  if ($enhanced_safety_result !== TRUE) {
				  echo '<span class="text-danger font-weight-bold">' . $enhanced_safety_result . '</span>';
			  }
		  }
		  if ($this->setting->maintenance_mode) {?>
		    <span class="text-danger font-weight-bold"><?=my_caption('menu_topbar_maintenance_mode')?></span>
          <?php } 
		  if (!empty($_SESSION['impersonate'])) {
		  ?>
		    <span class="text-danger font-weight-bold"><?=my_caption('user_impersonate_impersonating')?>, <a href="<?=base_url('admin/stop_impersonating')?>" class="text-danger font-weight-bold"><u><?=my_caption('user_impersonate_return')?></u></a></span>
		  <?php } ?>
		  <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown no-arrow d-sm-none">
              <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-search fa-fw"></i>
              </a>
              <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                <form class="form-inline mr-auto w-100 navbar-search">
                  <div class="input-group">
                    <input type="text" class="form-control bg-light border-0 small" placeholder="<?=my_caption('menu_topbar_search_placeholder')?>" aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                      <button class="btn btn-primary" type="button">
                        <i class="fas fa-search fa-sm"></i>
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </li>
            <div class="topbar-divider d-none d-sm-block"></div>
            <li class="nav-item dropdown no-arrow">
              <?php $img_url = base_url('upload/avatar/' . $this->user_avatar); ?>
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				<img class="img-profile rounded-circle mr-3" src="<?=my_esc_html($img_url)?>">
				<span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo my_caption('menu_topbar_welcome_back') . ', ' . $_SESSION['full_name']?></span>
			  </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item mb-2" href="<?=base_url('user/my_profile')?>">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?=my_caption('menu_sidebar_topbar_my_profile')?>
                </a>
                <a class="dropdown-item mb-2" href="<?=base_url('user/change_password')?>">
                  <i class="fa fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?=my_caption('menu_sidebar_topbar_change_password')?>
                </a>
                <div class="dropdown-divider"></div>
                <a href="javascript:void(0)" class="dropdown-item" onclick="actionQuery('<?=my_caption('global_signout_query_title')?>', '<?=my_caption('global_signout_query_text')?>', '', '<?=base_url('generic/sign_out')?>')">
                  <i class="fa fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  <?=my_caption('menu_sidebar_topbar_signout')?>
                </a>
              </div>
            </li>
			<div class="topbar-divider d-none d-sm-block"></div>
          </ul>
        </nav>