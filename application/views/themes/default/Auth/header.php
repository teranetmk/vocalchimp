<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$method_name = $this->router->fetch_method();
if ($method_name == 'signup' || $method_name == 'signup_action') {
	$title = my_caption('signup_html_title') . ' - ';
	$description = my_caption('signup_html_description');
}
elseif ($method_name == 'signin' || $method_name == 'signin_action') {
	$title = my_caption('signin_html_title') . ' - ';
	$description = my_caption('signin_html_description');
}
elseif ($method_name == 'forget' || $method_name == 'forget_action') {
	$title = my_caption('forget_html_title') . ' - ';
	$description = my_caption('forget_html_description');
}
elseif ($method_name == 'terms_conditions') {
	$title = my_caption('tc_html_title') . ' - ';
	$description = my_caption('tc_html_description');
}
else {
	$title = '';
	$description = '';
}

?>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="<?=$description?>">
  <meta name="author" content="<?=my_caption('global_html_author')?>">
  <title><?php echo $title . $this->setting->sys_name; ?></title>
  <link rel="shortcut icon" href="<?=base_url()?>upload/favicon.ico" type="image/x-icon">
  <link href="<?=base_url()?>assets/themes/default/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/sb-admin-2.min.css" rel="stylesheet">
  <link href="<?=base_url()?>assets/themes/default/css/custom.css" rel="stylesheet">
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