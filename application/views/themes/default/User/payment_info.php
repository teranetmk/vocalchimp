<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<style type="text/css">
.credit_card_area .text-danger{
  margin-left: 38%;
}
</style>
<div class="container-fluid">
<div class="card">
	<div class="row">
		<div class="col-md-7">
	<div class="card-body">
<script src="https://js.stripe.com/v3/"></script>
<div id="page" class="site grid-container container mt-5">
<div class="offset-1 card">
<h3 class="p-3">Update credit card details</h3>
<div class="group credit_card_area">
  <label for="name">
    <span>Your name</span>
  <input type="text" id="name" placeholder="Please enter your name" <?= isset($name) ? "value='$name'" : '' ?> style="padding-left:10px;width: 65%;" />
  </label>
      <label>
        <span>Card number</span>
        <div id="card-number-element" class="field"></div>
      </label>
      <span id="card-number-error" role="alert" class="text-danger d-none"></span>
      <label>
        <span>Expiry date</span>
        <div id="card-expiry-element" class="field"></div>
      </label>
      <span id="exp-date-error" role="alert" class="text-danger d-none"></span>
      <label>
        <span>CVC / CVV</span>
        <div id="card-cvc-element" class="field"></div>
      </label>
      <span id="cvv-number-error" role="alert" class="text-danger d-none"></span>
      <label>
        <span>Zip code</span>
        <?php /* <input id="postal-code" name="postal_code" class="field" placeholder="ZIP CODE" /> */ ?>
        <div id="zip-code-element" class="field"></div>
      </label>
      <span id="zip-code-error" role="alert" class="text-danger d-none"></span>
    </div>
    <button type="submit" id="submit-payment-btn">Update Card Details</button>
    <div class="outcome">
      <div class="error"></div>
      <div class="success">
        <span class="token"></span>
      </div>
    </div>
</div>
</div>
</div>
</div>
<div class="col-md-3 mt-3">
  <p class="mt-5">Card on file:</p>
  <div class="card">
    <div class="card-body">
      <div id="cncn">XXXX XXXX XXXX <?= $last4 ?></div>
      <div id="lower">Exp: <span><?= $mn ?>/<?= $yr ?></span></div>
    </div>
  </div>
</div>
</div>
</div>
</div>
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
<script src="/assets/themes/default/vendor/blockui/jquery.blockUI.js"></script>
<script src="/assets/themes/default/vendor/sweetalert2/sweetalert2.min.js"></script>
<style type="text/css">
  .group {
  background: white;
  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
  border-radius: 4px;
  margin-bottom: 20px;
}

label {
  position: relative;
  color: #3A3A3A;
  height: 40px;
  line-height: 40px;
  margin-left: 20px;
  display: flex;
  flex-direction: row;
}

.group label:not(:last-child) {
  border-bottom: 1px solid #F0F5FA;
}

label > span {
  width: 170px;
  text-align: right;
  margin-right: 30px;
}

label > span.brand {
  width: 30px;
}

.field {
  font-weight: 300;
  color: #31325F;
  flex: 1;
  padding: 10px;
  border: 1px solid #1a1a1a;
  cursor: text;
}

.field::-webkit-input-placeholder {
  color: #CFD7E0;
}

.field::-moz-placeholder {
  color: #CFD7E0;
}

button {
  float: left;
  display: block;
  background: #ed236e;
  color: white;
  box-shadow: 0 7px 14px 0 rgba(49, 49, 93, 0.10), 0 3px 6px 0 rgba(0, 0, 0, 0.08);
  border-radius: 4px;
  margin-top: 20px;
  font-size: 15px;
  font-weight: 400;
  width: 100%;
  height: 40px;
  outline: none;
}

button:focus {
  background: #67072a;
}

button:active {
  background: #880c3a;
}

.outcome {
  float: left;
  width: 100%;
  padding-top: 8px;
  min-height: 24px;
  text-align: center;
}

.success,
.error {
  display: none;
  font-size: 13px;
}

.success.visible,
.error.visible {
  display: inline;
}

.error {
  color: #E4584C;
}

.success {
  color: #666EE8;
}

.success .token {
  font-weight: 500;
  font-size: 13px;
}

</style>
<script type="text/javascript">
function my_blockUI() {
$.blockUI({ overlayColor: '#000000',
    state: 'primary', message: '<p style="background-color: #ffffff;">Processing Payment...</p><img src="/assets/themes/default/img/loading.svg" alt="Processing..."/>',
    css: {
      border:"none",
      backgroundColor:"transparent"
    }
  });
}
var stripe = Stripe('<?= $publishable_key ?>');
var elements = stripe.elements();

var style = {
  base: {
    iconColor: '#666EE8',
    color: '#31325F',
    border: '2px solid #1a1a1a',
    borderRadius: '1.3rem',
    lineHeight: '40px',
    fontWeight: 300,
    letterSpacing: '0.5px',
    fontFamily: 'Helvetica Neue',
    fontSize: '18px',
    '::placeholder': {
      color: '#CFD7E0',
    },
  },
};

var cardNumberElement = elements.create('cardNumber', {
  showIcon: true
});
cardNumberElement.mount('#card-number-element');

var cardExpiryElement = elements.create('cardExpiry', {});
cardExpiryElement.mount('#card-expiry-element');

var cardCvcElement = elements.create('cardCvc', {});
cardCvcElement.mount('#card-cvc-element');

var postalCode = elements.create('postalCode', {
    // style: style
});
postalCode.mount('#zip-code-element');

var invalidForm = false;
cardNumberElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('card-number-error');
  if (event.error) {
      displayError.textContent = event.error.message;
      $("#card-number-error").show();
      $("#card-number-error").removeClass('d-none');
      invalidForm = true;
  } else {
    $("#card-number-error").hide();
      displayError.textContent = '';
  }
});

cardCvcElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('cvv-number-error');
  if (event.error) {
      displayError.textContent = event.error.message;
      $("#cvv-number-error").show();
      $("#cvv-number-error").removeClass('d-none');
      invalidForm = true;
  } else {
    $("#cvv-number-error").hide();
      displayError.textContent = '';
  }
});

cardExpiryElement.addEventListener('change', function(event) {
  var displayError = document.getElementById('exp-date-error');
  if (event.error) {
      displayError.textContent = event.error.message;
      displayError.class = 'text-danger';
      invalidForm = true;
      $("#exp-date-error").show();
      $("#exp-date-error").removeClass('d-none');
  } else {
    $("#exp-date-error").hide();
      displayError.textContent = '';
  }
});

postalCode.addEventListener('change', function(event) {
  var displayError = document.getElementById('zip-code-error');
  if (event.error) {
      displayError.textContent = event.error.message;
      displayError.class = 'text-danger';
      invalidForm = true;
      $("#zip-code-error").show();
      $("#zip-code-error").removeClass('d-none');
  } else {
    $("#zip-code-error").hide();
      displayError.textContent = '';
  }
});

const btn = document.querySelector('#submit-payment-btn');
btn.addEventListener('click', async (e) => {
  e.preventDefault();
  my_blockUI();
  const nameInput = document.getElementById('name');
  // Create payment method and confirm payment intent.
  stripe.createPaymentMethod({type: 'card', card: cardNumberElement}).then((result) => {
    if (result.paymentMethod) {
      $.ajax({
        url: '/user/payment_info_update/'+result.paymentMethod.id,
        method: 'GET',
        dataType:'json',
        success: function (response) {
          if(response.error){
            btn.innerHTML = 'Update Card Details';
            $.unblockUI();
            Swal.fire({
            title: 'Could not update Credit Card',
            text: 'Card is NOT updated! Please try again with a different card.',
            icon: 'error',
            confirmButtonText: 'OK'
            }).then(function(result) {window.location.href = window.location.href;});
            return;
          }else{
            $.unblockUI();
            Swal.fire({
            title: 'Update Confirmation',
            text: 'Card is updated!',
            icon: 'success',
            confirmButtonText: 'Awesome!'
            }).then(function(result) { window.location.href = window.location.href; });
            return;
          }
        }
      });

      /*var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
        //console.log(result);
        if (this.readyState == 4 && this.status == 200) {
          Swal.fire({
          title: 'Update Confirmation',
          text: 'Card is updated!',
          icon: 'success',
          confirmButtonText: 'Awesome!'
          }).then(function(result) {}); //window.location.href = window.location.href;
          return;
        }
        if (this.readyState == 4 && this.status != 200) {
          Swal.fire({
          title: 'Could not update Credit Card',
          text: 'Card is NOT updated! Please try again with a different card.',
          icon: 'error',
          confirmButtonText: 'OK'
          }).then(function(result) {window.location.href = window.location.href;});
          return;
        }
      }
      xhttp.open("GET", "/user/payment_info_update/"+result.paymentMethod.id, true);
      xhttp.send();
      //console.log(res);
      $.unblockUI();
      return;*/
    } else {
      Swal.fire({
          title: 'Could not update Credit Card',
          text: 'Please enter a valid card number.',
          icon: 'error',
          confirmButtonText: 'OK'
          }).then(function(result) { $.unblockUI(); });
    }
    });
});
</script>
<?php my_load_view($this->setting->theme, 'footer')?>