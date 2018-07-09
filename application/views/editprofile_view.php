<!-- PAGE CONTENT WRAPPER -->
<script type="text/javascript">

    $(document).ready(function(){
		
            var availableCountry = [ 
                <?php  foreach ($country_details as $country) { 
                   echo '"'.$country->Name.'-'.$country->SortName.'",';
                } ?>
            ];   
            $("#country").autocomplete({
                  source: function(req, response) {
                  var results = $.ui.autocomplete.filter(availableCountry, req.term);
                  response(results.slice(0, 8));//for getting 5 results
                }
            });   
      });
</script> 
                
	
				<?php if(isset($msg)){ echo $msg; } ?>
				<!---------------edit form start-------------------------------->
				<?php  	if(isset($Edit_Profile))
						{
							//echo "<pre>";print_r($Edit_Profile);exit;
				?>
	<div class="page-inner">
        <div class="page-title"><h3 class="breadcrumb-header">Driver Edit Profile</h3></div>
		<div class="page-content-wrap">
			<form id="driver_update_Form" name="driver_update_Form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>editprofile/updateProfileProcess">
				<!--<input type="hidden" name="userId" id="hiddenUserId" value="<?php //echo $session_data['userid'];?>">-->
				
				<input type="hidden" name="UserId" value="<?php echo $Edit_Profile[0]->UserId; ?>">
				<input type="hidden" name="driverId" value="<?php echo $Edit_Profile[0]->Id; ?>">
				<div class="panel panel-default">
					
					<div class="form-group">
						<label class="col-md-2 control-label">Name :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Name" name="name" placeholder="name" value="<?php echo $Edit_Profile[0]->Name; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Email :</label>
						<div class="col-md-5">
						  <input type="email" class="form-control" id="email" name="email" placeholder="email" readonly  value="<?php echo $Edit_Profile[0]->Email; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Password :</label>  
						<div class="col-md-5">
							<input type="password" class="form-control" id="Password"  name="Password" placeholder="Password" minlength="6" value="<?php echo $Edit_Profile[0]->Password; ?>">
							<input type="hidden" name="New_pass" id="New_pass" value="<?php echo $Edit_Profile[0]->Password; ?>">
							<!--<input autocomplete=new-password type="Password" class="form-control" name="Password" id="Password" value="">-->
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Confirm Password :</label>  
						<div class="col-md-5">
							<input type="password" class="form-control" id="Conf_Password"  name="Conf_Password" placeholder="Password" minlength="6" value="<?php echo $Edit_Profile[0]->Password; ?>">
							<!--<input autocomplete=new-password type="Password" class="form-control" name="Conf_Password" id="Conf_Password" minlength="6" value="">-->
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Building :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Building" name="Building"  placeholder="Building" value="<?php echo $Edit_Profile[0]->Building; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Street :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Street" name="Street"  placeholder="Street*" value="<?php echo $Edit_Profile[0]->Street; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Country :</label>
						<div class="col-md-5 custom-country">
							<input type="text" id="country" autocomplete="off" class="form-control"  name="country" value="<?php echo $Edit_Profile[0]->Country; ?>"/>	
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Suburb :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="city" name="Suburb" autocomplete="off"  placeholder="Suburb*" value="<?php echo $Edit_Profile[0]->Suburb; ?>">
						</div>
						<div id="suburb-div" class="" style="display:none;">
							<ul></ul>
						</div> 
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">State/City :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="state" name="State"  placeholder="State/City*" value="<?php echo $Edit_Profile[0]->State; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Postcode :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="postalcode" name="Postcode"  placeholder="Postcode*" value="<?php echo $Edit_Profile[0]->Postcode; ?>">
						</div>
					</div>
					
					
					<div class="form-group">
						<label class="col-md-2 control-label">Profile Image :</label>  
						<div class="col-md-5">
							<input type="file" class="file-type" id="editfile2" name="editfile2" onchange="validatefile(this);">
							<div id="shfilerr"></div>
							<img style='width:100px;height:100px;' src="<?php echo base_url().$Edit_Profile[0]->ProfileImage; ?>" />
							<input type="hidden" name="uploadimg" value="<?php echo $Edit_Profile[0]->ProfileImage; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Token</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" name="Token" id="Token" value="<?php echo $Edit_Profile[0]->Token;?>" readonly>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone No :</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" id="PhoneNo"  name="PhoneNo" placeholder="Phoneno" minlength="10" maxlength="12" value="<?php echo $Edit_Profile[0]->Phoneno; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Licence No :</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" id="licenceNo"  name="licenceNo" placeholder="licenceNo" value="<?php echo $Edit_Profile[0]->LicenceNo; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Vehicle Name :</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" id="vehiclename"  name="vehiclename" placeholder="vehiclename" value="<?php echo $Edit_Profile[0]->VehicleType; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Vehicle No :</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" id="vehicleno"  name="vehicleno" placeholder="vehicleno" value="<?php echo $Edit_Profile[0]->VehicleNumber; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Document Image :</label>  
						<div class="col-md-5">
							<input type="file" class="file-type" id="editfile" name="editfile" onchange="svalidatefile(this);">
							<div id="shfilerr"></div>
							<img style='width:100px;height:100px;' src="<?php echo base_url().$Edit_Profile[0]->DocumentImage; ?>" />
							<input type="hidden" name="uploadimg2" value="<?php echo $Edit_Profile[0]->DocumentImage; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">WorkingStatus :</label>
						<div class="col-md-5 margin">                                        
							<label>
								<input type="radio" name="WorkingStatus" value="0" <?php if($Edit_Profile[0]->WorkingStatus==0){ echo 'checked';} ?> /> Offline
							</label>
							<label>
								<input type="radio" name="WorkingStatus"  value="1" <?php if($Edit_Profile[0]->WorkingStatus==1){ echo 'checked';} ?> /> OnLine
							</label>
						</div>								
					</div>
					
					
					<!--<div class="form-group">
						<div class="col-md-offset-2 col-sm-10">
						  <button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Update</button>
						</div>
					</div>-->
						<div class="form-group">
						  <div class="col-md-offset-2 col-sm-1">
							<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Update</button>
						  </div>
						</div>
				</div>
			</form>
		</div>			
	</div>			
	<?php } else { if(isset($EditData)) { ?>	
			
	<div class="page-inner">
		<div class="page-title">
			<h3 class="breadcrumb-header">User Profile</h3>
		</div>
		<div class="page-content-wrap">
			<form id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>editprofile/updateprofile">
				<div class="panel panel-default">
					<div class="form-group">
						<label class="col-md-2 control-label">Company</label>
						<div class="col-md-5">   
							<input type="text" class="form-control" value="<?php echo $EditData[0]->companyname; ?>" readonly>					
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
					</div>
					<div class="form-group">
					  <div class="col-md-offset-2 col-sm-1">
						<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Update</button>
					  </div>
					</div>
				</div>
			</form>
		</div>			
	</div>			
	<?php } } ?>
			
				
	
<script type="text/javascript">
			
			var base_url = "<?php echo base_url();?>";
	 
		$('#PhoneNo').keyup(function()
		{
			if (/\D/g.test(this.value))
			{
			  // Filter non-digits from input value.
			  this.value = this.value.replace(/\D/g, '');
			}
		});

/*********** Js for Check Email Duplication gose here @ Krushna @ **************/      

	var jvalidate = $("#jvalidate").validate({
			ignore: [],
			rules: {
				Name: {
					required: true,
				},
				Password: {
					required: true,
					Maxlength:6,
				}
			}        
		});
	
	$(function() 
	{	
		$("form[name='driver_update_Form']").validate({
			errorClass   : "error-message",
			errorElement : "p",
			
			rules:
			{
				name: "required",
			Password:{
						required: true,
					 },
		   
		   Conf_Password:{
							required: true,
							equalTo: "#Password"
						 },
				licenceNo: "required",
				PhoneNo: "required",
				file:{
						required: true
						
					 },
			   file2:{
						required: true
					 },
					 vehiclename:"required",
					 vehicleno:"required",
			},
			
			messages:
			{
			  name:"Name field is required.",
			  //email:"Email is required.",
			  licenceNo:"licenceNo field is required.",
			  file:"Document Image is required.",
			  file2:"Profile Image is required.",
			  vehiclename:"Vehicle Name is required.",
			  vehicleno:"Vehicle No is required.",
			  
			},
    
			submitHandler: function(form)
			{
				form.submit();
			}	
			
		});
	}); 
/***** Restrict File Extensions While upload File 2 ******/	
	function svalidatefile(oInput) 
	{
		var _validFileExtensions = [".jpg", ".jpeg",".png"]; 
		if (oInput.type == "file")
		{
			var sFileName = oInput.value;
			if (sFileName.length > 0) 
			{
				var blnValid = false;
				for (var j = 0; j < _validFileExtensions.length; j++) 
				{
					var sCurExtension = _validFileExtensions[j];
					if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) 
					{
						blnValid = true;
						$('#shfilerr2').html('');
						break;
					}
				}
				if (!blnValid) 
				{
					
					//alert("Sorry, " + sFileName + " is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
					$('#shfilerr2').html('<label id="Name-error" class="error" for="Name"><strong>Sorry file is invalid, allowed extensions are: .jpg, .jpeg, .png  </strong></label>');
					oInput.value = "";
					return false;
				}
			}
		}
			return true;
	}	
		
</script>

<style>

.page-content-wrap .form-horizontal .radio
{
	padding-top: 4px;
}
.margin
{
	margin-top:6px;
}

</style>