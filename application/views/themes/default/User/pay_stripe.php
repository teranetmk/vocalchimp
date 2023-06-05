<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<script src="https://js.stripe.com/v3/"></script>
<script>
	var stripe = Stripe("<?=my_esc_html($publishable_key)?>");
	stripe.redirectToCheckout({
		sessionId: '<?=my_esc_html($checkout_session)?>'
	}).then(
	  function (result) {
		  //handle error if necessary
	  }
	);
</script>