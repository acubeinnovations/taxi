<?php


?>


<div class="trips-booking-div">

<div class="box">
    <div class="box-body">
<div class="trip-booking-body">
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
       <?php    }else if($this->session->userdata('dbError') != ''){ ?>
	<div class="alert alert-danger alert-dismissable">
        <i class="fa fa-ban"></i>
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
         <b>Alert!</b><br><?php
		echo $this->session->userdata('dbError').br();
	?>
      </div> 
	<?php    } ?>	
	<div class="first-column-trip-booking">
		<?php	
		$attributes = array('autocomplete'=>'off','id'=>'trip-form');
		 echo form_open(base_url().'trip-booking/book-trip',$attributes);?>
		<fieldset class="body-border">
		<legend class="body-head ">Trip Booking</legend>
			<div class="inner-first-column-trip-booking div-with-50-percent-width-with-margin-10">
				<?php if($booking_by=='customer'){ 
					$booking_source='';

					}else{
				?>
				<div class="booking-source">
					<fieldset class="body-border">
					<legend class="body-head font-size-18-px">Booking Source</legend>
						<div class="form-group">
						<?php $class="form-control row-source-50-percent-width-with-margin-8";
						echo $this->form_functions->populate_dropdown('booking_source',$booking_sources,$booking_source,$class,$id='',$msg="Source");?><?php
						echo form_input(array('name'=>'source','class'=>'form-control row-source-50-percent-width-with-margin-8','id'=>'source','placeholder'=>'Source','value'=>$source)); ?>
						
						<?php echo $this->form_functions->form_error_session('booking_sources', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('source', '<p class="text-red">', '</p>'); ?>
						</div>
						
					</fieldset>
				</div>
				<?php } ?>
				<div class="booking-source">
					<fieldset class="body-border">
					<?php if($booking_by == 'customer'){?>
					<legend class="body-head font-size-18-px">Guest Information</legend>
					<?php }else{?>
					<legend class="body-head font-size-18-px">Customer Information</legend>
					<?php } ?>
					<table>
					<?php if($booking_by == 'customer'){?>
							<tr>
							<td><?php echo form_hidden('mobile',$mobile).br();?>
					<div class="hide-me"><?php echo form_input(array('name'=>'new_customer','class'=>'form-control new-customer','value'=>$new_customer)); ?></div>		
							</td>
							<td><?php echo form_hidden('email',$email);echo form_hidden('customer',$customer);echo form_hidden('new_customer',$new_customer).br();?></td>
							</tr>	
							<?php }else{?>
							
							
						<tr>
							<td>
							<div class="div-with-90-percent-width-and-marigin-5 passenger-basic-info">
								<div class="form-group">
								<?php 
								echo form_input(array('name'=>'customer','class'=>'form-control mandatory', 'id'=>'customer','placeholder'=>'Customer','value'=>$customer)).form_label('','name_error').$this->form_functions->form_error_session('customer', '<p class="text-red">', '</p>');

								 ?>
	
		<ul class="autofill autofill-customer"></ul>
				
								<div class="hide-me"><?php echo form_input(array('name'=>'new_customer','class'=>'form-control new-customer','value'=>$new_customer)); ?></div>
								</div>
								<div class="form-group margin-top-less-10">
								<?php 
								echo form_input(array('name'=>'email', 'class'=>'form-control col-1-textbox-with-50-percent-width-and-float-left','id'=>'email','placeholder'=>'Email','value'=>$email));
								echo form_input(array('name'=>'mobile','class'=>'form-control col-2-textbox-with-50-percent-width-and-float-left mandatory','id'=>'mobile','placeholder'=>'Mobile','value'=>$mobile)).br().form_label('','email_error').nbs(61).form_label('','mobile_error');
								?>
								<span class="width-50-percent margin-top-less-10 float-left">
								<?php echo $this->form_functions->form_error_session('email', '<p class="text-red">', '</p>').nbs(8);?>
								</span><span class="width-50-percent margin-top-less-20 float-left">
								<?php echo $this->form_functions->form_error_session('mobile', '<p class="text-red">', '</p>');
								 ?>
								</span>
								</div>
							</div>
							</td>
							<td>
								
							</td>
						</tr>
						<?php }?>
						<tr>
							<td>
								<div class="form-group advanced-container margin-top-less-20">
									<?php
									echo form_checkbox(array('name'=>'advanced','class'=>'advanced-chek-box flat-red','checked'=>$advanced));
									echo nbs(4).form_label('Advanced');
									?>
								</div>
								
								<div class="form-group guest-container margin-top-less-40">
									<?php
									echo form_checkbox(array('name'=> 'guest','class'=>'guest-chek-box flat-red','checked'=>$guest));
									echo nbs(4).form_label('Guest');
									?>
								</div>
								<div class="form-group margin-top-less-20 customer-button-group  float-right">
									<button class="btn btn-info btn-lg add-customer" type="button">ADD</button>
									<button class="btn btn-danger btn-lg clear-customer" type="button">CLEAR</button>
								</div>
							</td>
							<td>
							</td>
						</tr>
						<tr>
							<td>
								<div class="group-toggle div-with-90-percent-width-and-marigin-5">
										<?php echo $this->form_functions->populate_dropdown('customer_group',$customer_groups,$customer_group,$class ='groups form-control',$id='customer-group',$msg="Groups"); ?>
										<?php echo $this->form_functions->form_error_session('customer_group', '<p class="text-red">', '</p>');?>
								</div>
							
							</td>
						</tr>
						<tr>
							<td>
								<div class="guest-toggle div-with-90-percent-width-and-marigin-5">
									<div class="form-group"> 
										<?php   
										echo form_input(array('name'=>'guestname','class'=>'form-control','id'=>'guestname','placeholder'=>'Guest','value'=>$guestname));
										 ?>
										<?php echo $this->form_functions->form_error_session('guestname', '<p class="text-red">', '</p>');?>
										</div>
										<div class="form-group margin-top-less-10">
										<?php 
										echo form_input(array('name'=>'guestemail','class'=>'form-control col-1-textbox-with-50-percent-width-and-float-left','id'=>'guestemail','placeholder'=>'Email','value'=>$guestemail));
										echo form_input(array('name'=>'guestmobile','class'=>'form-control col-2-textbox-with-50-percent-width-and-float-left','id'=>'guestmobile','placeholder'=>'Mobile','value'=>$guestmobile));
										 echo $this->form_functions->form_error_session('guestemail', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('guestmobile', '<p class="text-red">', '</p>');
										?>
										
									</div>
								</div>
							</td>
							<td>
								<button type="button" class="btn btn-danger btn-lg clear-guest">CLEAR</button>
							</td>
						</tr>
					</table>
					</fieldset>
				</div>
				<div class="booking-source">
					<fieldset class="body-border">
					<legend class="body-head font-size-18-px">Booking Information</legend>
						<div class="div-with-90-percent-width-and-marigin-5">
							<table>
								<tr>
									<td>
									
									<div class="form-group">
										<?php $class="form-control row-source-50-percent-width-with-margin-8";
										if($booking_by == 'customer') 
											form_hidden('trip_model',$trip_model);
										else
										echo $this->form_functions->populate_dropdown('trip_model',$trip_models,$trip_model,$class,$id='',$msg="Trip"); 
										echo form_input(array('name'=>'no_of_passengers','class'=>'form-control row-source-50-percent-width-with-margin-8','id'=>'no_of_passengers','placeholder'=>'No of passengers','value'=>$no_of_passengers)).br(2);?>
									<?php echo $this->form_functions->form_error_session('trip_models', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('no_of_passengers', '<p class="text-red">', '</p>');?>
									</div>
									<div class="form-group">
								
                                        <div class="input-group-btn ">
                                            <?php 
									echo form_input(array('name'=>'pickupcity','class'=>'bold form-control width-96-percent-and-margin-8 dropdown-toggle mandatory','id'=>'pickupcity','placeholder'=>'Pick up City','value'=>$pickupcity));?><div class="hide-me"><?php echo form_input(array('name'=>'pickupcitylat','id'=>'pickupcitylat','value'=>$pickupcitylat)).form_input(array('name'=>'pickupcitylng','id'=>'pickupcitylng','value'=>$pickupcitylng));?></div><?php
									echo $this->form_functions->form_error_session('pickupcity', '<p class="text-red">', '</p>');
									 ?>
                                            <ul class="dropdown-menu dropdown-menu-on-key-press autofill-pickupcity">
                                                
                                            </ul>
                                        </div>
                                       
                                   
								 </div>
									
									</div>
									<!--<div class="form-group">
									<?php 
									echo form_input(array('name'=>'pickuparea','class'=>'form-control width-96-percent-and-margin-8','id'=>'pickuparea','placeholder'=>'Pick up Area','value'=>$pickuparea));
										echo $this->form_functions->form_error_session('pickuparea', '<p class="text-red">', '</p>');
									 ?>
									</div>-->
									<div class="form-group">
									<?php 
									echo form_textarea((array('name'=>'pickuplandmark','class'=>'form-control width-96-percent-and-margin-8','id'=>'pickuplandmark','placeholder'=>'Pickup Landmark','value'=>$pickuplandmark,'rows'=>'2')));
									echo $this->form_functions->form_error_session('pickuplandmark', '<p class="text-red">', '</p>');
									 ?>
									</div>
									<div class="toggle-via">
										<div class="form-group">
											  <div class="input-group-btn ">
										<?php 
										echo form_input(array('name'=>'viacity','class'=>'form-control width-96-percent-and-margin-8','id'=>'viacity','placeholder'=>'Via City','value'=>$viacity));?><div class="hide-me"><?php echo form_input(array('name'=>'viacitylat','id'=>'viacitylat','value'=>$viacitylat)).form_input(array('name'=>'viacitylng','id'=>'viacitylng','value'=>$viacitylng));?></div><?php
										echo $this->form_functions->form_error_session('viacity', '<p class="text-red">', '</p>');
										 ?>
												 <ul class="dropdown-menu dropdown-menu-on-key-press autofill-viacity">
                                                
                                          		  </ul>
                                        </div>
										</div>
									<!--	<div class="form-group">
										<?php 
										echo form_input(array('name'=>'viaarea','class'=>'form-control width-96-percent-and-margin-8' ,'id'=>'viaarea','placeholder'=>'Via Area','value'=>$viaarea));
										echo $this->form_functions->form_error_session('viaarea', '<p class="text-red">', '</p>');
										 ?>
										</div>-->
										<div class="form-group">
										<?php 
										echo form_textarea(array('name'=>'vialandmark','class'=>'form-control width-96-percent-and-margin-8','id'=>'vialandmark','placeholder'=>'Via Landmark','value'=>$vialandmark,'rows'=>'2'));
										echo $this->form_functions->form_error_session('vialandmark', '<p class="text-red">', '</p>');
										 ?>
										</div>
									</div>
									<div class="form-group">
										  <div class="input-group-btn ">
									<?php 
									echo form_input(array('name'=>'dropdownlocation','class'=>'bold form-control width-96-percent-and-margin-8 mandatory','id'=>'dropdownlocation','placeholder'=>'Drop Down City','value'=>$dropdownlocation));?><div class="hide-me"><?php echo form_input(array('name'=>'dropdownlocationlat','id'=>'dropdownlocationlat','value'=>$dropdownlocationlat)).form_input(array('name'=>'dropdownlocationlng','id'=>'dropdownlocationlng','value'=>$dropdownlocationlng));?></div><?php
									echo $this->form_functions->form_error_session('dropdownlocation', '<p class="text-red">', '</p>');
									 ?>
											 <ul class="dropdown-menu dropdown-menu-on-key-press autofill-dropdownlocation">
                                                
                                            </ul>
                                        </div>
									</div>
									<!--<div class="form-group">
									<?php 
									echo form_input(array('name'=>'dropdownarea','class'=>'form-control width-96-percent-and-margin-8' ,'id'=>'dropdownarea','placeholder'=>'Drop Down Area','value'=>$dropdownarea));
										echo $this->form_functions->form_error_session('dropdownarea', '<p class="text-red">', '</p>');
									 ?>
									</div>-->
									<div class="form-group">
									<?php 
							echo form_textarea((array('name'=>'dropdownlandmark','class'=>'form-control width-96-percent-and-margin-8','id'=>'dropdownlandmark','placeholder'=>'Drop Down Landmark','value'=>$dropdownlandmark,'rows'=>'2')));
										echo $this->form_functions->form_error_session('dropdownlandmark', '<p class="text-red">', '</p>');
									 ?>
									</div>
									<div class="form-group">
									<?php 
									echo form_input(array('name'=>'pickupdatepicker','class'=>'mandatory form-control width-60-percent-with-margin-10','id'=>'pickupdatepicker','placeholder'=>'Pick up Date','value'=>$pickupdatepicker)).form_input(array('name'=>'pickuptimepicker','class'=>'mandatory form-control width-30-percent-with-margin-left-20','id'=>'pickuptimepicker','placeholder'=>'Pick up time ','value'=>$pickuptimepicker));
									echo $this->form_functions->form_error_session('pickupdatepicker', '<p class="text-red float-left right-15">', '</p>').$this->form_functions->form_error_session('pickuptimepicker', '<p class="text-red float-left left-65">', '</p>');
									 ?>
									</div>
									<div class="form-group float-left">
									<?php 
									echo form_input(array('name'=>'dropdatepicker','class'=>'mandatory  form-control width-60-percent-with-margin-10','id'=>'dropdatepicker','placeholder'=>'Drop Date','value'=>$dropdatepicker)).form_input(array('name'=>'droptimepicker','class'=>'mandatory form-control width-30-percent-with-margin-left-20','id'=>'droptimepicker','placeholder'=>'Drop time','value'=>$droptimepicker));
									echo $this->form_functions->form_error_session('dropdatepicker', '<p class="text-red float-left right-15">', '</p>').$this->form_functions->form_error_session('droptimepicker', '<p class="text-red float-left left-65">', '</p>');
									 ?>
									</div>
									</td>
									<td>
									<?php echo anchor(base_url().$_SERVER['REQUEST_URI'].'#', 'Via','id="via"'); ?>
									</td>
								</tr>
							</table>
						</div>
					</fieldset>
				</div>
			</div>
			<div class="inner-second-column-trip-booking div-with-50-percent-width-with-margin-10">
				<a href="<?php echo base_url().'organization/front-desk/trips'?>" class="btn btn-primary trips-redirect btn-sm">TRIPS</a>
				<div class="booking-source">
					<fieldset class="body-border">
					<legend class="body-head font-size-18-px">Vehicle Information</legend>
						<div class="form-group">
						<?php /* $class="form-control row-source-50-percent-width-with-margin-8";
							  $id='vehicle-type';
						echo $this->form_functions->populate_dropdown('vehicle_type',$vehicle_types,$vehicle_type,$class,$id,$msg="Type");*/
								$class="form-control row-source-50-percent-width-with-margin-8 mandatory";
							  $id='vehicle-model';
						echo $this->form_functions->populate_dropdown('vehicle_model',$vehicle_models,$vehicle_model_id,$class,$id,$msg="Vehicle Models");
								$class="form-control row-source-50-percent-width-with-margin-8 mandatory";	
								$id='vehicle-ac-type';?>
					
								
						<?php echo $this->form_functions->populate_dropdown('vehicle_ac_type',$vehicle_ac_types,$vehicle_ac_type,$class,$id,$msg="AC/Non AC");
						/*echo $this->form_functions->form_error_session('vehicle_type', '<p class="text-red">', '</p>')*/ 
						//echo $this->form_functions->form_error_session('vehicle_model', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('vehicle_ac_type', '<p class="text-red">', '</p>');	?>					
					<span class="text-red"><?php
						if($this->mysession->get('Err_V_Ac') != ''){
							echo $this->mysession->get('Err_V_Ac');
							$this->mysession->delete('Err_V_Ac');
						} 
						echo nbs(7);
						if($this->mysession->get('Err_Vmodel') != ''){
							echo $this->mysession->get('Err_Vmodel');
							$this->mysession->delete('Err_Vmodel');
						 }
					?></span>
					
					 
					
						</div>

						<?php if($booking_by == 'customer') { ?>
						<div class ="hide-me"> <?php echo form_input(array('id'=>'tarrif','value'=>'-1'));?></div>
						<?php } else{?>
						<div class="form-group">
						<?php $class="form-control row-source-50-percent-width-with-margin-8 ";
						$id="tarrif"; 
						echo $this->form_functions->populate_dropdown('tariff',$tariffs,$tariff,$class,$id,$msg="Tariffs");
						$class="form-control row-source-50-percent-width-with-margin-8 vehicle-list";
						$id="available_vehicle";
						//echo $this->form_functions->populate_dropdown('available_vehicle',$available_vehicles,$available_vehicle,$class,$id,$msg="Available Vehicles");
						if($available_vehicle==gINVALID)
						{	
						$available_vehicle='';	
						}
						
						echo $this->form_functions->populate_editable_dropdown('available_vehicle', $available_vehicles,$class,'vehicles',array(),"Vehicle",$available_vehicle,$id);
						echo nbs(35);?><span class="text-red"><?php
						if($this->mysession->get('Err_reg_num') != ''){
							echo $this->mysession->get('Err_reg_num');
							$this->mysession->delete('Err_reg_num');
						} 
						
					?></span><?php
						echo br(2);
						?>
						<div class="hide-me vehicle-tarif-checker" tariff_id="<?php echo $tariff;?>" available_vehicle_id="<?php echo $available_vehicle;?>"></div>
						</div><?php }?>
						<!--<div class="form-group">
						<?php 
								$class="form-control row-source-50-percent-width-with-margin-8";	
								$id='vehicle-make';
						//echo $this->form_functions->populate_dropdown('vehicle_make',$vehicle_makes,$vehicle_make_id,$class,$id,$msg="Vehicle Makes");
						$class="form-control row-source-50-percent-width-with-margin-8";
							  $id='vehicle-model';
						echo $this->form_functions->populate_dropdown('vehicle_model',$vehicle_models,$vehicle_model_id,$class,$id,$msg="Vehicle Models");
						echo $this->form_functions->form_error_session('vehicle_make', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('vehicle_model', '<p class="text-red">', '</p>');						
						echo br(2);
						 ?>
						</div>-->
						
						<div class="form-group advanced-vehicle-container margin-top-less-20">
									<?php
									echo form_checkbox(array('name'=>'advanced_vehicle','class'=>'advanced-vehicle-chek-box flat-red','checked'=>$advanced_vehicle));
									echo nbs(4).form_label('Advanced');
									?>
						</div>
					
				<div class="group-vehicle-toggle div-with-90-percent-width-and-marigin-5">
				   <div class="form-group vehicle-advanced">
					<table class="radio-checkbox-vehicle-group">
						<tr>
							<td class="beacon-light-chk-box-container">
								<?php
									echo form_checkbox(array('name'=> 'beacon_light','class'=>'beacon-light-chek-box flat-red','checked'=>$beacon_light,'radio_to_be_selected'=>$beacon_light_radio));
								
									echo nbs(5).form_label('Beacon Light');
								?>	
								</td>
							<td>
								<span class="beacon-radio1-container">
								<?php
									echo nbs(25).form_radio(array('name' => 'beacon_light_radio','id' => 'beacon-light-radio1','value'=>'red'));
								
								    echo nbs(5).form_label('Red').nbs(15);?>
									</span>
									<span class="beacon-radio2-container"><?php
								    echo form_radio(array('name' => 'beacon_light_radio','id' => 'beacon-light-radio2','value'=>'blue'));
								
								echo nbs(5).form_label('Blue');
								?>
								</span>
							</td>
						</tr>
						<tr>
							<td>
								<?php
									echo form_checkbox(array('name'=> 'pluck_card','class'=>'pluckcard-chek-box flat-red','checked'=>$pluck_card));
								
									echo nbs(5).form_label('Placard');
								?>	
							</td>
							<td>
								<?php
									echo nbs(25).form_checkbox(array('name'=> 'uniform','class'=>'uniform-chek-box flat-red','checked'=>$uniform));
								
									echo nbs(5).form_label('Uniform');
								?>
							</td>
						</tr>
					</table>								
				    </div>
				    
				    <div class="form-group vehicle-advanced">
						<?php $class="form-control row-source-50-percent-width-with-margin-8 driver-list";
						echo $this->form_functions->populate_dropdown('seating_capacity',$vehicle_seating_capacity,$seating_capacity,$class,$id='',$msg="Seats");
						echo $this->form_functions->populate_dropdown('language',$languages,$language,$class,$id='',$msg="Languages");
						echo $this->form_functions->form_error_session('vehicle_seating_capacity', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('language', '<p class="text-red">', '</p>');
						 if($booking_by == 'customer') { ?>
						<div class ="hide-me"> <?php echo form_input(array('name'=>'driver_list','value'=>'-1'));?></div>
						<?php } else{
						//echo $this->form_functions->populate_dropdown('driver_list',$driver_availability,$available_driver,$class,$id='',$msg="Drivers");
						if($available_driver==gINVALID)
						{	
						$available_driver='';	
						}
						echo $this->form_functions->populate_editable_dropdown('driver_list', $driver_availability,$class,'drivers',array(),"Driver",$available_driver);
						}
						echo br(2);
						 ?>
				    </div>
			     </div>
						
						
						

						

					</fieldset>
				</div>
				<?php $count=count($reccurent_alternatives_pickupdatepicker);?>
				<div class="reccurent-lok">
					<div class="box">
	   					 <div class="box-body">
							<div class="reccurent-container" slider="<?php echo $count; ?>">
								<fieldset class="body-border ">
								<legend class="body-head font-size-18-px">Recurrent</legend>
									<div class="form-group float-right recurrent-yes-container">
											<?php
												echo form_checkbox(array('name'=> 'recurrent_yes','class'=>'recurrent-yes-chek-box flat-red','checked'=>$recurrent_yes,'radio_button_to_be_checked'=>$recurrent));
								
												echo nbs(5).form_label('Yes');
											?>
									</div>
									<div class="form-group float-right recurrent-radio-container">
									<div class="div-continues">
									<?php
												echo nbs(1).form_radio(array('name' => 'recurrent','id' => 'continues-recurrent','value'=>'continues','checked'=>$recurrent_continues));
								
												echo nbs(5).form_label('Continues').nbs(5);
												?></div> <div class="div-alternatives"><?php
												echo form_radio(array('name' => 'recurrent','id' => 'alternative-recurrent','value'=>'alternatives','checked'=>$recurrent_alternatives));
								
											echo nbs(5).form_label('Alternatives');
											?>
									</div>
									</div>
									<div class="recurrent-container-continues">
										<div class="form-group">
									
												<?php 
								
												echo form_input(array('name'=>'reccurent_continues_pickupdatepicker','class'=>'form-control width-60-percent-with-margin-10','id'=>'reccurent_continues_pickupdatepicker','placeholder'=>'Pick up Date ','value'=>$reccurent_continues_pickupdatepicker)).form_input(array('name'=>'reccurent_continues_pickuptimepicker','class'=>'form-control width-30-percent-with-margin-left-20','id'=>'reccurent_continues_pickuptimepicker','placeholder'=>'Pick up time ','value'=>$reccurent_continues_pickuptimepicker));
												echo br(3).$this->form_functions->form_error_session('reccurent_continues_pickupdatepicker', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('reccurent_continues_pickuptimepicker', '<p class="text-red">', '</p>');
												 ?>
									
											</div>
											<div class="form-group">
											<?php 
											echo form_input(array('name'=>'reccurent_continues_dropdatepicker','class'=>'form-control width-60-percent-with-margin-10','id'=>'reccurent_continues_dropdatepicker','placeholder'=>'Drop Date','value'=>$reccurent_continues_dropdatepicker)).form_input(array('name'=>'reccurent_continues_droptimepicker','class'=>'form-control width-30-percent-with-margin-left-20','id'=>'reccurent_continues_droptimepicker','placeholder'=>'Drop time ','value'=>$reccurent_continues_droptimepicker));
											echo $this->form_functions->form_error_session('reccurent_continues_dropdatepicker', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('reccurent_continues_droptimepicker', '<p class="text-red">', '</p>');
											 ?>
											</div>
										</div>
										<div class="recurrent-container-alternatives">
											<table class="alternative-table">
												<tr>
													<td class="width-80-percent">
														<div class="form-group">
														<?php 
														if(isset($reccurent_alternatives_pickupdatepicker[0]) && $reccurent_alternatives_pickupdatepicker[0]!=''){
														$alternative_pickupdatepicker=$reccurent_alternatives_pickupdatepicker[0];
														}
														if(isset($reccurent_alternatives_pickuptimepicker[0]) && $reccurent_alternatives_pickuptimepicker[0]!=''){
														$alternative_pickuptimepicker=$reccurent_alternatives_pickuptimepicker[0];
														}
											
														echo form_input(array('name'=>'reccurent_alternatives_pickupdatepicker[]','class'=>'form-control width-60-percent-with-margin-10','id'=>'reccurent_alternatives_pickupdatepicker0','placeholder'=>'Pick up Date and time ','value'=>$alternative_pickupdatepicker)).form_input(array('name'=>'reccurent_alternatives_pickuptimepicker[]','class'=>'form-control width-30-percent-with-margin-left-20','id'=>'reccurent_alternatives_pickuptimepicker0','placeholder'=>'Pick up time ','value'=>$alternative_pickuptimepicker));
														echo $this->form_functions->form_error_session('reccurent_alternatives_pickupdatepicker', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('reccurent_alternatives_pickuptimepicker', '<p class="text-red">', '</p>');
														 ?>
														</div>
								
														<div class="form-group">
														<?php 
														if(isset($reccurent_alternatives_dropdatepicker[0]) && $reccurent_alternatives_dropdatepicker[0]!=''){
														$alternative_dropdatepicker=$reccurent_alternatives_dropdatepicker[0];
														}
														if(isset($reccurent_alternatives_droptimepicker[0]) && $reccurent_alternatives_droptimepicker[0]!=''){
														$alternative_droptimepicker=$reccurent_alternatives_droptimepicker[0];
														}
											
														echo form_input(array('name'=>'reccurent_alternatives_dropdatepicker[]','class'=>'form-control width-60-percent-with-margin-10','id'=>'reccurent_alternatives_dropdatepicker0','placeholder'=>'Drop Date and time ','value'=>$alternative_dropdatepicker)).form_input(array('name'=>'reccurent_alternatives_droptimepicker[]','class'=>'form-control width-30-percent-with-margin-left-20','id'=>'reccurent_alternatives_droptimepicker0','placeholder'=>'Drop time ','value'=>$alternative_droptimepicker));
														echo $this->form_functions->form_error_session('reccurent_alternatives_dropdatepicker[]', '<p class="text-red">', '</p>').$this->form_functions->form_error_session('reccurent_alternatives_droptimepicker[]', '<p class="text-red">', '</p>');
														 ?>
														</div>
													</td>
													<td>
													<?php
													 if(count($reccurent_alternatives_dropdatepicker)==0){
														 $count=0;
													}else{
														$count=count($reccurent_alternatives_dropdatepicker);
													} 
													?>
														<div class="float-left margin-15"><a class="btn btn-info btn-lg add-reccurent-dates" count="<?php echo$count; ?>">ADD</a></div>
													</td>
												</tr>
											</table>
											<div class="new-reccurent-date-textbox reccurent-slider">
											<?php
											 if(count($reccurent_alternatives_dropdatepicker)>1){
												$count=count($reccurent_alternatives_dropdatepicker);
											for($date_time_index=1;$date_time_index<$count;$date_time_index++){
											?>
											<div class="form-group"><input name="reccurent_alternatives_pickupdatepicker[]" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_pickupdatepicker<?php echo $date_time_index; ?>" placeholder="Pick up Date" type="text" value="<?php echo $reccurent_alternatives_pickupdatepicker[$date_time_index]; ?>"><input name="reccurent_alternatives_pickuptimepicker[]" value="<?php echo $reccurent_alternatives_pickuptimepicker[$date_time_index]; ?>" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_pickuptimepicker<?php echo $date_time_index; ?>" placeholder="Pick up Time" type="text"></div><div class="form-group"><input name="reccurent_alternatives_dropdatepicker[]" value="<?php echo $reccurent_alternatives_dropdatepicker[$date_time_index]; ?>" class="form-control width-60-percent-with-margin-10" id="reccurent_alternatives_dropdatepicker<?php echo $date_time_index; ?>" placeholder="Drop Date" type="text"><input name="reccurent_alternatives_droptimepicker[]" value="<?php echo $reccurent_alternatives_droptimepicker[$date_time_index]; ?>" class="form-control width-30-percent-with-margin-left-20" id="reccurent_alternatives_droptimepicker<?php echo $date_time_index; ?>" placeholder="Drop time " type="text"></div>
											<?php

											}
				
											}								
											?>
								
											</div>
										</div>
							
								</fieldset>
							</div>
						</div><!-- /.box-body -->
	   					<?php if($trip_id!='' && $trip_id!=gINVALID){?>
						<div class='overlay-container' style="display:block !important;">
					   		<div class="overlay"></div>
							<div class="lok-img"></div>
						</div>
						<?php } ?>
						<!-- end loading -->
					</div>
				</div>	
				<div class="booking-source">
					<fieldset class="body-border">
					<legend class="body-head font-size-18-px">Rough Estimate</legend>
						<div class="box no-border-top rough-estimate-body">
                              <div class="box-body no-padding">
									<div class="float-right form-group customer-type-container hide-me">
										<?php $class="form-control customer-type"; 
											echo $this->form_functions->populate_dropdown('customer_type',$customer_types,$customer_type,$class,$id='',$msg="Select Customer type").br(4);?>
									</div>
                                    <table class="table table-striped rough-estimate-table">
                                        <tbody>
		                                    <tr>
		                                        <td class="wdith-30-percent">Total Time Of Journey<span class="float-right"> : </span></td>
		                                        <td><div class="estimated-time-of-journey"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'time_journey','class'=>'form-control ','id'=>'time_journey'));?></div>
												</td>
		                                        
		                                        
		                                    </tr>
		                                    <tr>
		                                        <td>Total Distance<span class="float-right"> : </span></td>
		                                        <td><div class="estimated-distance-of-journey"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'est_distance','class'=>'form-control ','id'=>'est_distance'));?></div></td>
		                                        
		                                    </tr>
											<tr>
		                                        <td>No of Days<span class="float-right"> : </span></td>
		                                        <td><div class="no-of-days"></div></td>
		                                        
		                                    </tr>
											<tr>
		                                        <td>Minimum Charge<span class="float-right"> : </span></td>
		                                        <td><div class="charge-per-km"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'charge','class'=>'form-control ','id'=>'charge'));?></div></td>
		                                        
		                                    </tr>
															<tr>
		                                        <td>Minimum Kilometers per Day<span class="float-right"> : </span></td>
		                                        <td><div class="mini-km"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'min_kilo','class'=>'form-control ','id'=>'min_kilo'));?></div></td>
		                                        
		                                    </tr>
											
											<tr>
		                                        <td>Additional KM<span class="float-right"> : </span></td>
		                                        <td><div class="additional-km"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'additional-km','class'=>'form-control ','id'=>'additional-km'));?></div></td>
		                                        
		                                    </tr>		
											<tr>
		                                        <td>Additional KM Rate<span class="float-right"> : </span></td>
		                                        <td><div class="additional-rate-per-km"></div>
												<!--<div class="hide-me"><?php //echo form_input(array('name'=>'additional-charge','class'=>'form-control ','id'=>'additional-charge'));?></div>--></td>
		                                        
		                                    </tr>
											
											<tr>
		                                        <td>Additional Charge<span class="float-right"> : </span></td>
		                                        <td><div class="additional-charge-per-km"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'additional-charge','class'=>'form-control ','id'=>'additional-charge'));?></div></td>
		                                        
		                                    </tr>
											<tr>
		                                        <td> Amount<span class="float-right"> : </span></td>
		                                        <td><div class="estimated-amount"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'amt','class'=>'form-control ','id'=>'amt'));?></div></td>
		                                        
		                                    </tr>
											<tr>
		                                        <td> Tax Payable<span class="float-right"> : </span></td>
		                                        <td><div class="tax-payable"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'tax','class'=>'form-control ','id'=>'tax'));?></div></td>
		                                        
		                                    </tr>
											
											<tr>
		                                        <td>Total Amount<span class="float-right"> : </span></td>
		                                        <td><div class="estimated-total-amount"></div>
												<div class="hide-me"><?php echo form_input(array('name'=>'tot_amt','class'=>'form-control ','id'=>'tot_amt'));?></div></td>
		                                        
		                                    </tr>
                                   		</tbody>
									</table>
                                </div><!-- /.box-body -->
                            </div>
					</fieldset>
				</div>
				<div class="booking-source">
					<fieldset class="body-border">
					<legend class="body-head font-size-18-px">Other Information</legend>

					<div class="form-group">
						<?php	$data = array(
							  'name'        => 'remarks',
							  'id'          => 'txt_area',
							  'value'       => $remarks,
							  'rows'        => '2',
							  'cols'        => '10',
							  'style'       => 'width:100%',
							);
						echo form_label('Remarks','remarks');
	  					echo form_textarea($data);?>
					</div>

					<div class="form-group width-40-percent">
						<?php	$data = array(
							  'name'        => 'advance_amount',
							  'id'          => 'advance_amount',
							  'value'       => $advance_amount,
							  'class'=>'form-control'
							);
						echo form_label('Advance Amount','advance_amount');
	  					echo form_input($data);?>
					</div>
					</fieldset>
				</div>

				<div class="form-submit-reset-buttons-group">
						<?php if(isset($trip_id) && $trip_id>0){
							$book_or_update_trip="UPDATE TRIP";
							}else{

								$book_or_update_trip="BOOK TRIP";
							}?>
						<button class="btn btn-success btn-lg book-trip-validate" type="button" enable_redirect='false'><?php echo $book_or_update_trip; ?></button>
						<div class="hide-me"><?php echo form_submit("book_trip","","class='btn btn-success btn-lg book-trip'").form_submit("cancel_trip","","class='btn btn-success btn-lg cancel-trip'");  ?> </div> 
						<?php if(isset($trip_id) && $trip_id>0){
							$cancel_or_reset="CANCEL TRIP";
							?>
							<button class="btn btn-danger btn-lg cancel-trip-validate" type="button"><?php echo $cancel_or_reset;?></button>
							
							<div class="hide-me">
			
								<?php echo form_input(array('name'=>'trip_id','value'=>$trip_id,'id'=>'trip_id')); ?>
								
								
							</div>
							<?php } else {
							$cancel_or_reset="RESET";
							echo form_reset("reset_trip",$cancel_or_reset,"class='btn btn-danger btn-lg reset-trip'"); 
							}

	
						 ?> 
							<div class="hide-me">
							<?php 
								echo form_input(array('name'=>'guest_id','value'=>$guest_id));
							?>
								</div>
				
				</div>
			
			</div>
			<div class="row-source-full-100-percent-width-with-margin-8">
			</div>
		</fieldset>
		 <?php echo form_close(); ?>
	</div>
	<div class="second-column-trip-booking">
		<fieldset class="body-border notify">
		<legend class="body-head ">Notification</legend>
		<div class="ajax-notifications">
		<?php
		
		if(count($notification)>0 && $notification!=''){
		for($notification_index=0;$notification_index<count($notification);$notification_index++){?>
		<a href="<?php echo base_url().'organization/front-desk/trip-booking/'.$notification[$notification_index]->id;?>" class="notify-link">
		<div class="callout callout-warning no-right-padding">
		<div class="notification<?php echo $notification_index; ?>">
			<table style="width:100%;" class="font-size-12-px">
				<tr>
					<td class='notification-trip-id'>
						Trip ID :
					</td>
					<td>
						<?php echo $notification[$notification_index]->id; ?>
					</td>
				</tr>
				<tr>
					<td class='notification-pickup-city'>
						Cust :
					</td>
					<td>
						<?php echo $customers_array[$notification[$notification_index]->customer_id]; ?>
					</td>
				</tr>
				<tr>
					<td class='notification-trip-id'>
						Pick up :
					</td>
					<td>
						<?php echo $notification[$notification_index]->pick_up_city; ?>
					</td>
				</tr>
				<tr>
					<td class='notification-pickup-city'>
					Date :</td><td><?php echo $notification[$notification_index]->pick_up_date; ?>
					</td>
				</tr>
			</table>
		</div>
		</div>
		</a>
		<?php }


		}


		?>
		</div>
		</fieldset>
	</div>
</div>

</div><!-- /.box-body -->
   
	<div class='overlay-container display-me'>
   		<div class="overlay"></div>
		<div class="loading-img"></div>
	</div>
    <!-- end loading -->
</div>	
</div>

<div class="hide-me">
<div class="vehicle-makes">
<?php
	$i=0;
 foreach ($vehicle_makes as $value) {
echo $value;
	if($i<count($vehicle_makes)-1){
echo ',';
	}
	$i++;
}
?>
</div>
</div>

