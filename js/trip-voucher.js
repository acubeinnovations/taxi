$(document).ready(function(){
	

	//vehicle ownership types
	var ATTACHED_VEHICLE=3;
	var OWNED_VEHICLE=1;

	//driver status
	var OWNED_DRIVER=1;
	var ATTACHED_DRIVER=2;
	
	var base_url=window.location.origin;

	//--------------trip complete action-------------------
	$('.complete-trip').click(function(){
		if($(this).find('span').attr('vehicle_model_id')>0){
			return true;
		}else{
			var trip_id=$(this).find('span').attr('trip_id');
			var url=base_url+"/organization/front-desk/trip-booking/"+trip_id;
			var r = confirm("Please Select Vehicle Model To Complete The Trip..Click OK to Continue..!");
		    	if (r == true) {
		       		window.open(url, '_blank');
				return false; 
		    	} else {
		       		return false; 
		    	}
		   
		}
	});
	//------------------------------------------------------------


	//--to expand and collapse table rows for trips	

	$('.common').click(function(){ 
		$(this).hide();
		if($(this).attr('limited')=='true'){
		
			$(this).next().show();
		}else if($(this).attr('limited')=='false'){
			$(this).prev().show();
		}	
	});
	//------------------------------------------------------------

	
	$('#vehicle_model_id').on('change',function(){
		var vehicle_model_id = $(this).val();
		var customer_id = $('input[name="customer_id"]').val();
		id='#trip-tariff';

		var vehicle_ac_type_id = $('input[name="vehicle_ac_type_id"]').val();
		var tariff_id = $('#trip-tariff').val();
		generateTariffs(vehicle_model_id,vehicle_ac_type_id,tariff_id,id,customer_id,'no');
	});

	
	

	//-----------VOUCHER CLICK-------------------
	var nighthalt_flag = false;//night halt flag variable
	$('.voucher').on('click',function(){ 
	
		clearAllFields();
	

		$('#voucher-stretch').addClass( "strech" );
		$('#voucher-hide').css('display','none');

		var new_voucher = $(this).attr('new_voucher');
		var trip_id=$(this).attr('trip_id');

		//get trip and voucher data
		$.post(base_url+"/trip-booking/getTripVoucher",
		{
			trip_id:trip_id,
			ajax:'YES'
		
		},function(data){
			if(data['trip']){
				var trip_expense = '';
				var driver_id 		= data['trip'].driver_id;
				var tarrif_id		= data['trip'].tariff_id;
				var pick_up_time	= data['trip'].pick_up_time; 
				var vehicle_model_id	= data['trip'].vehicle_model_id;
				var vehicle_ac_type_id	= data['trip'].vehicle_ac_type_id;

				var payment_type_id	= data['trip'].payment_type_id;
				var pick_up_date	= data['trip'].pick_up_date;
				var drop_date		= data['trip'].drop_date;
	
				var customer_id		= data['trip'].customer_id;
				var customer_name	= data['trip'].customer_name;
				var company_name	= data['trip'].company_name;
				var model		= data['trip'].model;
				var vehicle_no		= data['trip'].registration_number;
				var description		= data['trip'].remarks;
				var ownership		= data['trip'].vehicle_ownership_types_id;
				var driver_status_id 	= data['trip'].driver_status_id;
				if(vehicle_ac_type_id==-1){
					vehicle_ac_type_id=1;
				}


				$('.overlay-container').css('display','block');

				var top=-1*(Number($('.trips-table').height())+100); 
				$('.modal-body').css('top',top);

	
				$('.trip-voucher-save').attr('trip_id',trip_id);
				$('.trip-voucher-save').attr('driver_id',driver_id);
				$('.trip-voucher-save').attr('new_voucher',new_voucher);

				if(new_voucher==0){

					$('.totaldriverkmamount').attr('totamountset','true');
					$('.totalvehiclekmamount').attr('totamountset','true');
					$('.totaldriverhramount').attr('totamountset','true');
					$('.totalvehiclehramount').attr('totamountset','true');

				}else{
					$('.totaldriverkmamount').attr('totamountset','false');
					$('.totalvehiclekmamount').attr('totamountset','false');
					$('.totaldriverhramount').attr('totamountset','false');
					$('.totalvehiclehramount').attr('totamountset','false');
				}

	
				$('.customer').val(customer_name);
				$('.company').val(company_name);
				$('#vehicle_model_id').val(vehicle_model_id);
				$('.vehicleno').val(vehicle_no);
				$('.ownership').val(ownership);
				$('.driver_status').val(driver_status_id);

				//set for hidden inputs
				$('input[name="vehicle_ac_type_id"]').val(vehicle_ac_type_id);
				$('input[name="customer_id"]').val(customer_id);

				//payment blocks display options
				if(ownership == OWNED_VEHICLE && driver_status_id==OWNED_DRIVER){
					
					//both owned
					$(".vehicle-payment").css('display','none'); 
					$(".driver-payment").css('display','block');
				}else if(ownership == ATTACHED_VEHICLE && driver_status_id==ATTACHED_DRIVER){
					//both attached
					$(".vehicle-payment").css('display','block'); 
					$(".driver-payment").css('display','none');
				}else if(ownership == OWNED_VEHICLE && driver_status_id==ATTACHED_DRIVER){
					//owned vehicle and attached driver
					$(".vehicle-payment").css('display','none'); 
					$(".driver-payment").css('display','block');
				}else{
					$(".vehicle-payment").css('display','block'); 
					$(".driver-payment").css('display','block');
				}

			
				if(data['voucher']){
	
					trip_expense = data['voucher'].trip_expense;
			
					var total_km = data['voucher'].end_km_reading-data['voucher'].start_km_reading;
					if(data['voucher'].trip_start_date!='' && data['voucher'].trip_starting_time!='' && data['voucher'].trip_end_date!='' && data['voucher'].trip_ending_time!='' ){
						var time_diff = timeDifference(data['voucher'].trip_start_date,data['voucher'].trip_starting_time,data['voucher'].trip_end_date,data['voucher'].trip_ending_time);
				
						time_diff = time_diff.split('-');
						$('.triptime').val(time_diff[1]+':'+time_diff[2]);

					}
				
					//set_tarif_row_with_daysno(data['voucher'].no_of_days);

					$('.voucherno').val(data['voucher'].voucher_no);
					$('.voucherno').attr('readonly','true');

					$('.startdt').val(formatDate_d_m_Y(data['voucher'].trip_start_date));
					$('.enddt').val(formatDate_d_m_Y(data['voucher'].trip_end_date));
					$('.startkm').val(data['voucher'].start_km_reading);
					$('.endkm').val(data['voucher'].end_km_reading);
					$('#payment').val(data['voucher'].payment_type_id);

					$('.description').val(data['voucher'].remarks);
					$('.releasingplace').val(data['voucher'].releasing_place);
					start_time=data['voucher'].trip_starting_time.split(':');
					$('.tripstartingtime').val(start_time[0]+':'+start_time[1]);
					end_time=data['voucher'].trip_ending_time.split(':');
					$('.tripendingtime').val(end_time[0]+':'+end_time[1]);
	
					$('.daysno').val(data['voucher'].no_of_days);
				
							
					$('.nighthalt').val(data['voucher'].night_halt_charges);
					$('.nighthalt').attr('night_halt',data['voucher'].night_halt_charges);
				
					$('.driverbata').val(data['voucher'].driver_bata);
					$('.driverbata').attr('driver_bata',data['voucher'].driver_bata);
				
					$('.basekm').val(data['voucher'].base_kilometers);
					$('.basekmamount').val(data['voucher'].base_kilometer_amount);
					$('.adtkmrate').val(data['voucher'].base_additional_kilometer_rate);
				
					$('.basedriverkm').val(data['voucher'].driver_base_kilometers);
					$('.basedriverkmamount').val(data['voucher'].driver_base_kilometer_amount);
					$('.adtdriverkmrate').val(data['voucher'].driver_additional_kilometer_rate);
	
						 
					 $('.driverhrpaymentpercentage').val(data['voucher'].driver_payment_percentage);
					 $('.driverkmpaymentpercentage').val(data['voucher'].driver_payment_percentage);
					//click here
					 $('.driverhrpaymentpercentage').trigger('click');

					$('.basevehiclekm').val(data['voucher'].vehicle_base_kilometers);
				       $('.basevehiclekmamount').val(data['voucher'].vehicle_base_kilometer_amount);
					$('.adtvehiclekmrate').val(data['voucher'].vehicle_additional_kilometer_rate);
			
				
					$('.vehiclehrpaymentpercentage').val(data['voucher'].vehicle_payment_percentage);
					$('.vehiclekmpaymentpercentage').val(data['voucher'].vehicle_payment_percentage);
					//click here
					 $('.vehiclekmpaymentpercentage').trigger('click');
				   
					$('.basehrs').val(data['voucher'].base_hours);
					$('.basehrsamount').val(data['voucher'].base_hour_amount);
					$('.adthrsrate').val(data['voucher'].base_additional_hour_rate);
	
	
					$('.basedriverhrs').val(data['voucher'].driver_base_hours);
					$('.basedriverhrsamount').val(data['voucher'].driver_base_hours_amount);
					$('.adtdriverhrrate').val(data['voucher'].driver_additional_hour_rate);
	

					$('.basevehiclehrs').val(data['voucher'].vehicle_base_hours);
				   	$('.basevehiclehrsamount').val(data['voucher'].vehicle_base_hours_amount);
					$('.adtvehiclehrrate').val(data['voucher'].vehicle_additional_hour_rate);
	    
	 
					id='#trip-tariff';
					res=generateTariffs(vehicle_model_id,vehicle_ac_type_id,data['voucher'].tariff_id,id,customer_id,newvoucher='no');
					if(res===true){		
				
						setTimeout(function(){ 
							//click here
							$('.endkm').trigger('click');
							setTripDaysParams(data['voucher'].no_of_days); 
							if(data['voucher'].base_km_hr=='H'){
								$('.totalamount').attr('amount-class-to-be-selected','totalhramount');
								//click here
								$('.totamount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
							}else if(data['voucher'].base_km_hr=='K'){
							 	$('.totalamount').attr('amount-class-to-be-selected','totalkmamount');
								//click here
								$('.totamount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
							}
							if(data['voucher'].driver_km_hr=='H'){
									 $('.totaldriverkmamount').attr('amount-class-to-be-selected','totaldriverhramount');
								//click here
								$('.totdriveramount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
						
							}else if(data['voucher'].driver_km_hr=='K'){
							 	$('.totaldriverkmamount').attr('amount-class-to-be-selected','totaldriverkmamount');
								//click here
								$('.totdriveramount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
						
							}
				
							if(data['voucher'].vehicle_km_hr=='H'){
								$('.totalvehiclekmamount').attr('amount-class-to-be-selected','totalvehiclehramount');
								//click here
								$('.totvehicleamount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
						
							}else if(data['voucher'].vehicle_km_hr=='K'){
							 	$('.totalvehiclekmamount').attr('amount-class-to-be-selected','totalvehiclekmamount');
								//click here
								$('.totvehicleamount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
										 
							}
						 }, 1000);
			
					}
					$('.taxgroup').val(data['voucher'].tax_group_id);
					setTax(data['voucher'].total_trip_amount);
				}else{//new voucher
					start_time=pick_up_time.split(':');
					$('.tripstartingtime').val(start_time[0]+':'+start_time[1]);
				
					$('.startdt').val(formatDate_d_m_Y(pick_up_date));
					$('.enddt').val(formatDate_d_m_Y(drop_date));
					$('.description').val(description);

					id='#trip-tariff';
				
				
					generateTariffs(vehicle_model_id,vehicle_ac_type_id,tarrif_id,id,customer_id,newvoucher='yes');
					var vehicle_no = $('.vehicleno').val();
					$.post(base_url+"/trip-booking/getVehicleDriverPercentages",
					{
						VehicleDriverPercentages:'VehicleDriverPercentages',
						VehicleRegNo:vehicle_no
			
					},function(data){
							data=jQuery.parseJSON(data);
							if(data!='false'){
								if(data.driver!='false'){//alert(data.driver);return false;
									//$('.driverhrpaymentpercentage,.driverkmpaymentpercentage').val(data.driver);
									$(".driverkmpaymentpercentage").val(data.driver);
								
									$(".driverhrpaymentpercentage").val(data.driver);
								}
								if(data.vehicle!='false'){
									$('.vehiclehrpaymentpercentage,.vehiclekmpaymentpercentage').val(data.vehicle);
								}
							}
					});
				}//voucher

				setTripExpenses(trip_expense);


			}//trip condition
		
		});	
			
	});




	$('.modal-close').on('click',function(){
	
		$('#voucher-stretch').removeClass( "strech" );
		$('#voucher-hide').css('display','block');
	
		clearErrorLabels();
		clearAllFields();
		resetTax();
		history.go(0);


	});

	//calculate total km readming
	$('.endkm').on('click keyup blur',function(e) {
		var start = $('.startkm').val();
		var end = $(this).val();
		var total = end - start;
		$('.totalkm').val(total);
	
	});

	$('.startkm').on('click keyup  blur',function(e) {
		var end = $('.endkm').val();
		var start = $(this).val();
		var total = end - start;
		$('.totalkm').val(total);

	
	});



	//calculate total hrs readming
	$('#trip-tariff').on('click blur',function(e) {
		var start = $('.tripstartingtime').val();
		var end = $('.tripendingtime').val();
		var fromdate=formatDate_Y_m_d($('#startdt').val()); 
		var todate=formatDate_Y_m_d($('.enddt').val());
		if(fromdate!='' && todate!='' && end!='' && start!=''){
			var total = timeDifference(fromdate,start,todate,end);
			total=total.split('-');
			$('.triptime').val(total[1]+':'+total[2]);
			$('.daysno').val(total[0]);
			setTripDaysParams(total[0]);
			
		}else{
			$('.triptime').val('');
			$('.daysno').val('');
			enablekmfields();
			enablehrfields();
		
		}
	});

	$('.tripstartingtime,.tripendingtime').on('blur click',function(e) {
		var end = $('.tripendingtime').val();
		var start =$('.tripstartingtime').val();
		var fromdate=formatDate_Y_m_d($('#startdt').val());
		var todate=formatDate_Y_m_d($('.enddt').val());
		if(fromdate!='' && todate!='' && end!='' && start!=''){
			var total = timeDifference(fromdate,start,todate,end);
			total=total.split('-');
			$('.triptime').val(total[1]+':'+total[2]);
			$('.daysno').val(total[0]);
			setTripDaysParams(total[0]);
		
		}else{
			$('.triptime').val('');
			$('.daysno').val('');
			enablekmfields();
			enablehrfields();
		}
	});

	$('.enddt').on('blur click',function(e) {
		var end = $('.tripendingtime').val();
		var start = $('.tripstartingtime').val();
		var fromdate=formatDate_Y_m_d($('#startdt').val());
		var todate=formatDate_Y_m_d($('.enddt').val());
		if(fromdate!='' && todate!='' && end!='' && start!=''){
			var total = timeDifference(fromdate,start,todate,end);
			total=total.split('-');
			$('.triptime').val(total[1]+':'+total[2]);
			$('.daysno').val(total[0]);
			if(total[0]==1){
				setBataandHalt();
				enablekmfields();
				enablehrfields();
				setHR_Tariff();
				setKM_Tariff();
				setCustomKM_Tariff('driver');
				setCustomKM_Tariff('vehicle');
				setCustomHR_Tariff('driver');
				setCustomHR_Tariff('vehicle');
			}else{
				enablekmfields();
				setBataandHalt();
				setKM_Tariff();
				setCustomKM_Tariff('driver');
				setCustomKM_Tariff('vehicle');
				disablehrfields();
				//clearkmfields();
				//clearhrfields();
			}
		}else{
			$('.triptime').val('');
			$('.daysno').val('');
			enablekmfields();
			enablehrfields();
			enableperdayfields();
		}
	
	});

	$('.startkm,.endkm').on('click blur',function(){
	if($('.startkm').val()!='' && $('.endkm').val()!='' ){
	setBataandHalt();
	setKM_Tariff();
	setCustomKM_Tariff('driver');
	setCustomKM_Tariff('vehicle');
	}
	});




	$(document).keydown(function(e) {
	  
	  if (e.keyCode == 27) { 
		clearErrorLabels();
		clearAllFields();
		resetTax();
	 }   // esc

	});


	//set which amount is to select

	$('.totamount-radio-container2 > .iradio_minimal > .iCheck-helper').click(function(){
	$('.totalamount').attr('amount-class-to-be-selected','totalhramount');
	checkTotAmount();
	});

	$('.totamount-radio-container1 > .iradio_minimal > .iCheck-helper').click(function(){

	$('.totalamount').attr('amount-class-to-be-selected','totalkmamount');
	checkTotAmount();
	});




	//calculating tax amount on changing tax group from voucher
	$(".taxgroup").change(function(){
		var amount = $('.totalamount').val();
		setTax(amount);
	});



	$('#totaltax').click(function(){
		$('#totaltax').hide();
		$(".taxgroup").show();
	});

	$('.taxgroup').on('blur',function() {
		var amount = $('.totalamount').val();
		setTax(amount);
	});
	

	

	$('.daysno').keyup(function(){
		var daysno = $(this).val();
		$('.daysno').val(daysno);
		//setBataandHalt();
		//setKM_Tariff();
		//setHR_Tariff();
		setTripDaysParams(daysno);
	});


	//km keyup event
	$('.basekm,.basekmamount,.adlkmrate,.adtkmrate').keyup(function(){
		setBataandHalt();
		setKM_Tariff();
	});

	//hourly tariff calculation
	$('.basehrs,.basehrsamount,.adlhrrate,.adthrrate').keyup(function(){
		setBataandHalt();
		setHR_Tariff();
	});




	$(document).on('keyup', ".trip-expense-input",function () {
		checkTotAmount();
	});


	//--------trip voucher save---------------------------------------
	$('.trip-voucher-save').on('click',function(){ 
	
	
		var new_voucher=$(this).attr('new_voucher');
		var tax_group = $('.taxgroup').val();//tax calculating factor
	   	var error = 'false';
	    	if(new_voucher == 1 && tax_group == ''){
			error = true;
		}
	    	var trip_id=$(this).attr('trip_id');
	    	var driver_id=$(this).attr('driver_id');
	
	   	payment_type_id= $('#payment').val(); 
		var combo_data={};
		combo_data['trip-tariff']=$('#trip-tariff').val();
		combo_data['payment']=$('#payment').val();
		var data={};

	   
		data['taxgroup']=$('.taxgroup').val();
		data['voucherno']= voucherno = $('.voucherno').val();
		remarks = $('.description').val();	
		releasing_place=$('.releasingplace').val();
		tariff_id=$('#trip-tariff').val();

		data['startdt']=startdt=$('.startdt').val();   
		data['enddt']=enddt=$('.enddt').val();
		data['tripstartingtime']=trip_starting_time=$('.tripstartingtime').val();
		data['tripendingtime']=trip_ending_time=$('.tripendingtime').val();

		data['startkm']=startkm=$('.startkm').val();
		data['endkm']=endkm=$('.endkm').val();

	   
		no_of_days=$('.daysno').val();

		//tarif values
		data['basekm']=basekm=$('.basekm').val();
		data['basekmamount']=basekmamount=$('.basekmamount').val();
		data['adtkmrate']=adtkmrate=$('.adtkmrate').val();
		adtkm=$('.adtkm').val();

		data['basehrs']=basehr=$('.basehrs').val();
		data['basehrsamount']=basehramount=$('.basehrsamount').val();
		data['adthrsrate']= adthrrate=$('.adthrsrate').val();
		adthr=$('.adthrs').val();
	    

	
		trip_narration="Minimum ";
		if($('.totalamount').attr('amount-class-to-be-selected')=='totalhramount'){
	    		var base_km_hr='H';
			 trip_narration=trip_narration+basehr+' Hr @  Rs.'+basehramount+' + Additional '+adthr+' Hr @  Rs.'+adthrrate+'/Hr';
		}else if($('.totalamount').attr('amount-class-to-be-selected')=='totalkmamount'){
	 		var base_km_hr='K';
		
			 trip_narration=trip_narration+basekm+' KM @ Rs. '+basekmamount+' + Additional '+adtkm+' KM @  Rs.'+adtkmrate+'/KM';
		}
	    
		data['basedriverkm']=driverbasekm=$('.basedriverkm').val();
		data['basedriverkmamount']=driverbasekmamount=$('.basedriverkmamount').val();
		data['adtdriverkmrate']=driveradtkmrate=$('.adtdriverkmrate').val();
	

		data['basevehiclekm']=vehiclebasekm=$('.basevehiclekm').val();
		data['basevehiclekmamount']=vehiclebasekmamount=$('.basevehiclekmamount').val();
		data['adtvehiclekmrate']=vehicleadtkmrate=$('.adtvehiclekmrate').val();



		var ownership = $(".ownership").val();
		var driver_status_id = $(".driver_status").val();

		if(ownership == OWNED_VEHICLE && driver_status_id==OWNED_DRIVER){
					
			//both owned
			vehicle_payment = false;
			driver_payment = true;
		}else if(ownership == ATTACHED_VEHICLE && driver_status_id==ATTACHED_DRIVER){
			//both attached
			vehicle_payment = true;
			driver_payment = false;
		}else if(ownership == OWNED_VEHICLE && driver_status_id==ATTACHED_DRIVER){
			//owned vehicle and attached driver
			vehicle_payment = false;
			driver_payment = true;
		}else{
			vehicle_payment = true;
			driver_payment = true;
		}
		
		if(driver_payment){
			
			if($('.totaldriverkmamount').attr('amount-class-to-be-selected')=='totaldriverhramount'){ 
				var driverbase_km_hr='H';
				data['driverpaymenthramount']=driverpaymentamount= $('.driverpaymenthramount').val();
				data['driverhrpaymentpercentage']=driverpaymentpercentage=$('.driverhrpaymentpercentage').val();
				data['totaldriverhramount']=totaldrivertripamount=$('.totaldriverhramount').val();
				data['driverpaymentkmamount']='NO_VALUE';
				data['driverkmpaymentpercentage']='NO_VALUE';
			}else if($('.totaldriverkmamount').attr('amount-class-to-be-selected')=='totaldriverkmamount'){
				var driverbase_km_hr='K';
				data['driverpaymentkmamount']=driverpaymentamount= $('.driverpaymentkmamount').val();
				data['driverkmpaymentpercentage']=driverpaymentpercentage=$('.driverkmpaymentpercentage').val();
				data['totaldriverkmamount']=totaldrivertripamount=$('.totaldriverkmamount').val();
				data['driverpaymenthramount']='NO_VALUE';
				data['driverhrpaymentpercentage']='NO_VALUE';
			}
		}else{
			driverpaymentamount= 0;
			driverpaymentpercentage=0;
			totaldrivertripamount=0;
			driverbase_km_hr='';
		}


		if(vehicle_payment) {
			if($('.totalvehiclekmamount').attr('amount-class-to-be-selected')=='totalvehiclehramount'){ 
				var vehiclebase_km_hr='H'; 
					data['vehiclepaymenthramount']=vehiclepaymentamount= $('.vehiclepaymenthramount').val();
					data['vehiclehrpaymentpercentage']=vehiclepaymentpercentage=$('.vehiclehrpaymentpercentage').val();
					data['totalvehiclehramount']=totalvehicletripamount=$('.totalvehiclehramount').val();
					data['vehiclepaymentkmamount']= 'NO_VALUE';
					data['vehiclekmpaymentpercentage']='NO_VALUE';
	
			  }else if($('.totalvehiclekmamount').attr('amount-class-to-be-selected')=='totalvehiclekmamount'){
				var vehiclebase_km_hr='K'; 
					data['vehiclepaymentkmamount']=vehiclepaymentamount= $('.vehiclepaymentkmamount').val();
					data['vehiclekmpaymentpercentage']=vehiclepaymentpercentage=$('.vehiclekmpaymentpercentage').val();
					data['totalvehiclekmamount']=totalvehicletripamount=$('.totalvehiclekmamount').val();
					data['vehiclepaymenthramount']='NO_VALUE';
					data['vehiclehrpaymentpercentage']='NO_VALUE';
	
			 }
		}else{
			vehiclepaymentamount= 0;
			vehiclepaymentpercentage=0;
			totalvehicletripamount=0;
			vehiclebase_km_hr='';
		}
	   	    
		data['basedriverhrs']=driverbasehr=$('.basedriverhrs').val();
		data['basedriverhrsamount']=driverbasehramount=$('.basedriverhrsamount').val();
		data['adtdriverhrrate']=driveradthrrate=$('.adtdriverhrrate').val();
	    
	
		data['basevehiclehrs']=vehiclebasehr=$('.basevehiclehrs').val();
		data['basevehiclehrsamount']=vehiclebasehramount=$('.basevehiclehrsamount').val();
		data['adtvehiclehrrate']=vehicleadthrrate=$('.adtvehiclehrrate').val();
	    
	   
	    	//other charges
		nighthalt=$('.nighthalt').val();
		if(nighthalt!='' || Number(nighthalt)>0){
			trip_narration=trip_narration+' + Night Halt Rs.'+nighthalt;
		}

		driverbata=$('.driverbata').val();
		if(driverbata!='' || Number(driverbata)>0){
			trip_narration=trip_narration+' + Driver Bata Rs.'+driverbata;
		}

		//trip expense
		var expense = {};
		$("input.trip-expense-input").each(function(){
			expense_amount 	= $(this).val();
			expense_code 	= $(this).attr("id");
			expense[expense_code] = expense_amount;
			expense_name = $("label[for='"+$(this).attr('id')+"']").text();
			if(Number(expense_amount) > 0)
				trip_narration=trip_narration+" + "+expense_name+' Rs.'+expense_amount;
		});
		

		//total
		data['totalamount']=totalamount=$('.totalamount').val();
		resetErrorFields(data);
		resetComboErrorFields(combo_data);
		error=isVarNull(data);
		error_combo=isVarNullCombo(combo_data);
	
		if(error=='false' && error_combo=='false'){
			$.post(base_url+"/trip-booking/tripVoucher",
		      	{
		       		trip_id:trip_id,
				remarks:remarks,
				tariff_id:tariff_id,
				voucher_no:voucherno,
				trip_end_date:enddt,
				trip_start_date:startdt,
				trip_starting_time:trip_starting_time,
				trip_ending_time:trip_ending_time,
				start_km_reading:startkm,
				end_km_reading:endkm,
				releasing_place:releasing_place,
				no_of_days:no_of_days,
				base_kilometers:basekm,
				base_kilometer_amount:basekmamount,
				base_additional_kilometer_rate:adtkmrate,
				base_km_hr:base_km_hr,
				base_hours:basehr,
				trip_narration:trip_narration,
				driver_trip_amount:totaldrivertripamount,
				vehicle_trip_amount:totalvehicletripamount,
				base_hour_amount:basehramount,
				base_additional_hour_rate:adthrrate,
				driver_base_kilometers:driverbasekm,
				driver_base_kilometer_amount:driverbasekmamount,
				driver_additional_kilometer_rate:driveradtkmrate,
				driver_km_hr:driverbase_km_hr,
				driver_payment_percentage:driverpaymentpercentage,
				driver_payment_amount:driverpaymentamount,
				driver_base_hours:driverbasehr,
				driver_base_hours_amount:driverbasehramount,
				driver_additional_hour_rate:driveradthrrate,
				vehicle_base_kilometers:vehiclebasekm,
				vehicle_base_kilometer_amount:vehiclebasekmamount,
				vehicle_additional_kilometer_rate:vehicleadtkmrate,
				vehicle_km_hr:vehiclebase_km_hr,
				vehicle_payment_percentage:vehiclepaymentpercentage,
				vehicle_payment_amount:vehiclepaymentamount,
				vehicle_base_hours:vehiclebasehr,
				vehicle_base_hours_amount:vehiclebasehramount,
				vehicle_additional_hour_rate:vehicleadthrrate,
				night_halt_charges:nighthalt,
				driver_bata:driverbata,
				driver_id:driver_id,
				total_trip_amount:totalamount,
				tax_group:tax_group,
				payment_type_id:payment_type_id,	
				expense:expense
		       
		    },function(data){
		      if(data!='false'){
		        var fa_link = '';
		        var fa_val;
		        var arr = $.parseJSON(data);
		        $.each(arr,function(key,value){
		            fa_val = value;
		            if(key == 'NewDelivery'){
		                fa_link = 'NewDelivery';

		            }else if(key == 'ModifyDelivery'){
		                fa_link = 'ModifyDelivery';
		            }
		        });
		       
		        if(fa_link != ''){
		            window.location.replace(base_url+'/account/front_desk/'+fa_link+'/'+fa_val);
		        }
		       }
		    });
	    }else{
		return false;
	    }
	});
	//------------------------------------------------------------------------


		

	$('#trip-tariff').change(function(){
		if($('#trip-tariff').val()!=-1){
			res=customizedTariff();
			if(res=true){
				var triptime=$('.triptime').val();
					if(triptime != ''){
				
				setHR_Tariff();
				setKM_Tariff();
				}
			}
		}
		
	});
	

	//set which amount is to select-driver

	$('.totdriveramount-radio-container2 > .iradio_minimal > .iCheck-helper').click(function(){
	$('.totaldriverkmamount').attr('amount-class-to-be-selected','totaldriverhramount');

	});

	$('.totdriveramount-radio-container1 > .iradio_minimal > .iCheck-helper').click(function(){

	$('.totaldriverkmamount').attr('amount-class-to-be-selected','totaldriverkmamount');

	});

	//set which amount is to select-vehicle

	$('.totvehicleamount-radio-container2 > .iradio_minimal > .iCheck-helper').click(function(){
	$('.totalvehiclekmamount').attr('amount-class-to-be-selected','totalvehiclehramount');

	});

	$('.totvehicleamount-radio-container1 > .iradio_minimal > .iCheck-helper').click(function(){

	$('.totalvehiclekmamount').attr('amount-class-to-be-selected','totalvehiclekmamount');

	});



	//km keyup event
	$('.basedriverkm,.basedriverkmamount,.adtdriverkmrate').on('blur  click  keyup',function(){
	
		setCustomKM_Tariff('driver');
	});

	$('.basevehiclekm,.basevehiclekmamount,.adtvehiclekmrate').on('blur  click  keyup',function(){
	
		setCustomKM_Tariff('vehicle');
	});

	//hourly tariff calculation
	$('.basedriverhrs,.basedriverhrsamount,.adtdriverhrrate').on('blur  click  keyup',function(){
	
		setCustomHR_Tariff('driver');
	});

	$('.basevehiclehrs,.basevehiclehrsamount,.adtvehiclehrrate').on('blur  click  keyup',function(){
	
		setCustomHR_Tariff('vehicle');

	});

	$('.driverkmpaymentpercentage,.driverhrpaymentpercentage').on('blur change',function(){
		var id=$(this).val();
		$(".driverkmpaymentpercentage").val(id);
		$(".driverhrpaymentpercentage").val(id);
		calculatePaymentAmount('driver');

	});

	$('.vehiclekmpaymentpercentage,.vehiclehrpaymentpercentage').on('blur change',function(){
	
		calculatePaymentAmount('vehicle');

	});	

	//////////////////////FUNCTIONS////////////////////////////////////////
	
	//set night halt
	function setNighthalt(trip_duration,start_actual_time,end_actual_time)
	{
		var _time = trip_duration.split('-');
	
	
		if(_time[1] < 8){//hour less than 8 no night halt
			return false;
		}else if(_time[1] > 24){
			return true;
		}else if($('.startdt').val() != $('.enddt').val()){
			return true;
		}else{

			var start_hr = start_actual_time.getHours();
			var start_min = start_actual_time.getMinutes();
			var end_hr = end_actual_time.getHours();
			var end_min = end_actual_time.getMinutes();
			if(end_hr > 23 || end_hr == 0 || (end_hr == 23 && end_min >= 30)){
				return true;
			}else{
				return false;
			}

			//alert(end_hr);
			/*if((start_hr > 15) || (start_hr == 15 && start_min >= 30) || (start_hr < 15  && (end_hr == 23 && end_min >= 30)) || (start_hr < 15  && end_hr == 0)){
				return true;
			
			}else if((end_hr > 23) || (end_hr > 0) || (end_hr == 23 && end_min >= 30)){
			
				return true;
			}else{
				return false;
			}*/
		}
	

	}



	//formatting date
	function formatDate_d_m_Y(date) {
		var d = new Date(date);
		var day = d.getDate();
		var month = d.getMonth() + 1;
		var year = d.getFullYear();
		if (day < 10) {
		    day = "0" + day;
		}
		if (month < 10) {
		    month = "0" + month;
		}
		var date = day + "-" + month + "-" + year;

		return date;
	    }

	/*function formatDate_Y_m_d(date) {
		var d = new Date(date);
		var day = d.getDate();
		var month = d.getMonth() + 1;
		var year = d.getFullYear();
		if (day < 10) {
		    day = "0" + day;
		}
		if (month < 10) {
		    month = "0" + month;
		}
		var date = year + "-" + month + "-" + day;

		return date;
	    }*/
	function formatDate_Y_m_d(date) {
		var d = date.split('-');
		var day = d[0];
		var month = d[1];
		var year = d[2];
		
		var date = year + "-" + month + "-" + day;

		return date;
	    }
	function toSeconds(time_str) {
	    // Extract hours, minutes and seconds
	    var parts = time_str.split(':');
	    var sec = 0;
	    // compute  and return total seconds
		if(parts[0]){
			sec = Number(sec) + Number(parts[0]) * 3600;
		}
		if(parts[1]){
			sec = Number(sec) + Number(parts[1]) * 60;
		}
		if(parts[2]){
			sec = Number(sec) + Number(parts[2]) * 1;
		}
	    //return parts[0] * 3600 + parts[1] * 60 + parts[2]*1; // seconds
		return sec;
	}

	function timeDifference(fromdate,fromtime,todate,totime){ 

		var fromdate=fromdate.split('-');
		var todate=todate.split('-');
	    var start_actual_time  =  fromdate[0]+'/'+fromdate[1]+'/'+fromdate[2]+' '+fromtime;
	    var end_actual_time    =  todate[0]+'/'+todate[1]+'/'+todate[2]+' '+totime;
	
	    start_actual_time = new Date(start_actual_time);
	    end_actual_time = new Date(end_actual_time);

	    var diff = end_actual_time - start_actual_time;

	    var diffSeconds = diff/1000;
	    var HH = Math.floor(diffSeconds/3600);
	    var MM = Math.floor(diffSeconds%3600)/60;
		var result='';
		var no_of_days=Math.floor(HH/24);  
	    if(HH%24==0 && MM==0){
	    result+=no_of_days+'-'+HH+'-'+MM;	
	    }
	    else if((HH>=24 && MM>=1) || HH>24){
	      no_of_days=no_of_days+1; 
		result+=no_of_days+'-'+HH+'-'+MM;	
	    }else{
	 	result+='1'+'-'+HH+'-'+MM;
	
		}

		nighthalt_flag = setNighthalt(result,start_actual_time,end_actual_time);
		return result;
	}
	/*function set_tarif_row_with_daysno(days=1)
	{
		if(days==1){
			enablekmfields();
			enablehrfields();
			disableperdayfields();
			clearperdaykmfields();
			total_tarif=0;
			setTotalAmount();
		}else{
			total_tarif=0;
			setTotalAmount();
			disablekmfields();
			disablehrfields();
			enableperdayfields();
			clearkmfields();
			clearhrfields();
		}
	}*/
	function setTripDaysParams(daysno)
	{
		if(daysno == 1){
			setBataandHalt();
			enablekmfields();
			enablehrfields();
			setHR_Tariff()
			setKM_Tariff();
			setCustomKM_Tariff('driver');
			setCustomKM_Tariff('vehicle');
			setCustomHR_Tariff('driver');
			setCustomHR_Tariff('vehicle');
		}else{
			enablekmfields();
			setBataandHalt();
			setKM_Tariff();
			setCustomKM_Tariff('driver');
			setCustomKM_Tariff('vehicle');
			disablehrfields();
			//clearkmfields();
			//clearhrfields();
		}
	}

	function clearErrorLabels(){
	$('.overlay-container').css('display','	none');
	$('.start-km-error').html('');
	$('.end-km-error').html('');
	$('.garage-km-error').html('');
	$('.garage-time-error').html('');
	$('.tariff-error').html('');

	$('.startdt').val('');
	$('.enddt').val('');

	$('.startkm').val('');
	$('.endkm').val('');
	$('.totalkm').val('');
	$('.garageclosingkm').val('');
	$('.garageclosingtime').val('');
	$('.releasingplace').val('');
	$('.tripstartingtime').val('');
	$('.tripendingtime').val('');
	$('#tarrif').val('');
	$('.nighthalt').val('');
	$('.driverbata').val('');
	}




	function disablekmfields(){

	$('.basekm').attr('disabled','true');
	$('.baseamount').attr('disabled','true');
	$('.adlkmrate').attr('disabled','true');
	$('.adtkmrate').attr('disabled','true');
	$('.totamount-radio-container1').hide();

	$('.basedriverkm').attr('disabled','true');
	$('.basedriveramount').attr('disabled','true');
	$('.adldriverkmrate').attr('disabled','true');
	$('.adtdriverkmrate').attr('disabled','true');
	$('.totdriveramount-radio-container1').hide();

	$('.basevehiclekm').attr('disabled','true');
	$('.basevehicleamount').attr('disabled','true');
	$('.adlvehiclekmrate').attr('disabled','true');
	$('.adtvehiclekmrate').attr('disabled','true');
	$('.totvehicleamount-radio-container1').hide();
	}
	function clearkmfields(){
	total_tarif = 0;
	$('.totaltarif').val('');
	$('.basekm').val('');
	$('.baseamount').val('');
	$('.adlkmrate').val('');
	$('.adtkm').val('');
	}

	function disablehrfields(){

	$('.basehrs').attr('disabled','true');
	$('.basehrsamount').attr('disabled','true');
	$('.adlhrrate').attr('disabled','true');
	//$('.aditionalhramount').attr('disabled','true');
	$('.adthrsrate').attr('disabled','true');
	$('.totamount-radio-container2').hide();

	$('.basedriverhrs').attr('disabled','true');
	$('.basedriverhrsamount').attr('disabled','true');
	$('.adldriverhrrate').attr('disabled','true');
	$('.aditionaldriverhramount').attr('disabled','true');
	$('.adtdriverhrrate').attr('disabled','true');
	$('.totdriveramount-radio-container2').hide();

	$('.basevehiclehrs').attr('disabled','true');
	$('.basevehiclehrsamount').attr('disabled','true');
	$('.adlvehiclehrrate').attr('disabled','true');
	$('.aditionalvehiclehramount').attr('disabled','true');
	$('.adtvehiclehrrate').attr('disabled','true');
	$('.totvehicleamount-radio-container2').hide();


	}

	function clearhrfields(){
	total_tarif = 0;
	$('.totaltarif').val('');
	$('.basehrs').val('');
	$('.basehrsamount').val('');
	$('.adlhrrate').val('');
	$('.adthrs').val('');
	}

	function enablekmfields(){

	$('.basekm').removeAttr('disabled');
	$('.baseamount').removeAttr('disabled');
	$('.adlkmrate').removeAttr('disabled');
	$('.adtkmrate').removeAttr('disabled');
	$('.totamount-radio-container1').show();

	$('.basedriverkm').removeAttr('disabled');
	$('.basedriveramount').removeAttr('disabled');
	$('.adldriverkmrate').removeAttr('disabled');
	$('.adtdriverkmrate').removeAttr('disabled');
	$('.totdriveramount-radio-container1').show();

	$('.basevehiclekm').removeAttr('disabled');
	$('.basevehicleamount').removeAttr('disabled');
	$('.adlvehiclekmrate').removeAttr('disabled');
	$('.adtvehiclekmrate').removeAttr('disabled');
	$('.totvehicleamount-radio-container1').show();
	}

	function enablehrfields(){
	$('.basehrs').removeAttr('disabled');
	$('.basehrsamount').removeAttr('disabled');
	$('.adlhrrate').removeAttr('disabled');
	//$('.aditionalhramount').removeAttr('disabled');
	$('.adthrsrate').removeAttr('disabled');
	$('.totamount-radio-container2').show();

	$('.basedriverhrs').removeAttr('disabled');
	$('.basedriverhrsamount').removeAttr('disabled');
	$('.adldriverhrrate').removeAttr('disabled');
	$('.aditionaldriverhramount').removeAttr('disabled');
	$('.adtdriverhrrate').removeAttr('disabled');
	$('.totdriveramount-radio-container2').show();

	$('.basevehiclehrs').removeAttr('disabled');
	$('.basevehiclehrsamount').removeAttr('disabled');
	$('.adlvehiclehrrate').removeAttr('disabled');
	$('.aditionalvehiclehramount').removeAttr('disabled');
	$('.adtvehiclehrrate').removeAttr('disabled');
	$('.totvehicleamount-radio-container2').show();
	}



	//clear all tariff inputs
	function clearAllFields(){
	$('.voucherno').val('');
	$('.voucherno').removeAttr('readonly');
	$('.startdt').val('');
	$('.enddt').val('');
	$('.startkm').val('');
	$('.endkm').val('');
	$('.description').val('');
	$('.releasingplace').val('');
	$('.tripstartingtime').val('');
	$('.tripendingtime').val('');
	$('.daysno').val('');
	$('.nighthalt').val('');
	$('.driverbata').val('');
	$('.basekm').val('');
	$('.basekmamount').val('');
	$('.adtkmrate').val('');
	$('.adtkm').val('');
	$('.adthrs').val('');
	$('.aditionalkmamount').val('');
	$('.aditionalhramount').val('');
	$('.totaldriverkmamount').val('');
	$('.totaldriverhramount').val('');
	$('.driverpaymentkmamount').val('');
	$('.driverpaymenthramount').val('');
	$('.aditionaldriverkmamount').val('');
	$('.aditionaldriverhramount').val('');
	$('.totalkmamount').val('');
	$('.totalhramount').val('');
	$('.adtvehiclekm').val('');
	$('.adtvehiclehrs').val('');
	$('.aditionalvehiclekmamount').val('');
	$('.aditionalvehiclehramount').val('');
	$('.totalvehiclekmamount').val('');
	$('.totalvehiclehramount').val('');
	$('.vehiclepaymentkmamount').val('');
	$('.vehiclepaymenthramount').val('');
	$('.totalamount').val(''); 
	 $('.adtdriverkm').val(''); 
	 $('.adtdriverhrs').val(''); 
	$('.basedriverkm').val('');
	$('.basedriverkmamount').val('');
	$('.adtdriverkmrate').val('');
	$('.driverhrpaymentpercentage').val('');
	$('.driverkmpaymentpercentage').val('');
	$('.basevehiclekm').val('');
	$('.basevehiclekmamount').val('');
	$('.adtvehiclekmrate').val('');
	$('.vehiclehrpaymentpercentage').val('');
	$('.vehiclekmpaymentpercentage').val('');
	$('.basehrs').val('');
	$('.basehrsamount').val('');
	$('.adthrsrate').val('');
	$('.basedriverhrs').val('');
	$('.basedriverhrsamount').val('');
	$('.adtdriverhrrate').val('');
	$('.basevehiclehrs').val('');
	$('.basevehiclehrsamount').val('');
	$('.adtvehiclehrrate').val('');
	}



	//KM tariff calculation
	function setKM_Tariff()
	{
		var basekm=$('.basekm').val();
		var basekmamount=$('.basekmamount').val();
		var tariff_id=$('#trip-tariff').val();
		var adlkmrate=$('.adtkmrate').val();
		var totalkm=$('.totalkm').val();
		var noofdays=$('.daysno').val();
		
		if(totalkm != '' && Number(totalkm) > 0 && tariff_id!=-1){
	
	
			if(totalkm!='' && basekm!=''){
				if(Number(noofdays)==1){
					if(Number(totalkm) <= Number(basekm)){
						$('.adtkm').val('');
						$('.adlkmrate').val('');
						$('.aditionalkmamount').val('');
						$('.totalkmamount').val(basekmamount);
			
						//driver
						$('.aditionaldriverkmamount').val('');
						$('.totaldriverkmamount').val(basekmamount);
						$('.adtdriverkm').val('');
						compareCustomAmounts('driver');

						//vehicle
						$('.aditionalvehiclekmamount').val('');
						$('.totalvehiclekmamount').val(basekmamount);
						$('.adtvehiclekm').val('');
						compareCustomAmounts('vehicle');

						$('.totaldriverkmamount').attr('totamountset','true');
						$('.totalvehiclekmamount').attr('totamountset','true');
				
					}else{
					
						var adtkm = totalkm-basekm;
						if(Number(adtkm)>0){
							$('.adtkm').val(adtkm);
						}else{
							$('.adtkm').val('');
						}

						if(basekmamount!='' && adlkmrate!=''){
							if(Number(adtkm)>0){
								var adtamt = Number(adtkm)*Number(adlkmrate);
								total_tarif = Number(basekmamount)+Number(adtamt);
								$('.aditionalkmamount').val(adtamt);
								$('.totalkmamount').val(total_tarif);
								//driver
								if($('.totaldriverkmamount').attr('totamountset')=='false'){
								$('.aditionaldriverkmamount').val(adtamt);
								$('.totaldriverkmamount').val(total_tarif);
								$('.adtdriverkm').val(adtkm);
								compareCustomAmounts('driver');
								}
								//vehicle
								if($('.totalvehiclekmamount').attr('totamountset')=='false'){
								$('.aditionalvehiclekmamount').val(adtamt);
								$('.totalvehiclekmamount').val(total_tarif);
								$('.adtvehiclekm').val(adtkm);
								compareCustomAmounts('vehicle');
								}
								$('.totaldriverkmamount').attr('totamountset','true');
								$('.totalvehiclekmamount').attr('totamountset','true');

							}else{

								$('.aditionalkmamount').val(adtamt);
								$('.totalkmamount').val(total_tarif);
	
								//driver
								$('.aditionaldriverkmamount').val('');
								$('.totaldriverkmamount').val(total_tarif);
								$('.adtdriverkm').val('');
						
								//vehicle
								$('.aditionalvehiclekmamount').val('');
								$('.totalvehiclekmamount').val(total_tarif);
								$('.adtvehiclekm').val('');
						
								$('.totaldriverkmamount').attr('totamountset','false');
								$('.totalvehiclekmamount').attr('totamountset','false');

							}
						}
					}
				}else if(Number(noofdays)>1){
					mutipledaysbasekm=Number(basekm)*Number(noofdays);
					mutipledaysbasekmamount=Number(basekmamount)*Number(noofdays);
					if(Number(totalkm) <= Number(mutipledaysbasekm)){
						$('.adtkm').val('');
						$('.adlkmrate').val('');
						$('.aditionalkmamount').val('');
						$('.totalkmamount').val(mutipledaysbasekmamount);

						//driver
						$('.aditionaldriverkmamount').val('');
						$('.totaldriverkmamount').val(mutipledaysbasekmamount);
						$('.adtdriverkm').val('');
						compareCustomAmounts('driver');

						//vehicle
						$('.aditionalvehiclekmamount').val('');
						$('.totalvehiclekmamount').val(mutipledaysbasekmamount);
						$('.adtvehiclekm').val('');
						compareCustomAmounts('vehicle');

						$('.totaldriverkmamount').attr('totamountset','true');
						$('.totalvehiclekmamount').attr('totamountset','true');
				
					}else{
					
						var adtkm = Number(totalkm)-Number(mutipledaysbasekm);
						if(Number(adtkm)>0){
							$('.adtkm').val(adtkm);
						}else{
							$('.adtkm').val('');
						}

						if(mutipledaysbasekmamount!='' && adlkmrate!=''){
							if(Number(adtkm)>0){
							var adtamt = Number(adtkm)*Number(adlkmrate);
							total_tarif = Number(mutipledaysbasekmamount)+Number(adtamt);
								$('.aditionalkmamount').val(adtamt);
								$('.totalkmamount').val(total_tarif);
								if($('.totaldriverkmamount').attr('totamountset')=='false'){
								$('.adtdriverkm').val(adtkm);
								$('.aditionaldriverkmamount').val(adtamt);
								$('.totaldriverkmamount').val(total_tarif);
								compareCustomAmounts('driver');
								}
								if($('.totalvehiclekmamount').attr('totamountset')=='false'){
								$('.adtvehiclekm').val(adtkm);
								$('.aditionalvehiclekmamount').val(adtamt);
								$('.totalvehiclekmamount').val(total_tarif);
								compareCustomAmounts('vehicle');
								}
								$('.totaldriverkmamount').attr('totamountset','true');
								$('.totalvehiclekmamount').attr('totamountset','true');

							}else{

								$('.aditionalkmamount').val(adtamt);
								$('.totalkmamount').val(total_tarif);
	
								//driver
								$('.aditionaldriverkmamount').val('');
								$('.adtdriverkm').val('');
								$('.totaldriverkmamount').val('');
						
								//vehicle
								$('.aditionalvehiclekmamount').val('');
								$('.totalvehiclekmamount').val('');
								$('.adtvehiclekm').val('');

								$('.totaldriverkmamount').attr('totamountset','false');
								$('.totalvehiclekmamount').attr('totamountset','false');

							}
						}
					}

				}
			}
			compareAmounts();
		
		
		}else{
			//clearAllTariff();
		}
	}
	//Hours tariff calculation
	function setHR_Tariff()
	{
	
		var basehrs=$('.basehrs').val();
		var tariff_id=$('#trip-tariff').val();
		var basehrsamount=$('.basehrsamount').val();
		var adlhrrate=$('.adthrsrate').val();
		var triptime=$('.triptime').val();
	
		if(triptime != '' && tariff_id!=-1){

			if(triptime!='' && basehrs!=''){
				if(basehrs.indexOf(':')>-1 ){
					basehrs=basehrs.split(':');
					base=basehrs[0]+'.'+basehrs[1];
				}else{
					base=basehrs;

				}
				triptime=triptime.split(':');

				tottime=triptime[0]+'.'+triptime[1];
				if(Number(tottime) <= Number(base)){
					total_tarif = Number(basehrsamount);
					$('.adthrs').val('');
					$('.adlhrrate').val('');
					$('.aditionalhramount').val('');
					$('.totalhramount').val(basehrsamount);
				
				}else{
				
					$('.adlhrrate').removeAttr('disabled');
					var adthrs=Number(tottime)-Number(base); //time difference
				
					adthrs=adthrs.toFixed(2);
					adthrsnew=adthrs.replace(/\./g, ':');
				

					if(basehrsamount!='' && adlhrrate!=''){

						var adtamt = calculateHrsAmount(adthrsnew,adlhrrate);
						total_tarif = Number(basehrsamount)+Number(adtamt);
					}
				
					if(Number(adthrs)>0){
						$('.adthrs').val(adthrsnew);
						$('.aditionalhramount').val(adtamt);
						$('.totalhramount').val(total_tarif);
						if($('.totaldriverhramount').attr('totamountset')=='false'){
							$('.aditionaldriverhramount').val(adtamt);
							$('.totaldriverhramount').val(total_tarif);
							$('.adtdriverhrs').val(adthrsnew);
							compareCustomAmounts('driver');
							}
							if($('.totalvehiclehramount').attr('totamountset')=='false'){
							$('.aditionalvehiclehramount').val(adtamt);
							$('.adtvehiclehrs').val(adthrsnew);
							$('.totalvehiclehramount').val(total_tarif);
							compareCustomAmounts('vehicle');
							}
							$('.totaldriverhramount').attr('totamountset','true');
							$('.totalvehiclehramount').attr('totamountset','true');
					}else{
						$('.adthrs').val('');
						$('.aditionalhramount').val('');
						$('.totalhramount').val('');
						
						//driver
						$('.aditionaldriverhramount').val('');
						$('.totaldriverhramount').val('');
						$('.adtdriverhrs').val('');
						$('.totaldriverhramount').attr('totamountset','false');
						$('.totalvehiclehramount').attr('totamountset','false');
				
						//vehicle
						$('.aditionalvehiclehramount').val('');
						$('.totalvehilcerhramount').val('');
						$('.adtvehiclehrs').val('');
						$('.totaldriverhramount').attr('totamountset','false');
						$('.totalvehiclehramount').attr('totamountset','false');
					}	
				}	
			
			}
			compareAmounts();
			//$('.kmhr').val(hr_flag);
			//setTotalAmount();

		}else{
			//clearAllTariff();
		}
	}


	function compareAmounts(){
		var totalhramount=$('.totalhramount').val();
		var totalkmamount=$('.totalkmamount').val();
		var noofdays=$('.daysno').val();
		if(Number(noofdays)==1){
		if(totalhramount!='' && totalkmamount!=''){
			if(Number(totalhramount)>Number(totalkmamount)){
				$('.totamount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
			}else if(Number(totalhramount)<Number(totalkmamount)){
				$('.totamount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
			}

		}
		}else if(Number(noofdays)>1){
			$('.totamount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
		}
	}

	//return true if tot amounts not null
	function checkTotAmount(){
		var totalhramount=$('.totalhramount').val();
		var totalkmamount=$('.totalkmamount').val();
		var noofdays=$('.daysno').val();
	
		if(totalhramount!='' && totalkmamount!='' && Number(noofdays)==1){
			setTotalAmount();
		}else if(totalkmamount!='' && Number(noofdays)>1){
			setTotalAmount();
		}
	}
	//calculate amount with time string and hourly rate
	function calculateHrsAmount(time_str,hrsrate){
	
		var parts = time_str.split(':');
		var hr_amount = 0;
		var min_amount = 0;
		if(hrsrate != ''){
			if(parts[0]){
				var hrs = Number(parts[0]);
				hr_amount = hrs*hrsrate;
			}
			if(parts[1]){
				var mns = Number(parts[1]);
				if(mns >0 && mns < 16){
					min_amount = hrsrate*0.25;
				}else if(mns > 15 && mns < 31){
					min_amount = hrsrate*0.5;
				}else  if(mns > 30 && mns < 46){
					min_amount = hrsrate*0.75;
				}else if(mns > 45 && mns < 60){
					min_amount = hrsrate;
				}
			}
		}
	
		return Number(hr_amount)+Number(min_amount);
	}

	//calculate total 
	function setTotalAmount()
	{	
	
		var driverbata = $('.driverbata').val();
		var nighthalt = $('.nighthalt').val();
		resetTax();
		if($('.totalamount').attr('amount-class-to-be-selected')!=''){
		
			var total_tarif=$('.'+$('.totalamount').attr('amount-class-to-be-selected')).val();
			var total = Number(total_tarif)+Number(driverbata)+Number(nighthalt);

			$(".trip-expense-input").each(function(){
				total += Number($(this).val());
			});

			$('.totalamount').val(total);
		
			setTax(total);
		}
	
	}

	function setTax(amount){
		
		$obj = $(".taxgroup");
		$id = $obj.val();
		var amt = $('.totalamount').val();
		$.post(base_url+"/account/getTotalTax",{id:$id, amt:amount},
			function(data){

			$obj.parent().find('#totaltax').val(data);
			$obj.hide();
			$obj.parent().find('#totaltax').show();
		});
	}
	function resetTax()
	{


		//$('#totaltax').text('');
	 	//$('.taxgroup').prop('selectedIndex',0);
		//$(".taxgroup option:selected").val('');
		//$(".taxgroup option:selected").text('');
		//$('#totaltax').hide();
		//$(".taxgroup").show();
	}


	//setting trip expense input , voucher trip expense as parameter
	function setTripExpenses(expenses = ''){
		$.post(base_url+"/trip-booking/getTripExpenses",
		{
			ajax:'YES'
			
		},function(data){
			var expense_html=''; 
		
			if(data!='false'){
				var val='';
			
				for(var i=0;i<data.length;i++){
					val = expenses[data[i].value];
					if(typeof(val) == "undefined") {
						val='';
					}
					
					expense_html+='<div class=" form-group div-with-20-percent-width-with-margin-0-10"><label for="'+data[i].value+'">'+data[i].description+'</label><input name="expense['+data[i].value+']" class="form-control  padding-2px-0-0-10-px voucher-text-box trip-expense-input" placeholder="Enter '+data[i].description+'" value="'+val+'"type="text" id="'+data[i].value+'"></div>';
			
				}
				$('#expense-div').append(expense_html);
		
			}
		});
	}
	//--------------------------------------------------

	function isVarNull(data){
		var error='false';
		$.each(data, function(key, value) {
		      	if(value==''){
				error='true';
				$('.'+key).css('border','1px solid red');
			}
		});
		return error;
	}
	function isVarNullCombo(data){
		var error='false';
		$.each(data, function(key, value) {
	     		if(value== -1){
				error='true';
				$('#'+key).css('border','1px solid red');
		  	}
		});
		return error;
	}

	function resetErrorFields(data){
		$.each(data, function(key, value) {
			$('.'+key).css('border','1px solid #CCC');
		});
	}
	function resetComboErrorFields(data){

		$.each(data, function(key, value) {
	      		$('#'+key).css('border','1px solid #CCC');  
		});
	}



	function generateTariffs(vehicle_model,vehicle_ac_type,tarif_id='',id,customer_id,newvoucher='yes'){
		var tarif_id=tarif_id;
		 $.post(base_url+"/tarrif/tariffSelecter",
			  {
				vehicle_model:vehicle_model,
				vehicle_ac_type:vehicle_ac_type
			  },function(data){
				if(data!='false'){
				data=jQuery.parseJSON(data);
				$(id+' option').remove();
				 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariffs--'));
				i=0;//alert(data.data.length);
				for(var i=0;i<data.data.length;i++){
				if(tarif_id==data.data[i].id){
				var selected="selected=selected";
				}else{
				var selected="";
				}
				  $(id).append($("<option customer_id='"+customer_id+"' rate='"+data.data[i].rate+"' additional_kilometer_rate='"+data.data[i].additional_kilometer_rate+"' minimum_kilometers='"+data.data[i].minimum_kilometers+"' driver_bata='"+data.data[i].driver_bata+"' night_halt='"+data.data[i].night_halt+"' additional_hour_rate='"+data.data[i].additional_hour_rate+"' minimum_hours='"+data.data[i].minimum_hours+"' vehicle_model_id='"+data.data[i].vehicle_model_id+"' vehicle_ac_type_id ='"+data.data[i].vehicle_ac_type_id+"' tariff_master_id='"+data.data[i].tariff_master_id+"' "+selected+"></option>").attr("value",data.data[i].id).text(data.data[i].title));
				
				}
					if(newvoucher=='yes'){
						customizedTariff();
					}
				}else{
				 $(id+' option').remove();
				 $(id).append($("<option rate='-1' additional_kilometer_rate='-1' minimum_kilometers='-1'></option>").attr("value",'-1').text('--Select Tariffs--'));
					$('.display-me').css('display','none');
				}
			
			  });
		
	return true;
	}


	//-------------driver and vehicle payment amounts-----------
	function calculatePaymentAmount(whom){

		totkmamount = $('.total'+whom+'kmamount').val();
		tothramount = $('.total'+whom+'hramount').val();
		noofdays=$('.daysno').val();

		if($('.'+whom+'kmpaymentpercentage option:selected').val()==-1){
			kmpaymentpercentage=0;
		}else{
			kmpaymentpercentage=Number($('.'+whom+'kmpaymentpercentage option:selected').text());
		}

		if($('.'+whom+'hrpaymentpercentage option:selected').val()==-1){
			hrpaymentpercentage=0;
		}else{
			hrpaymentpercentage=Number($('.'+whom+'hrpaymentpercentage option:selected').text());
		}

		if(totkmamount!='' && (tothramount!='' || Number(noofdays)>1) && kmpaymentpercentage!='' && hrpaymentpercentage!=''){

			kmcommsn=(Number(totkmamount)*(Number(kmpaymentpercentage)/100)).toFixed(2);
			hrcommsn=(Number(tothramount)*(Number(hrpaymentpercentage)/100)).toFixed(2);

		}else{
			kmcommsn = 0;
			hrcommsn = 0;
		}

		var ownership = $('.ownership').val();
		var driver_status_id = $('.driver_status').val();
		var driverbata = $('.driverbata').val();
		var nighthalt = $('.nighthalt').val();

		if(whom == 'driver'){
			if((ownership == OWNED_VEHICLE && driver_status_id == ATTACHED_DRIVER) || (ownership == ATTACHED_VEHICLE && driver_status_id == OWNED_DRIVER)) {
				kmcommsn = Number(kmcommsn)+Number(driverbata)+Number(nighthalt);
				hrcommsn = Number(hrcommsn)+Number(driverbata)+Number(nighthalt);
			}
		}else{
			if(ownership == ATTACHED_VEHICLE && driver_status_id == ATTACHED_DRIVER){
				kmcommsn = Number(kmcommsn)+Number(driverbata)+Number(nighthalt);
				hrcommsn = Number(hrcommsn)+Number(driverbata)+Number(nighthalt);
			}
		}
	
	


		$('.'+whom+'paymentkmamount').val(kmcommsn);
		$('.'+whom+'paymenthramount').val(hrcommsn);

	}




	function customizedTariff(){
		var vehicle_model 	= $('#vehicle_model_id').val();
		var vehicle_ac_type 	= $('input[name="vehicle_ac_type_id"]').val();
		var tariff_master_id	= $('#trip-tariff option:selected').attr('tariff_master_id');
		var customer_id 	= $('input[name="customer_id"]').val();

		$.post(base_url+"/tarrif/customertariff",
		{
			vehicle_model_id:vehicle_model,
			vehicle_ac_type_id:vehicle_ac_type,
			tariff_master_id:tariff_master_id,
			customer_id:customer_id,
			from:'trip-voucher'
		},function(data){

			data=jQuery.parseJSON(data);
			if(data!=false){
				var additional_kilometer_rate = data.data[0].additional_kilometer_rate;
				var minimum_kilometers = data.data[0].minimum_kilometers;
				var additional_hour_rate = data.data[0].additional_hour_rate;
				var minimum_hours = data.data[0].minimum_hours;
				var rate = data.data[0].rate;
				var driver_bata=data.data[0].driver_bata;
				var night_halt=data.data[0].night_halt;
			}else{
		
				var additional_kilometer_rate = $('#trip-tariff option:selected').attr('additional_kilometer_rate');
				var minimum_kilometers = $('#trip-tariff option:selected').attr('minimum_kilometers');
				var additional_hour_rate = $('#trip-tariff option:selected').attr('additional_hour_rate');
				var minimum_hours = $('#trip-tariff option:selected').attr('minimum_hours');
				var rate = $('#trip-tariff option:selected').attr('rate');
				var driver_bata=$('#trip-tariff option:selected').attr('driver_bata');
				var night_halt=$('#trip-tariff option:selected').attr('night_halt');

			} 
			$('.basekm').val(minimum_kilometers);
			$('.basehrs').val(minimum_hours);
			$('.basehrsamount').val(rate);
			$('.basekmamount').val(rate);
			$('.driverbata').attr('driver_bata',driver_bata);
			$('.nighthalt').attr('night_halt',night_halt);
			$('.adthrsrate').val(additional_hour_rate);
			$('.adtkmrate').val(additional_kilometer_rate);
			
			setBataandHalt();
			//driver km
			if($('.totaldriverkmamount').attr('totamountset')=='false'){
				$('.basedriverkm').val(minimum_kilometers);
				$('.basedriverkmamount').val(rate);
				$('.adtdriverkmrate').val(additional_kilometer_rate);
			}
			//driver hr
			if($('.totaldriverhramount').attr('totamountset')=='false'){
			$('.basedriverhrs').val(minimum_hours);
			$('.basedriverhrsamount').val(rate);
			$('.adtdriverhrrate').val(additional_hour_rate);
			}
			//vehicle km
			if($('.totalvehiclekmamount').attr('totamountset')=='false'){
				$('.basevehiclekm').val(minimum_kilometers);
				$('.basevehiclekmamount').val(rate);
				$('.adtvehiclekmrate').val(additional_kilometer_rate);
			}
			//vehicle hr
			if($('.totalvehiclehramount').attr('totamountset')=='false'){
				$('.basevehiclehrs').val(minimum_hours);
				$('.basevehiclehrsamount').val(rate);
				$('.adtvehiclehrrate').val(additional_hour_rate);
			}
		});
		return true;
	}


	//set bata and halt with no of days

	function setBataandHalt(){
		var driverbata= $('.driverbata').attr('driver_bata');
		var nighthalt=$('.nighthalt').attr('night_halt');
		var noofdays=$('.daysno').val();
		var new_voucher = $('.trip-voucher-save').attr('new_voucher');
		if(driverbata!='' && Number(noofdays)>1 && new_voucher==1){
			tot_driverbata=Number(driverbata)*Number(noofdays);
			$('.driverbata').val(tot_driverbata);
		}else{
			$('.driverbata').val(driverbata);
		}

		if(nighthalt_flag == true){
			$('.nighthalt').removeAttr('disabled');
			nighthalt = $('#trip-tariff option:selected').attr('night_halt');
			//if(nighthalt!='' && Number(noofdays)>1 && new_voucher==1){
			//alert(nighthalt);
			//alert(noofdays);
			//if(nighthalt!='' && Number(noofdays)>1){
				tot_nighthalt=Number(nighthalt)*Number(noofdays);
				$('.nighthalt').val(tot_nighthalt);
			//}else{
			//	$('.nighthalt').val(nighthalt);	
			//}
		}else{
			$('.nighthalt').val('');
			$('.nighthalt').attr('disabled',true);
		}
	

	}


	//KM tariff calculation
	function setCustomKM_Tariff(whom)
	{
		var basekm=$('.base'+whom+'km').val();
		var basekmamount=$('.base'+whom+'kmamount').val();
		var tariff_id=$('#trip-tariff').val();
		var adlkmrate=$('.adt'+whom+'kmrate').val();
		var totalkm=$('.totalkm').val();
		var noofdays=$('.daysno').val();
		
		if(totalkm != '' && Number(totalkm) > 0 && tariff_id!=-1){
	
	
			if(totalkm!='' && basekm!=''){
				if(Number(noofdays)==1){
					if(Number(totalkm) <= Number(basekm)){

						$('.adt'+whom+'km').val('');
						$('.adl'+whom+'kmrate').val('');
						$('.aditional'+whom+'kmamount').val('');
						$('.total'+whom+'kmamount').val(basekmamount);
			
				
					}else{
					
						var adtkm = Number(totalkm)-Number(basekm);
						if(Number(adtkm)>0){
							$('.adt'+whom+'km').val(adtkm);
						}else{
							$('.adt'+whom+'km').val('');
						}

						if(basekmamount!='' && adlkmrate!=''){
							if(Number(adtkm)>0){
								var adtamt = Number(adtkm)*Number(adlkmrate);
								total_tarif = Number(basekmamount)+Number(adtamt);
								$('.aditional'+whom+'kmamount').val(adtamt);
								$('.total'+whom+'kmamount').val(total_tarif);
							

							}else{

								$('.aditional'+whom+'kmamount').val(adtamt);
								$('.total'+whom+'kmamount').val(total_tarif);
	
							
							}
						}
					}
				}else if(Number(noofdays)>1){
					mutipledaysbasekm=Number(basekm)*Number(noofdays);
					mutipledaysbasekmamount=Number(basekmamount)*Number(noofdays);
					if(Number(totalkm) <= Number(mutipledaysbasekm)){
						$('.adt'+whom+'km').val('');
						$('.adl'+whom+'kmrate').val('');
						$('.aditional'+whom+'kmamount').val('');
						$('.total'+whom+'kmamount').val(mutipledaysbasekmamount);

				
					}else{
					
						var adtkm = Number(totalkm)-Number(mutipledaysbasekm);
						if(Number(adtkm)>0){
							$('.adt'+whom+'km').val(adtkm);
						}else{
							$('.adt'+whom+'km').val('');
						}

						if(mutipledaysbasekmamount!='' && adlkmrate!=''){
							if(Number(adtkm)>0){
							var adtamt = adtkm*adlkmrate;
							total_tarif = Number(mutipledaysbasekmamount)+Number(adtamt);
								$('.aditional'+whom+'kmamount').val(adtamt);
								$('.total'+whom+'kmamount').val(total_tarif);
							

							}else{

								$('.aditional'+whom+'kmamount').val(adtamt);
								$('.total'+whom+'kmamount').val(total_tarif);
	
							
							}
						}
					}

				}
			}
			compareCustomAmounts(whom);
			calculatePaymentAmount('vehicle');
			calculatePaymentAmount('driver');
			//$('.kmhr').val( km_flag);
			//setTotalAmount();
		
		}else{
			//clearAllTariff();
		}
	}
	//Hours tariff calculation
	function setCustomHR_Tariff(whom)
	{
	
		var basehrs=$('.base'+whom+'hrs').val();
		var tariff_id=$('#trip-tariff').val();
		var basehrsamount=$('.base'+whom+'hrsamount').val();
		var adlhrrate=$('.adt'+whom+'hrrate').val();
		var triptime=$('.triptime').val();
	
		if(triptime != '' && tariff_id!=-1){

			if(triptime!='' && basehrs!=''){
				if(basehrs.indexOf(':')>-1 ){
					basehrs=basehrs.split(':');
					base=basehrs[0]+'.'+basehrs[1];
				}else{
					base=basehrs;

				}
				triptime=triptime.split(':');

				tottime=triptime[0]+'.'+triptime[1];
				if(Number(tottime) <= Number(base)){
					total_tarif = Number(basehrsamount);
					$('.adt'+whom+'hrs').val('');
					$('.adl'+whom+'hrrate').val('');
					$('.aditional'+whom+'hramount').val('');
					$('.total'+whom+'hramount').val(basehrsamount);
				
				}else{
				
					$('.adl'+whom+'hrrate').removeAttr('disabled');
					var adthrs=Number(tottime)-Number(base); //time difference
				
					adthrs=adthrs.toFixed(2);
					adthrsnew=adthrs.replace(/\./g, ':');
				

					if(basehrsamount!='' && adlhrrate!=''){

						var adtamt = calculateHrsAmount(adthrsnew,adlhrrate);
						total_tarif = Number(basehrsamount)+Number(adtamt);
					}
				
					if(Number(adthrs)>0){
						$('.adt'+whom+'hrs').val(adthrsnew);
						$('.aditional'+whom+'hramount').val(adtamt);
						$('.total'+whom+'hramount').val(total_tarif);
					
					}else{
						$('.adt'+whom+'hrs').val('');
						$('.aditional'+whom+'hramount').val('');
						$('.total'+whom+'hramount').val('');
					
					
					}	
				}	
			
			}
			compareCustomAmounts(whom);
			calculatePaymentAmount('vehicle');
			calculatePaymentAmount('driver');
			//$('.kmhr').val(hr_flag);
			//setTotalAmount();

		}else{
			//clearAllTariff();
		}
	}


	function compareCustomAmounts(whom){
		var totalhramount=$('.total'+whom+'hramount').val();
		var totalkmamount=$('.total'+whom+'kmamount').val();
		var noofdays=$('.daysno').val();
		if(Number(noofdays)==1){
			if(totalhramount!='' && totalkmamount!=''){
				if(Number(totalhramount)>Number(totalkmamount)){
					$('.tot'+whom+'amount-radio-container2 > .iradio_minimal > .iCheck-helper').trigger('click');
				}else if(Number(totalhramount)<Number(totalkmamount)){
					$('.tot'+whom+'amount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
				}

			}
		}else if(Number(noofdays)>1){
			$('.tot'+whom+'amount-radio-container1 > .iradio_minimal > .iCheck-helper').trigger('click');
		}
	}





});

