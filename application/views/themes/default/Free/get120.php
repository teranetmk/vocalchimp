<div class="card mx-auto my-auto">
		<div class="card-body">
	<div class="row">
	<div class="col-md-12">
	<h2 class="blue">TRY VOCALCHIMP FOR FREE!</h2>
	<?php echo form_open(base_url('free/get120_action/'), ['method'=>'POST', 'id'=>'get120', 'name'=>'get120']); ?>
		<p class="blue"><b>Enter your email and start creating voiceovers!</b></p>
		<div class="form-group">
		<label for="email">Please enter your email address
		</label>
		<input type="email" name="email" id="email" class="form-control" required="required" />
		</div>
		<div class="form-group">
			<input type="submit" value="Start Using VocalChimp!" class="btn btn-primary w-100" id="freetrialbox" />
		</div>
		<input type="checkbox" id="tos" required="required" />
        <?php
        $policyURL = 'https://vocalchimp.com/privacy-policy/'; ?>
		<label for="tos">I agree with the <a href="<?php echo $policyURL;//site_url('privacy-policy');?>" target="_blank">terms and conditions</a> set forth for VocalChimp.com</label>
	<?php echo form_close(); ?>
	</div>
</div>
</div>
</div>