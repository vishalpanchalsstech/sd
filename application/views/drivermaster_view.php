<?php if(isset($msg)){ echo $msg; } ?>
<style>
table{
  margin: 0 auto;
  width: 100%;
  clear: both;
  border-collapse: collapse;
  table-layout: fixed; // ***********add this
  word-wrap:break-word; // ***********and this

}
table td{
  word-wrap:break-word; // ***********and this
}


  

</style>
<div class="page-inner">
		<div class="page-title">
                        <h3 class="breadcrumb-header">Driver Master List</h3>
					</div>
	<div class="panel panel-default">
		<h3 class="panel-title">
		<a href="<?php echo base_url(); ?>drivermaster/driver_insert"><button class="btn btn-danger">Add Driver</button></a>
		</h3>
		<div class="panel-body">
			<!--<div class="table-responsive" style="overflow-x:none !important;">-->
			<div>
				<table id="recordshow" class="display table" style="width: 100%;cellspacing: 0;">
				<thead>
					<tr>
						<th class="text-center" style="width:4%!important;">Id</th>
						<th class="text-center" style="width:7%!important;">Name</th>
						<th class="text-center" style="width:15%!important;">Email</th>
						<th class="text-center" style="width:8%!important;">Status</th>
						<th class="text-center" style="width:11%!important;">Email Verify</th>
						<!--<th>Phoneno</th>-->
						<th class="text-center" style="width:11%!important;">Vehicle Type</th>
						<th class="text-center" style="width:13%!important;">Vehicle Number</th>
						<th class="text-center" style="width:10%!important;">Licence No</th>
						<th style="width:11%!important;">Profile Image</th>
						<!--<th>DocumentImage</th>-->
						<!--<th>Token</th>-->
						<th class="text-center" style="width:10%!important;">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($driver_data))
						{ $i = 1;
							foreach($driver_data as $row)
							{ //echo "<pre>";print_r($driver_data);exit();
							 	$Email_varify = $row->UserEnable;
								if($Email_varify == 1)
								{
									$Email_varify = '<span class="label label-success" style="font-size:12px;top:10px;">Verified</span>';
								}
								else
								{
									$Email_varify = '<span class="label label-danger" style="font-size:12px;top:10px;">Unverified</span>';
								}
								$WorkingStatus = $row->WorkingStatus;
								
								if($WorkingStatus == 1)
								{
									$WorkingStatus = '<span class="label label-primary" style="font-size:12px;top:10px;">Online</span>';
								}
								else
								{
									$WorkingStatus = '<span class="label label-danger" style="font-size:12px;top:10px;">Offline</span>';
								}
				?>
					<tr>
						<td class="text-center"><?php echo $i; ?></td>
						<td class="text-center"><?php echo $row->Name;?></td>
						<td class="text-center" style='word-wrap:breakword;'><?php echo $row->Email;?></td>
						<td class="text-center"><?php echo $WorkingStatus;?></td>
						<td class="text-center"><?php echo $Email_varify;?></td>
						<!--<td><?php echo $row->Phoneno; ?></td>-->
						<td class="text-center"><?php echo $row->VehicleType; ?></td>
						<td class="text-center"><?php echo $row->VehicleNumber; ?></td>
						<td class="text-center"><?php echo $row->LicenceNo; ?></td>
						<?php if(isset($row->ProfileImage) && !empty($row->ProfileImage)){ ?>
						<td class="text-center"><img style='width:50px;height:50px;' src="<?php echo base_url().$row->ProfileImage; ?>" /></td>
						<?php }else{ ?>
						<td class="text-center"><img style='width:50px;height:50px;' src="<?php echo base_url() ?>assets/images/icon-driver.png" /></td>
						<?php } ?>
						<!--<td><img style='width:50px;height:50px;' src="<?php echo base_url().$row->DocumentImage; ?>" /></td>-->
						<!--<td><?php //echo $row->Token; ?></td>-->
						<td class="text-center">
						<a href="<?php echo base_url(); ?>drivermaster/Edit/<?php echo $row->Id;?>">
						 <button class="btn btn-default btn-rounded btn-sm" ><span class="fa fa-pencil" ></span></button></a>
							<?php //if($row->Id != '1'){ ?>
							<button data-target="#mb-remove-row"  data-toggle="modal" class="btn btn-danger btn-rounded btn-sm" id="delete-data" controller="<?php echo base_url().'drivermaster/Delete' ?>" onClick="delete_row_new('<?php echo $row->UserId;?>');"><span class="fa fa-times"></span></button>
							<?php // } ?>
						</td>
					</tr>
							
				<?php $i++; } } ?>
				</tbody>
				</table>
			</div>
		</div>
    </div>
					 
</div>								 
	