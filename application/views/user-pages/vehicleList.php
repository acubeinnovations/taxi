<?php    if($this->session->userdata('dbSuccess') != '') { 
?>

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
	  <?php 
	  //search?>

<div class="page-outer">    
	<fieldset class="body-border">
		<legend class="body-head">List Vehicles</legend><?php //print_r($vehicles);exit; ?>
		<div class="box-body table-responsive no-padding">
			<?php echo form_open(base_url().'organization/front-desk/list-vehicle');?>
			<table class="table list-org-table">
				<tbody>
					<tr>
					    <td><?php echo form_input(array('name'=>'reg_num','class'=>'form-control','id'=>'reg_num','placeholder'=>'By Registration Number','size'=>30,'value'=>$reg_num));?> </td>
						 <td><?php $class="form-control";
						 $id="vehicle-owner";
						 if(isset($owner)){
							  $owner=$owner;
							  }
							  else{
							   $owner='';
							  }
						echo $this->form_functions->populate_dropdown('owner',$vehicle_owners,$owner,$class,$id,$msg='Vehicle Owner')?> </td>
						<!--<td><?php// $class="form-control";
						//echo $this->form_functions->populate_dropdown('v_type',$vehicle_types,$selected='',$class,$id='',$msg='Select Vehicle Type')?></td>-->
						<td><?php $class="form-control";
						 $id="vehicle-model";
						 if(isset($v_model)){
							  $v_model=$v_model;
							  }
							  else{
							   $v_model='';
							  }
						echo $this->form_functions->populate_dropdown('v_model',$vehicle_models,$v_model,$class,$id,$msg='Vehicle Model')?></td>
						 <td><?php $class="form-control";
						  $id="vehicle-ownership";
						   if(isset($ownership)){
							  $ownership=$ownership;
							  }
							  else{
							   $ownership='';
							  }
						echo $this->form_functions->populate_dropdown('ownership',$vehicle_ownership_types,$ownership,$class,$id,$msg='Vehicle Ownership')?> </td>
					    <td><?php $class="form-control";
							  $id='supplier';
						echo $this->form_functions->populate_dropdown('supplier',$supplier_groups,@$supplier,$class,$id,$msg="Supplier Group");?> </td>
					    <td><?php $class="form-control";
							  $id='status';
							  $status[0]='Available';
							  $status[1]='On-Trip';
							  if(isset($status_id)){
							  $status_id=$status_id;
							  }
							  else{
							   $status_id='';
							  }
						echo $this->form_functions->populate_dropdown('status',$status,$status_id,$class,$id,$msg="Status");?> </td>
						<td><?php echo form_submit("search","Search","class='btn btn-primary'");?></td>
					    <?php echo form_close();?>
						
						<td><?php echo form_close(); ?></td>
						
						<td><?php echo form_open( base_url().'organization/front-desk/vehicle');
								  echo form_submit("add","Add","class='btn btn-primary'");
								  echo form_close(); 
						?></td>
						<td><?php echo form_button('print-vehicle','Print',"class='btn btn-primary print-vehicle'"); ?></td>
					</tr>
				</tbody>
			</table>
			<div class="msg"> <?php 
			if (isset($result)){ echo $result;} else {?></div>
		</div>
		<div class="box-body table-responsive no-padding ">
			<table class="table table-hover table-bordered table-with-20-percent-td">
				<tbody>
					<tr>
					    <th>Registration Number </th>
						<th>Contact Details</th>
						<th>Driver Details</th>
						<th>Current Status</th>
						<th>Account Statement</th>
					    
					</tr>
					<?php
					$attributes=array('class'=>'label-font-style');
					if(isset($values)){  //print_r($values);exit;
					foreach ($values as $det): 
					
				
					?>
					<tr> 
					    <td><?php  echo anchor(base_url().'organization/front-desk/vehicle/'.$det['id'],$vehicles[$det['id']]['registration_number']).br();
						if( !isset($vehicle_models[$vehicles[$det['id']]['model_id']]) || $vehicle_models[$vehicles[$det['id']]['model_id']]==''){ echo '';}else{echo $vehicle_models[$vehicles[$det['id']]['model_id']].br();}
						if( !isset($vehicle_makes[$vehicles[$det['id']]['make_id']]) || $vehicle_makes[$vehicles[$det['id']]['make_id']]==''){ echo '';}else{echo $vehicle_makes[$vehicles[$det['id']]['make_id']].br();}?></td>
						<td><?php  if( !isset($owners[$det['id']]['name']) || $owners[$det['id']]['name']==''){ echo '';}else{echo $owners[$det['id']]['name'].br();}?>
						<?php if( !isset($owners[$det['id']]['mobile']) || $owners[$det['id']]['mobile']==''){ echo '';}else{echo $owners[$det['id']]['mobile'].br();}?>
						<?php if( !isset($owners[$det['id']]['address']) || $owners[$det['id']]['address']==''){ echo '';}else{echo $owners[$det['id']]['address'].br();}
						if( !isset($supplier_groups[$vehicles[$det['id']]['supplier_group_id']]) || $supplier_groups[$vehicles[$det['id']]['supplier_group_id']]==''){ echo '';}else{echo $supplier_groups[$vehicles[$det['id']]['supplier_group_id']].br();}
						?></td>
						<td><?php if(!isset($drivers[$det['id']]['driver_name']) || $drivers[$det['id']]['driver_name']==''){ echo '';}else{echo $drivers[$det['id']]['driver_name'].br();}
						if(!isset($drivers[$det['id']]['mobile']) || $drivers[$det['id']]['mobile']==''){ echo '';}else{echo $drivers[$det['id']]['mobile'].br();}
						if(!isset($drivers[$det['id']]['from_date']) || $drivers[$det['id']]['from_date']==''){ echo '';}else{echo $drivers[$det['id']]['from_date']; } ?></td>
						<td><?php if($vehicle_statuses[$det['id']]!='Available'){ echo '<span class="label label-info">'.$vehicle_statuses[$det['id']].'</span>'.br(); }else{ echo '<span class="label label-success">'.$vehicle_statuses[$det['id']].'</span>'.br(); } if($vehicle_trips[$det['id']]!=gINVALID){ echo anchor(base_url().'organization/front-desk/trip-booking/'.$vehicle_trips[$det['id']],'Trip ID :'.$vehicle_trips[$det['id']]); } else{ echo ''; } ?>
						<?php echo br().form_label('Permit','permit',$attributes).nbs(2);?>: <span><?php if( !isset($vehicles[$det['id']]['vehicle_permit_renewal_date']) || $vehicles[$det['id']]['vehicle_permit_renewal_date']==''){ echo '';}else{echo $vehicles[$det['id']]['vehicle_permit_renewal_date'];}?></span>
						<?php echo br().form_label('Tax','tax',$attributes).nbs(2);?>: <span><?php if( !isset($vehicles[$det['id']]['tax_renewal_date']) || $vehicles[$det['id']]['tax_renewal_date']==''){ echo '';}else{echo $vehicles[$det['id']]['tax_renewal_date'];}?></span>
						<?php echo br().form_label('Insurance','insurance',$attributes).nbs(2);?>: <span><?php if( !isset($vehicles[$det['id']]['insurance_renewal_date']) || $vehicles[$det['id']]['insurance_renewal_date']==''){ echo '';}else{echo $vehicles[$det['id']]['insurance_renewal_date'];}?></span>
						</td>
						<td><div>
						<?php echo form_label('Total Trips','total_trips',$attributes).nbs(2);?> :
						<?php echo br().form_label('Outstanding','outstanding',$attributes).nbs(2);?> :
						<?php echo  br().form_label('Trip Advance','trip_advance',$attributes).nbs(2);?> :
						<?php echo br().form_label('Current Balance','current_balance',$attributes).nbs(2);?> :
						</div></td>
					
					    	
						
					</tr>
					<?php endforeach;
					}
					?>
				</tbody>
			</table><?php echo $page_links;?>
		</div>
		<?php }?>
	</fieldset>
	<?php echo form_close(); ?>
</div>
