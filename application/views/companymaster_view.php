<?php if(isset($msg)){ echo $msg; } ?>
<div class="page-inner">
		<div class="page-title">
                        <h3 class="breadcrumb-header">Company Master List</h3>
					</div>
	<div class="panel panel-default">
		<h3 class="panel-title">
		<a href="<?php echo base_url(); ?>companymaster/company_insert"><button class="btn btn-danger">Add Company</button></a>
		</h3>
		<div class="panel-body">
			<div class="table-responsive">
				<table id="recordshow" class="display table" style="width: 100%; cellspacing: 0;">
				<thead>
					<tr>
						<th class="text-center">Id</th>
						<th class="text-center">Name</th>
						<th class="text-center">Email</th>
						<th class="text-center">Address</th>
						<th class="text-center">Logo</th>
						<th class="text-center">Action</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($company_data))
						{ $i = 1;
							foreach($company_data as $row)
							{ 
				?>
					<tr>
						<td class="text-center"><?php echo $i; ?></td>
						<td class="text-center"><?php echo $row->Name; ?></td>
						<td class="text-center"><?php echo $row->Email; ?></td>
						<td class="text-center"><?php echo $row->Address; ?></td>	
						<?php if(isset($row->Logo)){?>
						<td class="text-center"><img style='width:50px;height:50px;' src="<?php echo base_url().$row->Logo; ?>" /></td>
						<?php }elseif($row->Logo){ ?>
						<td class="text-center"><?php echo "No Image"; ?></td>
						<?php }?>
						<td class="text-center">
						<a href="<?php echo base_url(); ?>companymaster/Edit/<?php echo $row->Id;?>"> <button class="btn btn-default btn-rounded btn-sm"><span class="fa fa-pencil"></span></button></a>
						<?php if($row->Id != '1'){ ?>
						<button data-target="#mb-remove-row" data-toggle="modal" class="btn btn-danger btn-rounded btn-sm" id="delete-data" controller="<?php echo base_url().'companymaster/Delete' ?>" onClick="delete_row_new('<?php echo $row->Id;?>');"><span class="fa fa-times"></span></button>
						<?php } ?>
						
						</td>
					</tr>
							
				<?php $i++; } } ?>
				</tbody>
				</table>
			</div>
		</div>
    </div>
					 
</div>								 
	