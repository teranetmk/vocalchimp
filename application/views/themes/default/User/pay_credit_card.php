<!DOCTYPE html>
<html>

<head>
	<title>VocalChimp Payment</title>
	<script src="https://js.stripe.com/v3/"></script>
	<link rel='stylesheet' id='generate-fonts-css'
		href='//fonts.googleapis.com/css?family=Open+Sans:300,300italic,regular,italic,600,600italic,700,700italic,800,800italic'
		media='all' />
	<link rel='stylesheet' id='wp-block-library-css'
		href='https://vocalchimp.com/wp-includes/css/dist/block-library/style.min.css?ver=5.7.2' media='all' />
	<link rel='stylesheet' id='siteorigin-panels-front-css'
		href='https://vocalchimp.com/wp-content/plugins/siteorigin-panels/css/front-flex.min.css?ver=2.12.2' media='all' />
	<link rel='stylesheet' id='generate-style-css'
		href='https://vocalchimp.com/wp-content/themes/generatepress/assets/css/main.min.css?ver=3.0.3' media='all' />
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-1P47JGM5MF"></script>
	<script>
		window.dataLayer = window.dataLayer || [];

		function gtag() {
			dataLayer.push(arguments);
		}
		gtag('js', new Date());

		gtag('config', 'G-1P47JGM5MF');
	</script>
	<!-- Google Tag Manager -->
	<script>
		(function (w, d, s, l, i) {
			w[l] = w[l] || [];
			w[l].push({
				'gtm.start': new Date().getTime(),
				event: 'gtm.js'
			});
			var f = d.getElementsByTagName(s)[0],
				j = d.createElement(s),
				dl = l != 'dataLayer' ? '&l=' + l : '';
			j.async = true;
			j.src =
				'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
			f.parentNode.insertBefore(j, f);
		})(window, document, 'script', 'dataLayer', 'GTM-5DMXH48');
	</script>
	<!-- End Google Tag Manager -->
	<!-- Facebook Pixel Code -->
	<script>
		! function (f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function () {
				n.callMethod ?
					n.callMethod.apply(n, arguments) : n.queue.push(arguments)
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = !0;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = !0;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s)
		}(window, document, 'script',
			'https://connect.facebook.net/en_US/fbevents.js');
		fbq('init', '214326412412841');
		fbq('track', 'PageView');
	</script>
	<noscript><img height="1" width="1" style="display:none"
			src="https://www.facebook.com/tr?id=214326412412841&ev=PageView&noscript=1" /></noscript>
	<!-- End Facebook Pixel Code -->
</head>

<body>
	<!-- Google Tag Manager (noscript) -->
	<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5DMXH48" height="0" width="0"
			style="display:none;visibility:hidden"></iframe></noscript>
	<!-- End Google Tag Manager (noscript) -->

	<body
		class="page-template-default page page-id-125 wp-custom-logo wp-embed-responsive siteorigin-panels siteorigin-panels-before-js no-sidebar nav-float-right one-container header-aligned-left dropdown-hover"
		itemtype="https://schema.org/WebPage" itemscope>
		<a class="screen-reader-text skip-link" href="#content" title="Skip to content">Skip to content</a>
		<header id="masthead" class="site-header has-inline-mobile-toggle" itemtype="https://schema.org/WPHeader" itemscope>
			<div class="inside-header grid-container">
				<div class="site-logo">
					<a href="https://vocalchimp.com/" title="VocalChimp" rel="home">
						<img class="header-image is-logo-image" alt="VocalChimp"
							src="https://vocalchimp.com/wp-content/uploads/2021/05/cropped-vo-chimp-horizontal-150-e1622050143609.png"
							title="My blog" width="200" height="42" />
					</a>
				</div>
				<nav id="mobile-menu-control-wrapper" class="main-navigation mobile-menu-control-wrapper">
					<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false" data-nav="site-navigation">
						<span class="gp-icon icon-menu-bars"><svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
								xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em">
								<path
									d="M0 96c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24z" />
							</svg><svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
								xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em" height="1em">
								<path
									d="M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z" />
							</svg></span><span class="screen-reader-text">Menu</span> </button>
				</nav>
				<nav id="site-navigation" class="main-navigation sub-menu-right"
					itemtype="https://schema.org/SiteNavigationElement" itemscope>
					<div class="inside-navigation grid-container">
						<button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">
							<span class="gp-icon icon-menu-bars"><svg viewBox="0 0 512 512" aria-hidden="true" role="img"
									version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
									width="1em" height="1em">
									<path
										d="M0 96c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24zm0 160c0-13.255 10.745-24 24-24h464c13.255 0 24 10.745 24 24s-10.745 24-24 24H24c-13.255 0-24-10.745-24-24z" />
								</svg><svg viewBox="0 0 512 512" aria-hidden="true" role="img" version="1.1"
									xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="1em"
									height="1em">
									<path
										d="M71.029 71.029c9.373-9.372 24.569-9.372 33.942 0L256 222.059l151.029-151.03c9.373-9.372 24.569-9.372 33.942 0 9.372 9.373 9.372 24.569 0 33.942L289.941 256l151.03 151.029c9.372 9.373 9.372 24.569 0 33.942-9.373 9.372-24.569 9.372-33.942 0L256 289.941l-151.029 151.03c-9.373 9.372-24.569 9.372-33.942 0-9.372-9.373-9.372-24.569 0-33.942L222.059 256 71.029 104.971c-9.372-9.373-9.372-24.569 0-33.942z" />
								</svg></span><span class="mobile-menu">Menu</span> </button>
						<div id="primary-menu" class="main-nav">
							<ul id="menu-menu-1" class=" menu sf-menu">
								<li id="menu-item-232"
									class="menu-item menu-item-type-post_type menu-item-object-page menu-item-home menu-item-232"><a
										href="https://vocalchimp.com/">Home</a></li>
								<li id="menu-item-450" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-450"><a
										href="/auth/signin">Account Login</a></li>
								<li id="menu-item-539" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-539"><a
										href="/auth/signup">Sign Up</a></li>
								<li id="menu-item-234"
									class="menu-item menu-item-type-post_type menu-item-object-page current-menu-item page_item page-item-125 current_page_item menu-item-234">
									<a href="https://app.vocalchimp.com//home/pricing/" aria-current="page">Pricing</a></li>
								<li id="menu-item-233" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-233"><a
										href="https://vocalchimp.com/contact-us/">Contact Us</a></li>
							</ul>
						</div>
					</div>
				</nav>
			</div>
		</header>
		<style type="text/css">
			input[type=email],
			input[type=number],
			input[type=password],
			input[type=search],
			input[type=tel],
			input[type=text],
			input[type=url],
			select,
			textarea {
				border-radius: inherit;
				padding: inherit;
			}
		</style>
		<div id="page" class="site grid-container container mt-5">
			<div class="row">
				<div class="col-md-12">
					<?php my_load_view($this->setting->theme, 'Front/alert_part');?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 offset-1 card">

					<h3 class="py-3">Please enter your credit card details</h3>
					<form name="billingForm" method="post" id="payment-form" action="/user/create_subscription">
						<div class="group">
							<label for="name">
								<span>Your name</span>
								<div class="w-100" style="flex:1">
									<input type="text" id="name" placeholder="Please enter your name"
										<?= isset($name) ? "value='$name'" : '' ?> class="w-100 pl-2" />
								</div>
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
								<?php /* <input id="postal-code" name="postal_code" class="field" placeholder="ZIP CODE" required /> */ ?>
								<div id="zip-code-element" class="field"></div>
							</label>
							<span id="zip-code-error" role="alert" class="text-danger d-none"></span>

							<div id="promocode_div">
								<?php if(!isset($code)): ?>
								<div id="input-code">
									<label>
										<span>Discount / Promo code</span>
										<input id="promocode" name="promocode" class="field" placeholder="" />
									</label>
									<button id="validator" type="button" class="mb-2">Apply</button>
								</div>
								<?php endif; ?>
								<?php if(isset($code)): ?>
								<div class="d-flex justify-content-end">
									<p id="user-code">
										<span class="mr-3 mt-3">Code <b><?= $code ?></b> applied!</span>
										<button id="reset-code" type="button">Reset</button>
									</p>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<input type="hidden" name="temp_c_bc" id="temp_c_bc" value="<?php echo $c_bc; ?>">
						<button type="submit" id="submit-payment-btn">Subscribe</button>
					</form>
					<div class="outcome">
						<div class="error"></div>
						<div class="success">
							Success! Your Stripe token is <span class="token"></span>
						</div>
					</div>


					<style type="text/css">
						#user-code {
							display: table-cell;
							vertical-align: middle;
						}

						#validator,
						#reset-code {
							cursor: pointer;
							width: 62px;
							height: 32px;
							padding: 0;
							margin-top: 0 !important;
							float: right;
							margin-right: 5px;
						}
						#payment-form .text-danger{
							margin-left: 40%;
						}
					</style>


				</div>
				<div class="col-md-4">
					<div class="card">
						<div class="card-body text-center" id="selected_package">
							<h4>Package Details</h4>
							<br />
							<div style="background-color:#00a7d1;color: #ffffff;padding: 20px 0;"
								class="column-item column-item-header">
								<span style="font-size: 30px; font-weight:200;"><?=my_esc_html($pricing->item_name)?></span>
							</div>
							<div style="font-size:35px;color:#747474;font-weight:200; padding:20px 0px; position:relative"
								class="column-item column-item-price">
								<span class="price-value">$<?= $pricing->item_price ?></span>
								<?php if(isset($percent_off)): ?>
								<div
									style="height:2px;background-color: #000;transform: rotate(15deg);width: 110px;position: absolute;margin: 0 auto;top: 40px;left: 100px;">
								</div>
								<?php $newprice = $pricing->item_price * ((100 - $percent_off) / 100); ?>
								<p style="color:#000">$<?= $newprice ?></p>
								<?php endif; ?>
							</div>
							<div class="column-item-data">
								<?php
				$description_array = explode("\n", $pricing->item_description);
				$i = 0;
				foreach ($description_array as $description):
			?>
								<?php if ($i == 0): ?>
								<p><strong style="padding:5px 0px; font-size: 14px;"><?=my_esc_html($description)?></strong></p>
								<?php $i++;
				else: ?>
								<p style="font-size: 14px; font-size:200;border-bottom: 1px solid #e0e0e0;">
									<?=my_esc_html($description)?></p>
								<?php endif; endforeach;?>
							</div>
							<p class="text-right"><a href="/home/pricing" class="btn w-100"
									style="background-color: #FF006E; color: #ffffff;">Choose a Different Package
								</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script src="https://code.jquery.com/jquery-3.6.0.min.js"
			integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
		<script src="/assets/themes/default/vendor/blockui/jquery.blockUI.js"></script>
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

			label>span {
				width: 170px;
				text-align: right;
				margin-right: 30px;
			}

			label>span.brand {
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
			var base_url = '<?php echo current_url(); ?>';
			$(document).ready(function () {
				if ($('#user-code').length) {
					$('#input-code').hide();
				}
			});

			$(document).on('click','#reset-code', function () {
				$('#input-code').show();
				$('#user-code').hide();

				var href = window.location.href;
				if (href.toString().indexOf("c_bc") != -1) {
					$('#temp_c_bc').val('');
					history.pushState(null, '', base_url);
					$("#selected_package").load(location.href+" #selected_package>*","");
					$("#promocode_div").load(location.href+" #promocode_div>*","");
				}
			});

			function my_blockUI() {
				$.blockUI({
					overlayColor: '#000000',
					state: 'primary',
					message: '<p style="background-color: #ffffff;">Processing Payment...</p><img src="/assets/themes/default/img/loading.svg" alt="Processing..."/>',
					css: {
						border: "none",
						backgroundColor: "transparent"
					}
				});
			}
			
			$(document).on('click','#validator', function () {
				if ($('#promocode').val().trim().length > 0) {
					let pc = $('#promocode').val();
					my_blockUI();
					$.ajax({
						url: '/user/pay_promocode',
						data: 'pc=' + pc,
						success: function (res) {
							$.unblockUI();
							if (res == 'NULL') {
								alert('Promocode is invalid.');
								return false;
							} else {
								let url = window.location.href;
								if (url.indexOf('?c_bc=') > 0) {
									url = url.split('?')[0]
								}
								$('#temp_c_bc').val(res);
								history.pushState(null, '', base_url+'?c_bc='+ res);
								$("#selected_package").load(location.href+" #selected_package>*","");
								$("#promocode_div").load(location.href+" #promocode_div>*","");
							}
						},
						error: function () {
							$.unblockUI();
							alert('Please refresh the page and try again.');
						}
					});
				} else {
					alert('Enter a discount / promo code');
					return false;
				}
			});


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
		        //style: style,
		        showIcon: true
		    });
		    cardNumberElement.mount('#card-number-element');

		    var cardExpiryElement = elements.create('cardExpiry', {
		        //style: style
		    });
		    cardExpiryElement.mount('#card-expiry-element');

		    var cardCvcElement = elements.create('cardCvc', {
		        // style: style
		    });
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

			var form = document.getElementById('payment-form');
		    
		    form.addEventListener('submit', function(event) {
		        event.preventDefault();
		        if($("#payment-form .text-danger").text() != '')
		        {
		        	alert('Please enter valid card details');
		            return;
		        }
		        my_blockUI();
		        createToken();
		    });

			const btn = document.querySelector('#submit-payment-btn');

			function createToken() {
				stripe.createToken(cardNumberElement).then(function(result) {
					console.log(result);
					if (result.error) {
						// Inform the user if there was an error
						alert(result.error.message);
						$.unblockUI();
					} else {
						// Send the token to your server
						stripeTokenHandler(result.token);
					}
				});
			}

			function stripeTokenHandler(token) {
				btn.innerHTML = 'Processing payment. Please wait.';
				let promo_code = $('#temp_c_bc').val();
				$.ajax({
					url: '/user/create_subscription',
					method: 'POST',
					data: {
						token:token.id,
						items_id:'<?php echo $items_id;?>',
						c_bc:promo_code,
						'<?php echo $this->security->get_csrf_token_name(); ?>':'<?php echo $this->security->get_csrf_hash(); ?>'
					},
					dataType:'json',
					success: function (response) {
						if(response.error){
							btn.innerHTML = response.message;
							alert(response.message);
							$.unblockUI();
							btn.innerHTML = 'Subscribe';
						}else{
							
							btn.innerHTML = 'Activating your account. Please wait.';
							$.ajax({
								url: '/user/pay_success/'+response.data.subid+'?g=1'
							});
							$.ajax({
								url: '/user/updatecc',
								method: 'get',
								data: 'pm=' + response.data.payment_method + '&si='+response.data.subid,
								success: function (e) {
									setTimeout(function () {
										window.location.href = '/thankyou';
										$.unblockUI();
									}, 8000);
								},
								error:function(){
									$.unblockUI();
								}
							});
						}
					}
				});
			}
		</script>
		<style type="text/css">
			footer {
				background-color: #55555e;
				color: #ffffff;
				font-size: 15px;
				text-align: center;
			}
		</style>
		<footer class="p-3">VocalChimp.com</footer>
	</body>

</html>