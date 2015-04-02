<div class="page-outer">
<fieldset class="body-border">
	<legend class="body-head">View Tariffs</legend>

	<!--tariff table start-->
	<div class="box-body table-responsive no-padding trips-table">
	<?php foreach($tariffs as $tariff){?>
		<table class="table-bordered" cellpadding="5px">
			<tr>
				<th width="200px"></th>
				<?php foreach($tariff['values'] as $td){?>
				<th width ="100px"><?php echo $td['vehicle_model']."<br/>".$td['vehicle_ac_type'];?></th>
				<?php }?>
			</tr>

			<tr>
				<th bgcolor="#DDD"><?php echo $tariff['label'];?></th>
				<?php foreach($tariff['values'] as $td){?>
				<td><?php echo $td['rate'];?></td>
				<?php }?>
			</tr>

			<tr>
				<th>Additional Kilometer Rate</th>
				<?php foreach($tariff['values'] as $td){?>
				<td><?php echo $td['additional_kilometer_rate'];?></td>
				<?php }?>
			</tr>

			<tr>
				<th>Additional Hour Rate</th>
				<?php foreach($tariff['values'] as $td){?>
				<td><?php echo $td['additional_hour_rate'];?></td>
				<?php }?>
			</tr>

			<tr>
				<th>Driver Bata</th>
				<?php foreach($tariff['values'] as $td){?>
				<td><?php echo $td['driver_bata'];?></td>
				<?php }?>
			</tr>

			<tr>
				<th>Night Halt</th>
				<?php foreach($tariff['values'] as $td){?>
				<td><?php echo $td['night_halt'];?></td>
				<?php }?>
			</tr>


		<table><br/>
	<?}?>
	</div>
	<!--tariff table end-->


</fieldset>
</div>
