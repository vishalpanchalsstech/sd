<!-- PAGE CONTENT WRAPPER -->
		<!---------------edit User form start-------------------------------->
							<?php if(isset($msg)){ echo $msg; } ?>

		<?php if(isset($EditData)){
			
		//echo "<pre>";print_r($EditData);exit(); 
		?>
		<div class="page-inner">
			<div class="page-title">
				<h3 class="breadcrumb-header">User Master Edit</h3>
			</div>
			<div class="page-content-wrap">
				<form id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>usermaster/update">
				<input type="hidden" name="UpdateId" value="<?php echo $EditData[0]->Id; ?>">
					<div class="panel panel-default">
						<div class="form-group">
							<label class="col-md-2 control-label">Company</label>
							<div class="col-md-5">                                        
								<select class="form-control select" name="CompanyId" id="CompanyId">
								  <option value="">Select Company</option>
								  <?php foreach($Getcompany as $comp){ ?>
								  <option value="<?php echo $comp->Id;?>" <?php if($EditData[0]->CompanyId == $comp->Id){echo 'selected'; } ?>><?php echo $comp->Name;?></option>
								  <?php } ?>
								</select>
							</div>								
						</div>	
						<div class="form-group">
							<label class="col-md-2 control-label">Name</label>
							<div class="col-md-5">
							  <input type="text" class="form-control" id="Name" name="Name" placeholder="Name" value="<?php echo $EditData[0]->Name; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Email</label>
							<div class="col-md-5">
							  <input type="email" class="form-control" id="Email" name="Email" placeholder="Email" value="<?php echo $EditData[0]->Email; ?>" readonly>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Password</label>  
							<div class="col-md-5">
								<input type="password" class="form-control" id="Password"  name="Password" value="<?php echo $EditData[0]->Password;?>" minlength="6"/>
								<input type="hidden" name="lasspass" value="<?php echo $EditData[0]->Password; ?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label">Token</label>  
							<div class="col-md-5">
								<input type="text" class="form-control" name="Token" id="Token" value="<?php echo $EditData[0]->Token;?>" readonly>
							</div>
<!--				            <input class="btn btn-danger" data-toggle="Regenerate_confirmation" id="autogenerateid" value="Regenerate" data-original-title="" title=""  type="button"></label>-->
						</div>
						<div class="form-group">
						  <div class="col-md-offset-2 col-sm-1">
							<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Update</button>
						  </div>
						  <div class="col-md-offset-1 col-sm-1">
							<a href="<?php echo base_url().'usermaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
						  </div>
						</div>
					</div>
				</form>
			</div>			
		</div>			
				<?php } else { ?>
				<!------------- Edit User Form End ------------------>
				
				<!------------- User Insert Form Start --------------->
				<?php //echo '<pre>';print_r($Getcompany);exit;?>
					<?php if(isset($message)){ echo $message; } ?>
		<div class="page-inner">
				<div class="page-title">
					<h3 class="breadcrumb-header">User Master Insert</h3>
				</div>
			<form id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>usermaster/insert">
				<div class="panel panel-default">
					<div class="form-group">
						<label class="col-md-2 control-label">Company</label>
						<div class="col-md-5">      
							<select class="form-control select" name="CompanyId" id="CompanyId">
							  <option value="">Select Company</option>
							  <?php foreach($Getcompany as $comp){ ?>
							  <option value="<?php echo $comp->Id;?>"><?php echo $comp->Name;?></option>
							  <?php } ?>
							</select>
						</div>								
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="Name" name="Name" placeholder="Name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Email</label>
						<div class="col-md-5">
						  <input type="email" class="form-control" id="Email" name="Email" placeholder="Email">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Password</label>
						<div class="col-md-5">
						  <input type="Password" class="form-control" id="Password" name="Password" placeholder="Password" minlength="6">
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-sm-1">
							<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Submit</button>
						</div>
						<div class="col-md-offset-1 col-sm-1">
							<a href="<?php echo base_url().'usermaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
						</div>
					</div>
				</div>
			</form>
		</div>
				<!-------------User insert form end--------------------------------->
	<?php } ?>
	        <script src="<?php echo base_url() ?>assets/js/bootstrap.js"></script>
	        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
	        <script src="<?php echo base_url() ?>assets/js/bootstrap-confirmation.min.js"></script>
	<script type="text/javascript">
		var jvalidate = $("#jvalidate").validate({
			ignore: [],
			rules: {
				CompanyId: {
					required: true,
				},
				Name: {
					required: true,
				},
				Email: {
						required: true,
						email: true
				},
				Password: {
					required: true,
					Maxlength:6,
				}
			}        
		});
		$(function () 
	{
		$('[data-toggle=Regenerate_confirmation]').confirmation({
		rootSelector: '[data-toggle=Regenerate_confirmation]',
		container: 'body',
		title: 'Are you sure You want to Regenerate Key',
			onConfirm: function() 
			{
			  var flag = 'edit';
			  $.ajax({
					 type: "POST",
					 url:baseurl + "general/re_generatekey/",
					 data:{"flag":flag},
					 datatype: "JSON",
					 success: function(data)
					{
						$("#Token").val(data);
					},
					error: function() 
					{
						alert('Error occured');
					}				 
			    });
			},
		});

	});

	</script>
	