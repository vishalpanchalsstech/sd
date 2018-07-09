<?php if(isset($msg)){ echo $msg; } ?>
	<div class="page-inner">
		<div class="page-title">
            <h3 class="breadcrumb-header">Company <?php if($roleid != '3' ){ ?>Driver <?php } ?> List</h3>
		</div>
		<div class="panel panel-default">
			<h3 class="panel-title">
			<?php if($roleid != '3' ){ ?><button type="button" class="btn btn-danger" data-toggle="modal" data-target="#adddriver" >Add Driver</button>
			<?php } ?>
			</h3>
			<div class="panel-body">
				<div class="table-responsive">
					<table id="recordshow" class="display table" style="width: 100%; cellspacing: 0;">
					<thead>
						<tr>
							<th class="text-center">Id</th>
							<th class="text-center" <?php if($roleid == '2' ){ echo 'style="display:none;"'; }?>>Company</th>
							<?php if($roleid != '3' ){ ?> 
							<th class="text-center">Name</th>
							<th class="text-center">Email</th>
							<th class="text-center">Priority</th>
							<th class="text-center">Action</th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php if(isset($GetcdData))
							{ $i = 1;
								foreach($GetcdData as $row)
								{
					?>
						<tr>
							<td class="text-center"><?php echo $i; ?></td>
							<td class="text-center" <?php if($roleid == '2' ){ echo 'style="display:none;"'; }?>><?php echo $row->companyname; ?></td>
							<?php if($roleid != '3' ){ ?> 
							<td class="text-center"><?php echo $row->name; ?></td>
							<td class="text-center"><?php echo $row->email; ?></td>
							<td class="text-center"><select  onchange="changePriority('<?php echo $row->Id; ?>')"class="form-control select" name="priority<?php echo $row->Id; ?>" id="priority<?php echo $row->Id; ?>">
							  <?php for($s=1;$s<=5;$s++){ ?>
							  <option value="<?php echo $s;?>" <?php if($row->Priority == $s){ echo 'selected'; } ?>><?php echo $s;?></option>
							  <?php } ?>
							</select></td>
							<td class="text-center">
							<button data-target="#mb-remove-row" data-toggle="modal" class="btn btn-danger btn-rounded btn-sm" id="delete-data" controller="<?php echo base_url().'companydriver/Delete' ?>" onClick="delete_row_new('<?php echo $row->Id;?>');"><span class="fa fa-times"></span></button>						
							</td>
							<?php } ?>
						</tr>	
					<?php $i++; } } ?>
					</tbody>
					</table>
				</div>
			</div>
		</div>		 
	</div>
	<div class="modal fade" id="adddriver" tabindex="-1" role="dialog" aria-labelledby="adddriver">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="exampleModalLabel">Add Driver</h4>
      </div>
	 <form class="form-horizontal" id="cdform" class="form-horizontal" method="post" a>
      <div class="modal-body">
       <div id="set_error_msg"></div>
		<?php if($roleid == '1') { ?>
			<div id="set_error_msg"></div>
			<div class="form-group">
						<label class="col-md-3 control-label">Company</label>
						<div class="col-md-9">      
							<select class="form-control select" name="CompanyId" id="CompanyId">
							  <option value="">Select Company</option>
							  <?php foreach($Getcompany as $comp){ ?>
							  <option value="<?php echo $comp->Id;?>"><?php echo $comp->Name;?></option>
							  <?php } ?>
							</select>
						</div>								
		</div>
		<?php }else{ ?>
		<input type="hidden" name="CompanyId" id="CompanyId" value="<?php echo $companyid; ?>">	
		<?php } ?>
          <div class="form-group">
            <label for="recipient-name" class="control-label col-md-3">Email</label>
			<div class="col-md-9"> 
           <input class="form-control" id="Email" name="Email" placeholder="Email" type="email">
			</div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" id="adddriver" class="btn btn-primary">Add Driver</button>
      </div>
	   </form>
    </div>
  </div>
</div>
<script type="text/javascript">
		var jvalidate = $("#cdform").validate({
			ignore: [],
			rules: {
				
				Email: {
						required: true,
						email: true,
				},
				CompanyId:{
					required: true
				}
				
			},
			submitHandler: function(form) {
					var data = $("#cdform").serialize();
					$.ajax({
						url:"<?php echo base_url(); ?>companydriver/insert",  
						type:'post',
						data:data,
							success:function(result){
							 var data_result = jQuery.parseJSON(result);
							 var status = data_result.status;
							
							 if(status==1){
								$('#Email').val('');
								location.reload();
							 }else{
							 $('#set_error_msg').html(data_result.msg); 
							 }
							 
							}
						});
						
					}	
		});
		
function changePriority(row) {
	var id = row;
	var priorityget = "#priority"+id;
    var priority = $(priorityget).val();
	if((id!='') && (priority!='')){
		//alert(id);
		//alert(priority);
		$.ajax({
			url:"<?php echo base_url(); ?>companydriver/update",  
			type:'post',
			data:{
				id:id,
				priority:priority,
			},
					success:function(result){
						 var data_result = jQuery.parseJSON(result);
						 var status = data_result.status;
						 location.reload();
						
					}
			});
	}
}
   
 
</script>