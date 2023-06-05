<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php my_load_view($this->setting->theme, 'header')?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 text-left">
	  <h1 class="h3 mb-3 text-gray-800">Promo Codes</h1>
	</div>
  </div>
  <div class="row">
		<div class="col-lg-6 card shadow pr-4 ">
        <div class="card-body">
		  <?php
		  echo form_open(base_url('admin/promocode_action'), ['method'=>'POST', 'id'=>'promocode_add', 'name'=>'promocode_add']); ?>
		  <div class="form-group">
		  <label for="name"><b>CODE</b></label>
		  	<input type="text" name="promocode" class="form-control" id="name" placeholder="Eg. VOCAL30, XMAS25" />
		  </div>
		  <div class="form-group">
		  <label for="percentage"><b>PERCENTAGE OFF</b></label>
		  <input id="percentage" type="number" name="percent_off" class="form-control" />
		  </div>
<?php
$data = array(
'type' => 'submit',
'value' => 'Add Promocode',
'id' => 'promocode_add',
'class' => 'btn btn-primary mt-3'
);
echo '<div class="col-lg-6">' . form_submit($data) . '</div>';
echo form_close(); ?>
		 </div>
		</div>
		<style>.point85 { font-size:0.85rem; cursor:pointer; } .point85:hover { color: blue; } .row.pt-2 { border: 1px solid #c3c3c3; }</style>
	    <div class="col-lg-6 card shadow p-2">
		  <h6 class="pl-3 pt-2 font-weight-bold text-primary">Promocodes</h6>
        <div class="card-body">
		    <div class="row pt-2 pb-2 pl-1">
			<div class="col-8"><b>Code and Value</b></div>
			<div class="col-4"><b>Actions</b></div>
			</div>
			  <?php foreach($promocodes as $promocode): ?>
				<div class="row pt-2 pb-2 pl-1" data-id="<?= $promocode->couponid ?>" data-csrf="<?php echo $this->security->get_csrf_hash(); ?>">
				<div class="col-8"><code><?= $promocode->code ?></code> gives <code><?= $promocode->percent_off?>% OFF</code></div>
				<div class="col-4">
					<span class="point85 deletepromocode"><i class="fa fa-trash"></i> Delete</span>
				</div>
				<div class="col-12 card mt-2"></div>
				</div>
			  <?php endforeach; ?>
          </div>
      </div>
	</div>
		</div>
      </div>
  </div>
</div>
  
<?php my_load_view($this->setting->theme, 'footer')?>