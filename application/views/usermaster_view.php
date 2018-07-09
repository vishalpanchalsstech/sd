<?php if(isset($msg)){ echo $msg; } ?>
	<div class="page-inner">
		<div class="page-title">
            <h3 class="breadcrumb-header">User Master List</h3>
		</div>
		<div class="panel panel-default">
			<h3 class="panel-title">
			<a href="<?php echo base_url(); ?>usermaster/user_insert"><button class="btn btn-danger">Add User</button></a>
			</h3>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="recordshow" class="display table" style="width: 100%; cellspacing: 0;">
					<thead>
						<tr>
							<th class="text-center">Id</th>
							<th class="text-center">Company</th>
                            <th class="text-center">UserType</th>
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($GetuserData))
							{ $i = 1;
								foreach($GetuserData as $row)
								{
					?>
						<tr>
							<td class="text-center"><?php echo $i; ?></td>
							<td class="text-center"><?php echo $row->companyname; ?></td>
                            <?php if($row->RoleId==1){ ?>
                                <td class="text-center"><span class="label label-info" style="font-size:11px;top:10px;">Super Admin</span></td>
                            <?php } ?>
                            <?php if($row->RoleId==2){ ?>
                                <td class="text-center"><span class="label label-success" style="font-size:11px;top:10px;">Admin</span></td>
                            <?php } ?>
							<td class="text-center"><?php echo $row->Name; ?></td>
							<td class="text-center"><?php echo $row->Email; ?></td>
							<td class="text-center">
							<a href="<?php echo base_url(); ?>usermaster/Edit/<?php echo $row->Id;?>"> <button class="btn btn-default btn-rounded btn-sm"><span class="fa fa-pencil"></span></button></a>
							<button data-target="#mb-remove-row" data-toggle="modal" class="btn btn-danger btn-rounded btn-sm" id="delete-data" controller="<?php echo base_url().'usermaster/Delete' ?>" onClick="delete_row_new('<?php echo $row->Id;?>');"><span class="fa fa-times"></span></button>						
							</td>
						</tr>	
					<?php $i++; } } ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>		 
	</div>