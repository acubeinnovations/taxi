<?php
if($this->session->userdata('post')==null){
$title1='';
//$trip_model_id='';
//$vehicle_make_id='';
//$vehicle_ac_type_id='';
//$vehicle_type_id='';
$minimum_kilometers='';
$minimum_hours='';
}
else
{
$data=$this->session->userdata('post');
$title1=$data['title'];
//$trip_model_id=$data['trip_model_id'];
//$vehicle_type_id=$data['vehicle_type_id'];
//$vehicle_make_id=$data['vehicle_make_id'];
//$vehicle_ac_type_id=$data['vehicle_ac_type_id'];
$minimum_kilometers=$data['minimum_kilometers'];
$minimum_hours=$data['minimum_hours'];
$this->session->set_userdata('post','');
}

?>
	
<?php   if($this->session->userdata('dbSuccess') != '') { ?>
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
<fieldset class="body-border border-style" >
<legend class="body-head">Search</legend>
<table>
<tr>
<td><?php echo form_open(base_url()."organization/front-desk/tarrif-masters"); 
echo form_input(array('name'=>'search_title','class'=>'form-control','id'=>'title1','placeholder'=>'Title','value'=>'')); ?>
</td>
<!--<td><?php $class="form-control";
		$msg="Trip Model";
		$name="search_trip_model";
		echo $this->form_functions->populate_dropdown($name,$trip_models,$trip_model_id,$class,$id='model',$msg)?></td>

<td><?php  	$class="form-control";
		$msg=" AC Type";
		$name="search_ac_type";
		echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,$vehicle_ac_type_id,$class,$id='ac_type',$msg)?></td>-->
<td><?php echo form_submit("search","Search","class='btn btn-primary'");?></td>
<td><?php echo form_button('print-tariff','Print',"class='btn btn-primary print-tariff'"); 
echo form_close();?></td>

</tr>
</table>
<p class="text-red"><?php
 if($this->session->userdata('Required') != ''){
	echo $this->session->userdata('Required');
	$this->session->set_userdata(array('Required'=>''));
 }
	?></p>

</fieldset>
<fieldset class="body-border border-style" >
<legend class="body-head">Add Tariff Master</legend>
<div class="form-group">
<table>
<tr>
<td>
<?php echo form_open(base_url()."tarrif/tarrif_master_manage");
		echo form_input(array('name'=>'title','class'=>'form-control','id'=>'title1','placeholder'=>'Title','value'=>$title1)); ?>
		</td>
		<!--<td><?php  	$class="form-control";
		$msg="Trip Model";
		$name="select_trip_model";
		echo $this->form_functions->populate_dropdown($name,$trip_models,$trip_model_id,$class,$id='',$msg)?>
			
		</td>
		<td><?php $class="form-control";
		$msg="Vehicle Type";
		$name="search_vehicle_type";
		echo $this->form_functions->populate_dropdown($name,$vehicle_types,$vehicle_type_id,$class,$id='',$msg)?>
			
		</td>
		<td><?php  	$class="form-control";
		$msg="Vehicle Make";
		$name="select_vehicle_makes";
		echo $this->form_functions->populate_dropdown($name,$vehicle_makes,$vehicle_make_id,$class,$id='',$msg)?>
			
		</td>
		<td><?php  	$class="form-control";
		$msg="AC Type";
		$name="select_ac_type";
		echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,$vehicle_ac_type_id,$class,$id='',$msg)?>
		   
		</td>-->
		<td><?php echo form_input(array('name'=>'min_kilo','class'=>'form-control','id'=>'min_kilo','placeholder'=>'Minimum Kilometers','value'=>$minimum_kilometers)); ?></td>
		<td><?php echo form_input(array('name'=>'min_hours','class'=>'form-control','id'=>'min_hours','placeholder'=>'Minimum Hours','value'=>$minimum_hours)); ?></td>
		<td><div  class="tarrif-add" ><?php echo nbs(5);?><i class="fa fa-plus-circle cursor-pointer"></i><?php echo nbs(5);?></div><div class="hide-me"><?php echo form_submit("add","Add","id=tarrif-add-id","class=btn");?></div
	>
	</td>
		</tr>
		<tr>
		<td><?php echo  $this->form_functions->form_error_session('title','<p class="text-red">', '</p>');?>
		<p class="text-red"><?php
		if($this->session->userdata('Err_title') != ''){
		echo $this->session->userdata('Err_title');
		$this->session->set_userdata(array('Err_title'=>''));
		}
		?></p>
		</td>
		<td><p class="text-red"><?php
			if($this->session->userdata('select_trip_model') != ''){
			echo $this->session->userdata('select_trip_model');
			$this->session->set_userdata(array('select_trip_model'=>''));
				}
			?></p></td>
		<td><p class="text-red"><?php
			if($this->session->userdata('select_vehicle_type') != ''){
			echo $this->session->userdata('select_vehicle_type');
			$this->session->set_userdata(array('select_vehicle_type'=>''));
				}
			?></p></td>
		<td><p class="text-red"><?php
			if($this->session->userdata('select_vehicle_makes') != ''){
			echo $this->session->userdata('select_vehicle_makes');
			$this->session->set_userdata(array('select_vehicle_makes'=>''));
				}
			?></p></td>
		<td><p class="text-red"><?php
			if($this->session->userdata('select_ac_type') != ''){
			echo $this->session->userdata('select_ac_type');
			$this->session->set_userdata(array('select_ac_type'=>''));
				}
			?></p></td>
		<td><?php echo  $this->form_functions->form_error_session('min_kilo','<p class="text-red">', '</p>');?>
			<p class="text-red"><?php
			if($this->session->userdata('Err_kilo') != ''){
			echo $this->session->userdata('Err_kilo');
			$this->session->set_userdata(array('Err_kilo'=>''));
				}
			?></p>
		</td>
		<td><?php echo  $this->form_functions->form_error_session('min_hours','<p class="text-red">', '</p>');?>
			<p class="text-red"><?php
			if($this->session->userdata('Err_hrs') != ''){
			echo $this->session->userdata('Err_hrs');
			$this->session->set_userdata(array('Err_hrs'=>''));
				}
			?></p>
		</td>
		</tr>
</table>
</div>
</fieldset>
<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>

<fieldset class="body-border border-style">
<legend class="body-head">Manage</legend>
<?php echo br();?>
<table>
<tr>
<td><?php echo form_label('Master Title ','master_Title'); ?></td>
<!--<td><?php echo form_label('Trip Model','trip_Model'); ?></td>
<td><?php echo form_label('Vehicle Types','vehicle_types'); ?></td>
<td><?php echo form_label('Vehicle Makes','vehicle_Makes'); ?></td>
<td><?php echo form_label('Ac Type','ac_Type'); ?></td>-->
<td><?php echo form_label('Min Kilo','min_Kilometers'); ?></td>
<td><?php echo form_label('Min Hrs','min_Hours'); ?></td>

<td></td>
<td></td>
</tr>
<?php 
foreach($values as $det):
?>

<tr>
<td><div class="form-group"><?php echo form_open(base_url()."tarrif/tarrif_master_manage"); echo form_input(array('name'=>'manage_title','class'=>'form-control','id'=>'manage_title','placeholder'=>'Title','value'=> $det['title'] )); ?></div></td>
<!--<td><div class="form-group"><?php 
$class="form-control";
		$msg="Select Trip Model";
		$name="manage_select_trip_model";
		$selected='';
		echo $this->form_functions->populate_dropdown($name,$trip_models,$det['trip_model_id'],$class,$id='',$msg)?></div></td>
		<td><div class="form-group"><?php 
$class="form-control";
		$msg="Select Vehicle Type";
		$name="manage_select_vehicle_type";
		$selected='';
		echo $this->form_functions->populate_dropdown($name,$vehicle_types,$det['vehicle_type_id'],$class,$id='',$msg)?></div></td>
<td><div class="form-group"><?php  	$class="form-control";
		$msg="Select Vehicle Make";
		$name="manage_select_vehicle_makes";
		echo $this->form_functions->populate_dropdown($name,$vehicle_makes,$det['vehicle_make_id'],$class,$id='',$msg)?></div></td>
<td><div class="form-group"><?php  	$class="form-control";
		$msg="Select AC Type";
		$name="manage_select_ac_type";
		echo $this->form_functions->populate_dropdown($name,$vehicle_ac_types,$det['vehicle_ac_type_id'],$class,$id='',$msg)?></div></td>-->
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_min_kilo','class'=>'form-control','id'=>'manage_min_kilo','placeholder'=>'Minimum Kilometers','value'=> $det['minimum_kilometers'])); ?></div></td>
<td><div class="form-group"><?php echo form_input(array('name'=>'manage_min_hours','class'=>'form-control','id'=>'min_hours','placeholder'=>'Minimum Hours','value'=> $det['minimum_hours'] )); ?>
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
