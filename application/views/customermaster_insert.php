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
				<?php if(isset($EditData)){ 
				// echo "<pre>";print_r($EditData);exit(); 
				//echo "update"; exit;
				?>
	
	<div class="page-inner">
        <div class="page-title"><h3 class="breadcrumb-header">Customer Master Edit</h3></div>
		<div class="page-content-wrap">
			<form id="customer_update_Form" name="customer_update_Form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>customermaster/update">
				<input type="hidden" name="UserId" value="<?php echo $EditData[0]->UserId; ?>">
				<input type="hidden" name="customerId" value="<?php echo $EditData[0]->Id; ?>">
				<div class="panel panel-default">
					
					<div class="form-group">
						<label class="col-md-2 control-label">Name :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Name" name="name" placeholder="name" value="<?php echo $EditData[0]->Name; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Email :</label>
						<div class="col-md-5">
						  <input type="email" class="form-control" id="email" name="email" placeholder="email" readonly  value="<?php echo $EditData[0]->Email; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Password :</label>  
						<div class="col-md-5">
							<input type="password" class="form-control" id="Password"  name="Password" placeholder="Password" minlength="6" value="<?php echo $EditData[0]->Password; ?>">
							<input type="hidden" name="New_pass" id="New_pass" value="<?php echo $EditData[0]->Password; ?>">
							<!--<input autocomplete=new-password type="Password" class="form-control" name="Password" id="Password" value="">-->
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Confirm Password :</label>  
						<div class="col-md-5">
							<input type="password" class="form-control" id="Conf_Password"  name="Conf_Password" placeholder="Password" minlength="6" value="<?php echo $EditData[0]->Password; ?>">
							<!--<input autocomplete=new-password type="Password" class="form-control" name="Conf_Password" id="Conf_Password" minlength="6" value="">-->
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Building :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Building" name="Building"  placeholder="Building" value="<?php echo $EditData[0]->Building; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Street :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Street" name="Street"  placeholder="Street*" value="<?php echo $EditData[0]->Street; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Country :</label>
						<div class="col-md-5 custom-country">
							<input type="text" id="country" autocomplete="off" class="form-control"  name="country" value="<?php echo $EditData[0]->Country; ?>"/>	
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Suburb :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="city" name="Suburb" autocomplete="off"  placeholder="Suburb*" value="<?php echo $EditData[0]->Suburb; ?>">
						</div>
						<div id="suburb-div" class="" style="display:none;">
							<ul></ul>
						</div> 
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">State/City :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="state" name="State"  placeholder="State/City*" value="<?php echo $EditData[0]->State; ?>">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Postcode :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="postalcode" name="Postcode"  placeholder="Postcode*" value="<?php echo $EditData[0]->Postcode; ?>">
						</div>
					</div>
					
					<!--<div class="form-group">
						<label class="col-md-2 control-label">Profile Image :</label>  
						<div class="col-md-5">
							<input type="file" class="file-type" id="editfile" name="editfile" onchange="validatefile(this);">
							<div id="shfilerr"></div>
							<img style='width:100px;height:100px;' src="<?php echo base_url().$EditData[0]->ProfileImage; ?>" />
							<input type="hidden" name="uploadimg" value="<?php echo $EditData[0]->ProfileImage; ?>"/>
						</div>
					</div>-->
					<div class="form-group">
						<label class="col-md-2 control-label">Token</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" name="Token" id="Token" value="<?php echo $EditData[0]->Token;?>" readonly>
						</div>
						<!-- <input class="btn btn-danger" data-toggle="Regenerate_confirmation" id="autogenerateid" value="Regenerate" data-original-title="" title=""  type="button"></label> -->
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone No :</label>  
						<div class="col-md-5">
							<input type="text" class="form-control" id="PhoneNo"  name="PhoneNo" placeholder="Phoneno" minlength="10" maxlength="12" value="<?php echo $EditData[0]->Phoneno; ?>">
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
						  <div class="col-md-offset-1 col-sm-1">
							<a href="<?php echo base_url().'customermaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
						  </div>
						</div>
				</div>
			</form>
		</div>			
	</div>			
				<?php } else { ?>
				<!------------- Edit Form End ------------------>
				
				<!------------- Insert Form Start --------------->
				<?php //echo '<pre>';print_r($state);exit;?>
							<?php if(isset($msg)){ echo $msg; } ?>
			<div class="page-inner">
                    <div class="page-title">
                        <h3 class="breadcrumb-header">Customer Master Insert</h3>
					</div>
				<form id="customer_insert_Form" name="customer_insert_Form" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>customermaster/insert_customer_details">
				<div class="panel panel-default">
					
					<div class="form-group">
						<label for="Name" class="col-md-2 control-label">Name :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="name" name="name" placeholder="name">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Email address :</label>
						<div class="col-md-5">
							<input type="email" class="form-control" id="email" name="email" onchange="check_duplicate_email()">
							<p id="msg_dupli" class="error-message"></p>
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Password :</label>
						<div class="col-md-5">
							<input type="password" class="form-control" id="Password" name="Password"  minlength="6">
							<!--<input type="password" class="form-control" name="Password" id="Password" autocomplete="new-password">-->
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Confirm Password :</label>
						<div class="col-md-5">
							<input type="password" class="form-control" id="Conf_Password" name="Conf_Password" minlength="6" >
						</div>
						
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Building :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Building" name="Building"  placeholder="Building">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Street :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="Street" name="Street"  placeholder="Street*">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Country :</label>
						<div class="col-md-5 custom-country">
							<input type="text" id="country" autocomplete="off" class="form-control"  name="country"/>	
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Suburb :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="city" name="Suburb" autocomplete="off"  placeholder="Suburb*">
						</div>
						<div id="suburb-div" class="" style="display:none;">
							<ul></ul>
						</div> 
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">State/City :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="state" name="State"  placeholder="State/City*">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Postcode :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="postalcode" name="Postcode"  placeholder="Postcode*">
						</div>
					</div>
					
				<!--	<div class="form-group">
						<label class="col-md-2 control-label">Profile Image :</label>
						<div class="col-md-5">
							<input type="file" class="file-type" id="file" name="file" onchange="validatefile(this);">
							<div id="shfilerr"></div>
						</div>
					</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Phone No :</label>
						<div class="col-md-5">
							<input type="text" class="form-control" id="PhoneNo" name="PhoneNo"  minlength="10" maxlength="12">
						</div>
					</div>
					<div class="form-group">
					  <div class="col-md-offset-2 col-sm-1">
						<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Submit</button>
					  </div>
					  <div class="col-md-offset-1 col-sm-1">
						<a href="<?php echo base_url().'customermaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
					  </div>
					</div>
					
				</div>
				</form>
				</div>
				
				
				
				<!-------------insert form end--------------------------------->
				<?php } ?>
		
      <script src="<?php echo base_url() ?>assets/js/bootstrap.js"></script>
	        <script src="<?php echo base_url() ?>assets/js/bootstrap.min.js"></script>
	        <script src="<?php echo base_url() ?>assets/js/bootstrap-confirmation.min.js"></script>

<script type="text/javascript">
	var base_url = "<?php echo base_url();?>";
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
					 url:base_url+"general/re_generatekey/",
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
		$('#PhoneNo').keyup(function()
		{
			if (/\D/g.test(this.value))
			{
			  // Filter non-digits from input value.
			  this.value = this.value.replace(/\D/g, '');
			}
		});
		
 
 /*********** Driver Insert form validation @ Krushna @ **************/      
	
	$(function() 
	{	
		$("form[name='customer_insert_Form']").validate({
			errorClass   : "error-message",
			errorElement : "p",
			
			rules:
			{
				name: "required",
				email:{
						required: true,
						email: true
					  },
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
					 Street: "required",
					 country: "required",
					 Suburb:"required",
					 State:"required",
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
			  Street:"Street/city  Required",
			  country:"Country Required",
			  Suburb:"Suburb Required or not valid select from suggestion",
			  
			},
    
			submitHandler: function(form)
			{
				var dupli_emal = check_duplicate_email();
				
				if(dupli_emal == '1')
				{
					return false;
				}
				else
				{
					if($('form#customer_insert_Form .error-class').length < 1)
					{
						form.submit();
					}
					else
					{
							return false;
					}
				}	
			}	
			
		});
	}); 

		/*********** Js for Check Email Duplication gose here @ Krushna @ **************/      

	function check_duplicate_email()
	{
		
		var email = $('#email').val();
		var myAjaxValue = null;
		 $.ajax({
		   url: base_url+"customermaster/validate_customer_email",
		   type: 'POST',
		   'async': false,
		   'global': false,
			data: {
				email: email,
				},
			success: function(res)
		   {	
				if(res == '1')
				{		
						$('#email').addClass('error-message').removeClass('valid');
						//$('#msg_dupli').html('This email is already used. Please choose another.'); 
						$('<p id="msg_dupli" class="error-message">This email is already used. Please choose another</p>').insertAfter('#email'); 
						myAjaxValue = 1;
						
				}
				else
				{		$('#email').addClass('valid').removeClass('error-message');
						//$('#msg_dupli').html(''); 
						$('#msg_dupli').remove();
						myAjaxValue = 0;
				}
			}
		});
		return myAjaxValue;
	}
	
	$(function() 
	{	
		$("form[name='customer_update_Form']").validate({
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
			   
					 vehiclename:"required",
					 vehicleno:"required",
					 Street: "required",
					 country: "required",
					 Suburb:"required",
					 State:"required",
					 
			},
			
			messages:
			{
			  name:"Name field is required.",
			  //email:"Email is required.",
			  licenceNo:"licenceNo field is required.",
			  file:"Document Image is required.",
			  vehiclename:"Vehicle Name is required.",
			  vehicleno:"Vehicle No is required.",
			  Street:"Street/city  Required",
			  country:"Country Required",
			  Suburb:"Suburb Required or not valid select from suggestion",
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
		
		
	/**** For not allowing Characters (Only digits allow) in postalcode field ****/

	$('#postalcode').keyup(function()
	{
	   if (/\D/g.test(this.value))
	   {
		 // Filter non-digits from input value.
		 this.value = this.value.replace(/\D/g, '');
	   }
	});
</script>