<style>

.popover-title{
text-align:center;

}
.popover.confirmation.fade.top.in{background:#eeeeee none repeat scroll 0 0 !important;}
.step-controls{margin-bottom:20px !important;}
</style>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap-confirmation.min.js"></script> 	
		<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		
<script>
    webshims.setOptions('forms-ext', {types: 'date'});	
	webshims.polyfill('forms forms-ext');
	$.webshims.formcfg = {
    en: {
        dFormat: '-',
        dateSigns: '-',
        patterns: {
                 d:"dd-mm-yy"
             }
        }
  };

  $.webshims.activeLang('en');	
</script>
<!-- PAGE CONTENT WRAPPER -->
		<!---------------edit User form start-------------------------------->
							<?php if(isset($msg)){ echo $msg; } ?>

		<?php if(isset($EditData)){
			
			//echo '<pre>';print_r($EditData);exit;
		?>
		<div class="page-inner">
			<div class="page-title">
				<h3 class="breadcrumb-header">Edit Job History Simple Form</h3>
			</div>
			<div class="page-content-wrap">
				<form id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>jobhistory/update">
				<input type="hidden" name="UpdateId" value="<?php echo $EditData[0]->Id; ?>">
					<div class="panel panel-default">
						
						<div class="panel-heading clearfix">
                                    <h4 class="panel-title">Pick Up Details</h4>
                    </div>
					<?php if($roleid==1){ ?>
					<div class="form-group">
						<label class="col-md-2 control-label">Company</label>
						<div class="col-md-5">      
							<select class="form-control select" name="CompanyId" id="CompanyId">
							  <option disabled="disabled">Select Company</option>
							  <?php foreach($Getcompany as $comp){ ?>
							  <option value="<?php echo $comp->Id;?>"<?php if($EditData[0]->CompanyId == $comp->Id){ echo 'selected'; } ?>><?php echo $comp->Name;?></option>
							  <?php } ?>
							</select>
						</div>								
					</div>
					<?php } ?>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Job Status</label>
						<div class="col-md-5">      
							<select class="form-control select" name="jobstatus" id="jobstatus">
							  <option disabled="disabled">Select JobStatus</option>
							  <?php foreach($jobstatus as $jbstatus){  ?>
							  <option value="<?php echo $jbstatus->Id;?>"<?php if($EditData[0]->JobStatus == $jbstatus->Id){ echo 'selected'; } ?>><?php echo $jbstatus->StatusName;?></option>
							  <?php } ?>
							</select>
						</div>								
					</div>
					
					<?php 
					$pkup_dtl = json_decode($EditData[0]->PickupDetail);
					$drp_dtl = json_decode($EditData[0]->DropoffDetail);
						   //echo "<pre>";print_r($EditData[0]->dropoffDetail);exit();
					?>
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="Name" name="pickupname" placeholder="Name" value="<?php echo $pkup_dtl->name;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="phone" name="pickupphone" placeholder="Phone" value="<?php echo $pkup_dtl->phone;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea name="pickupaddress" id="pickupaddress" placeholder="Address" type="text" value="" rows="3" cols="30" dir="ltr" ><?php echo $pkup_dtl->address;?></textarea>
						</div>
					</div>
					<div class="panel-heading clearfix">
                                    <h4 class="panel-title">DropOff Details</h4>
                    </div>
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffname" name="dropoffname" placeholder="Name" value="<?php echo $drp_dtl->name;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffphone" name="dropoffphone" placeholder="Phone" value="<?php echo $drp_dtl->phone;?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea name="dropoffaddress" id="dropoffaddress" placeholder="Address" value="" rows="3" cols="30" dir="ltr" ><?php echo $drp_dtl->address;?></textarea>
						</div>
					</div>
					
					
					<div class="panel-heading clearfix">
                                    <h4 class="panel-title">Scheduled Times</h4>
                    </div>
					<div class="form-group">
						<label class="col-md-2 control-label">Pick Up Details</label>
						<div class="col-md-5">
						  <input id="appt-time" class="form-control col-md-5 pkuptime" type="time" name="pickuptime" value="<?php echo date('H:i');?>">
						  <?php //$time=date('H:i');?>
						  <!--<a style="color:blue;cursor:pointer;" onclick="settime();">NOW</a>-->
						</div>
						<div class="col-md-5">
							<input id="date" type="date" value="<?php echo date('Y-m-d');?>" name="pickupdate" class="form-control col-md-5">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">DropOff Details</label>
						
						<div class="col-md-5">
						  <label class="control-label">Earliest Time</label><br>
						  <input id="appt-time" class="form-control col-md-5" type="time" name="earliestdrptime" value="<?php echo date('H:i');?>">
						  <input id="date" type="date" value="<?php echo date('Y-m-d');?>" name="earliestdrpdate" class="form-control col-md-5">
						</div>
						<div class="col-md-5">
						  <label class="control-label">Latest Time</label><br>
						  <input id="appt-time" class="form-control col-md-5" type="time" name="latestdrptime" value="13:30">
							<input id="date" type="date" value="<?php echo date('Y-m-d');?>" name="latestdrpdate" class="form-control col-md-5">
						</div>
						
					</div>
					
					
					
						
					<div class="form-group">
						  <div class="col-md-offset-2 col-sm-1">
							<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Update</button>
						  </div>
						  <div class="col-md-offset-1 col-sm-1">
							<a href="<?php echo base_url().'jobhistory'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
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
		<div class="page-inner">
				<div class="page-title">
					<h3 class="breadcrumb-header">Insert Job History Simple Form</h3>
				</div>
			<form id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>jobhistory/insert">
				<div class="panel panel-default">
					<div class="panel-heading clearfix">
                                    <h4 class="panel-title">Pick Up Details</h4>
                    </div>
					<?php if($roleid==1){ ?>
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
					<?php } ?>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Job Status</label>
						<div class="col-md-5">      
							<select class="form-control select" name="jobstatus" id="jobstatus">
							  <option value="">Select JobStatus</option>
							  <?php foreach($jobstatus as $jbstatus){  ?>
							  <option value="<?php echo $jbstatus->Id;?>"><?php echo $jbstatus->StatusName;?></option>
							  <?php } ?>
							</select>
						</div>								
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="Name" name="pickupname" placeholder="Name" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="phone" name="pickupphone" placeholder="Phone" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea name="pickupaddress" id="pickupaddress" placeholder="Address" type="text" value="" rows="3" cols="30" dir="ltr" ></textarea>
						</div>
					</div>
					<div class="panel-heading clearfix">
                                    <h4 class="panel-title">DropOff Details</h4>
                    </div>
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffname" name="dropoffname" placeholder="Name" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffphone" name="dropoffphone" placeholder="Phone" >
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea name="dropoffaddress" id="dropoffaddress" placeholder="Address" value="" rows="3" cols="30" dir="ltr" ></textarea>
						</div>
					</div>
					
				<div class="form-group">
				
					<div class="form-group">
						<div class="col-md-offset-2 col-sm-1">
							<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Submit</button>
						</div>
						<div class="col-md-offset-1 col-sm-1">
							<a href="<?php echo base_url().'jobhistory'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
						</div>
					</div>
				
				
				</div>
				
				</div>
			</form>
		
				<!-------------User insert form end--------------------------------->
	<?php } ?>
		
	<script type="text/javascript">
		var jvalidate = $("#jvalidate").validate({
			//ignore: [],
			rules: {
				pickupname: {
					required: true,
				},
				pickupphone: {
					required: true,
				},
				dropoffname: {
						required: true,
				},
				dropoffphone: {
						required: true,
				},
				pickupaddress:{
                                required:true,
                                //minlength:8
                              },
				dropoffaddress:{
                                  required:true,
                               //   minlength:8
                               }
				
				
			}        
		});
		
		$(function () 
		{
		  $('[data-toggle=Regenerate_confirmation]').confirmation({
		  rootSelector: '[data-toggle=Regenerate_confirmation]',
		  container: 'body',
		  title: 'Are you sure You want to Regenerate Key ?',
		   onConfirm: function() 
		   {
			 var flag = 'edit';
			 $.ajax({
			  type: "POST",
			  url:base_url+"usermaster/re_generatekey/",
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
	
	
	