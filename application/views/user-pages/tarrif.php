<?php
if($this->session->userdata('post')==null){
$tariff_master_id='';
$vehicle_model_id='';
$vehicle_ac_type_id='';
$customer_id='';
$from_date='';
$rate='';
$additional_kilometer_rate='';
$additional_hour_rate='';
$driver_bata='';
$night_halt='';
}
else
{
$data=$this->session->userdata('post');
$tariff_master_id=$data['tariff_master_id'];
$vehicle_model_id=$data['vehicle_model_id'];
$vehicle_ac_type_id=$data['vehicle_ac_type_id'];
$customer_id=$data['customer_id'];
$from_date=$data['from_date'];
$rate=$data['rate'];
$additional_kilometer_rate=$data['additional_kilometer_rate'];
$additional_hour_rate=$data['additional_hour_rate'];
$driver_bata=$data['driver_bata'];
$night_halt=$data['night_halt'];
$this->session->set_userdata('post','');
}
?>

<?php    if($this->session->userdata('dbSuccess') != '') { ?>
        <div class="success-message">
			
            <div class="alert alert-success alert-dismissable">
                <i class="fa fa-check"></i>
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <?php 
                echo $this->session->userdata('dbSuccess');
                $this->session->set_userdata(array('dbSuccess'=>''));
                ?>
           </div>
       </div>
       <?php    } ?>
<div class="tarrif_master_body">

<!--<legend class="body-head">Search</legend>
<table>
<tr>
<td><?php echo form_open(base_url()."organization/front-desk/tarrif"); 
 echo form_input(array('name'=>'search_from_date','class'=>'fromdatepicker form-control' ,'placeholder'=>' From Date')); ?>
</td>
<td><?php  echo form_input(array('name'=>'search_to_date','class'=>'fromdatepicker form-control' ,'placeholder'=>' To Date')); ?></td>
<td><?php echo form_submit("search","Search","class='btn btn-primary'");
echo form_close();?></td>
</tr>
</table>-->
	<p class="text-red"><?php
 if($this->session->userdata('Date') != ''){
	echo $this->session->userdata('Date');
	$this->session->set_userdata(array('Date'=>''));
 }
	?></p>
	<p class="text-red"><?php
 if($this->session->userdata('Err_from_date') != ''){
	echo $this->session->userdata('Err_from_date');
	$this->session->set_userdata(array('Err_from_date'=>''));
 }
	?></p>
	<p class="text-red"><?php
 if($this->session->userdata('Err_to_date') != ''){
	echo $this->session->userdata('Err_to_date');
	$this->session->set_userdata(array('Err_to_date'=>''));
 }
	?></p>

	

<fieldset class="body-border " >
<legend class="body-head">Add New Tariff</legend>
<div class="form-group">
<table>
<tr>
<td>
<div class="form-group">
<?php echo form_open(base_url()."tarrif/tarrif_manage");
		$class="form-control";
		$msg="Tariff Master";
		$name="select_tariff";
		$selected='';
echo $this->form_functions->populate_dropdown($name,$masters,$tariff_master_id,$class,$id='',$msg); 
?></div></td>
<td>
<div class="form-group">
<?php 
		$class="form-control";
		$msg="Vehicle Model";
		$name="vehicle_model";
		$selected='';
echo $this->form_functions->populate_dropdown($name,$vehicle_models,$vehicle_model_id,$class,$id='',$msg); 
?></div></td>
		<td>
<div class="form-group">
<?php 
		$class="form-control";
		$msg="Vehicle Ac Type";
		$name="vehicle_ac_type";
		$selected='';
echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,$vehicle_ac_type_id,$class,$id='',$msg); 
?></div></td>
		<td>
<div class="form-group">
<?php 
		$class="form-control";
		$msg="Customers";
		$name="customers";
		$selected='';
echo $this->form_functions->populate_dropdown($name,$customers,$customer_id,$class,$id='',$msg); 
?></div></td>
		
		<td><div class="form-group"><?php echo form_input(array('name'=>'fromdatepicker','class'=>'fromdatepicker form-control' ,'placeholder'=>' From Date','value'=>$from_date)); ?></div></td>
		<td><div class="form-group"><?php 
		if($rate==""){
		echo form_input(array('name'=>'rate','class'=>'form-control','id'=>'rate','placeholder'=>'Rate','value'=>$rate)); 
		}
		else
		{
		echo form_input(array('name'=>'rate','class'=>'form-control','id'=>'rate','placeholder'=>'Rate','value'=>number_format($rate,2))); 
		}?></div></td>
		<td><div class="form-group"><?php 
		if($additional_kilometer_rate==""){
		echo form_input(array('name'=>'additional_kilometer_rate','class'=>'form-control','id'=>'additional_kilometer_rate','placeholder'=>'Additional Kilometer Rate','value'=>$additional_kilometer_rate)); 
		}
		else{
		echo form_input(array('name'=>'additional_kilometer_rate','class'=>'form-control','id'=>'additional_kilometer_rate','placeholder'=>'Additional Kilometer Rate','value'=>number_format($additional_kilometer_rate,2))); 
		}
		?></div></td>
		<td><div class="form-group"><?php 
		if($additional_hour_rate==""){
		echo form_input(array('name'=>'additional_hour_rate','class'=>'form-control','id'=>'additional_hour_rate','placeholder'=>'Additional Hour Rate','value'=>$additional_hour_rate)); 
		}
		else{
		echo form_input(array('name'=>'additional_hour_rate','class'=>'form-control','id'=>'additional_hour_rate','placeholder'=>'Additional Hour Rate','value'=>number_format($additional_hour_rate,2))); 
		}?></div></td>
		<td><div class="form-group"><?php
		if($driver_bata==""){
			echo form_input(array('name'=>'driver_bata','class'=>'form-control','id'=>'driver_bata','placeholder'=>'Driver Bata','value'=>$driver_bata)); 
			}
			else{
			echo form_input(array('name'=>'driver_bata','class'=>'form-control','id'=>'driver_bata','placeholder'=>'Driver Bata','value'=>number_format($driver_bata,2))); 
			}?></div></td>
		<td><div class="form-group"><?php 
		if($night_halt==""){
		echo form_input(array('name'=>'night_halt','class'=>'form-control','id'=>'night_halt','placeholder'=>'Night Halt','value'=>$night_halt)); 
		}
		else{
		echo form_input(array('name'=>'night_halt','class'=>'form-control','id'=>'night_halt','placeholder'=>'Night Halt','value'=>number_format($night_halt,2))); 
		}?></div></td>
		<td><div  class="tarrif-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("tarrif-add","Add","id=tarrif-add-id","class=btn");?></div
	>
	</td>
		</tr>
		<tr>
		<td><p class="text-red"><?php
 if($this->session->userdata('select_tariff') != ''){
	echo $this->session->userdata('select_tariff');
	$this->session->set_userdata(array('select_tariff'=>''));
 }
	?></p></td>
		<td><p class="text-red"><?php
 if($this->session->userdata('vehicle_model') != ''){
	echo $this->session->userdata('vehicle_model');
	$this->session->set_userdata(array('vehicle_model'=>''));
 }
	?></p></td>
		<td><p class="text-red"><?php
 if($this->session->userdata('vehicle_ac_type') != ''){
	echo $this->session->userdata('vehicle_ac_type');
	$this->session->set_userdata(array('vehicle_ac_type'=>''));
 }
	?></p></td>
		<td></td>
		<td><?php echo  $this->form_functions->form_error_session('fromdatepicker','<p class="text-red">', '</p>');?>
		<p class="text-red"><?php
		if($this->session->userdata('Err_dt') != ''){
		echo $this->session->userdata('Err_dt');
		$this->session->set_userdata(array('Err_dt'=>''));
			}
		?></p>
		</td>
		<td><?php echo  $this->form_functions->form_error_session('rate','<p class="text-red">', '</p>');?></td>
		<td><?php  echo  $this->form_functions->form_error_session('additional_kilometer_rate','<p class="text-red">', '</p>'); ?></td>
		<td><?php echo  $this->form_functions->form_error_session('additional_hour_rate','<p class="text-red">', '</p>'); ?></td>
		<td><?php echo  $this->form_functions->form_error_session('driver_bata','<p class="text-red">', '</p>');?></td>
		<td><?php echo  $this->form_functions->form_error_session('night_halt','<p class="text-red">', '</p>'); ?></td>
		</tr>
</table>
<?php echo form_close();?>
</div>
</fieldset>
<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
<fieldset class="body-border ">
<legend class="body-head">Manage Tariff</legend>
<div class="float-right">
	<?php echo form_open(  base_url().'organization/front-desk/tariff-view');
		echo form_submit("view","View","class='btn btn-primary'");
		echo form_close(); 
	?>	
</div>

<?php echo br();?>
<table>
<tr>
<td><?php echo form_label('Tariff Master ','tariff_Master'); ?></td>
<td><?php echo form_label('Vehicle Models','v_models'); ?></td>
<td><?php echo form_label('Ac type','v_ac'); ?></td>
<td><?php echo form_label('Customer','customer_l'); ?></td>
<td><?php echo form_label('From Date','from_Date'); ?></td>
<td><?php echo form_label('Rate','rate'); ?></td>
<td><?php echo form_label('Ad KM Rate','additional_Kilometer_Rate'); ?></td>
<td><?php echo form_label('Ad Hr Rate','additional_Hour_Rate'); ?></td>
<td><?php echo form_label('Driver Bata','driver_Bata'); ?></td>
<td><?php echo form_label('Night Halt','night_Halt'); ?></td>

<td></td>
<td></td>
</tr>
<?php 
foreach($values as $det):
?>

<tr>
<td><div class="form-group"><?php echo form_open(base_url()."tarrif/tarrif_manage"); $class="form-control";
		$msg="Select Tariff Master";
		$name="manage_tariff";
		echo $this->form_functions->populate_dropdown($name,$masters,$det['tariff_master_id'],$class,$id='',$msg); ?></div>
</td>

<td><div class="form-group"><?php $class="form-control";
		$msg="Select Vehicle Models";
		$name="vehicle_model";
		echo $this->form_functions->populate_dropdown($name,$vehicle_models,$det['vehicle_model_id'],$class,$id='',$msg); ?></div>
</td>
<td><div class="form-group"><?php $class="form-control";
		$msg="Select Ac Type";
		$name="vehicle_ac_type";
		echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,$det['vehicle_ac_type_id'],$class,$id='',$msg); ?></div>
</td>
<td><div class="form-group"><?php $class="form-control";
		$msg="Customers";
		$name="customers";
		echo $this->form_functions->populate_dropdown($name,$customers,$det['customer_id'],$class,$id='',$msg); ?></div>
</td>

<td><div class="form-group"><?php echo form_input(array('name'=>'manage_datepicker','class'=>'fromdatepicker form-control' ,'placeholder'=>'Pick up From Date','value'=> $det['from_date'])); ?></div>
	 <div class="hide-me"><?php echo form_input(array('name'=>'h_dtpicker','class'=>'form-control','id'=>'h_dtpicker','value'=> $det['from_date'] ));?></div>
</td>

		<td><div class="form-group"><?php echo form_input(array('name'=>'manage_rate','class'=>'form-control','id'=>'rate','placeholder'=>'Rate','value'=> number_format($det['rate'],2))); ?></div></td>
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_additional_kilometer_rate','class'=>'form-control','id'=>'additional_kilometer_rate','placeholder'=>'Additional Kilometer Rate','value'=>number_format($det['additional_kilometer_rate'],2))); ?></div></td>
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_additional_hour_rate','class'=>'form-control','id'=>'additional_hour_rate','placeholder'=>'Additional Hour Rate','value'=> number_format($det['additional_hour_rate'],2))); ?></div></td>
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_driver_bata','class'=>'form-control','id'=>'driver_bata','placeholder'=>'Driver Bata','value'=> number_format($det['driver_bata'],2))); ?></div></td>
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_night_halt','class'=>'form-control','id'=>'night_halt','placeholder'=>'Night Halt','value'=>number_format($det['night_halt'],2))); ?>
           <div class="hide-me"><?php echo form_input(array('name'=>'manage_id','class'=>'form-control','id'=>'manage_id','value'=> $det['id'],'trigger'=>'true' ));?></div></td>
<td><div  class="tarrif-edit" ><?php echo nbs(5);?><i class="fa fa-edit cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me xx"><?php echo form_submit("edit","Edit","id=tarrif-edit-id","class=btn");?></div></td>
<td><div  class="tarrif-delete" ><?php echo nbs(5);?><i class="fa fa-trash-o cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("delete","Delete","id=tarrif-delete-id","class=btn");?></div></td>
<?php echo form_close();?>
</tr>

<?php endforeach; ?>
</table>
<?php echo $page_links;?>
</fieldset>
<?php } ?>
</div>
