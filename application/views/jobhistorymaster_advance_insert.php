	
	<!--<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
	<select id="address" style="width:500px;"></select>-->

    <!--    <script type="text/javascript">
            $(document).ready(function(){

                $("#submit").click(function(){
                    var val = $("#pickupaddress,#dropoffaddress").val();
                    alert(val);
                });

                $('#pickupaddress,#dropoffaddress').select2({
                    placeholder: "Search for your city Address",
                    ajax: {
							url: function(params)
							{
								return '<?php //echo base_url(); ?>jobhistory/autocomplete_place?data='+params.term; 
							},
                        dataType: 'json', 
                        processResults: function (data) {
                            return {
                                results: $.map(data, function (item) 
								{
                                    return {
                                        text: item.text,
                                        id: item.text
                                    }
                                })
                            };
						}
	                }
				});
            });
        </script>-->
 <style>
      input {
        height: 30px;
        padding-left: 10px;
        border-radius: 4px;
        border: 1px solid rgb(186, 178, 178);
        box-shadow: 0px 0px 12px #EFEFEF;
      }
    </style>
    
	
	
	
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places&language=en&key=AIzaSyBxBLnWXhi0YdTQITGWlOZrQI5MEPv2O3E"></script>
   
<style>
	.popover-title{
	text-align:center;

	}
	.popover.confirmation.fade.top.in{background:#eeeeee none repeat scroll 0 0 !important;}
	.step-controls{margin-bottom:20px !important;}
</style>

		<script src="<?php echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>
		<link rel="stylesheet" href="<?php echo asset_url(); ?>css/bootstrap-datetimepicker.min.css">
		<link href="<?php echo asset_url(); ?>css/bootstrap-combined.min.css" rel="stylesheet">
		
		<!-- <link href="http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css" rel="stylesheet">
		<script type="text/javascript"src="http://tarruda.github.com/bootstrap-datetimepicker/assets/js/bootstrap-datetimepicker.pt-BR.js"></script>
		<link rel="stylesheet" href="<?php// echo asset_url(); ?>css/bootstrap-datetimepicker.min.css">
		<script src="<?php //echo base_url(); ?>assets/js/bootstrap-datetimepicker.min.js"></script>-->
    
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
		<script src="<?php echo base_url(); ?>assets/js/bootstrap-confirmation.min.js"></script> 	
		<script src="//cdn.jsdelivr.net/webshim/1.14.5/polyfiller.js"></script>
		
		<!-- datepicker -->

	<script>
		
		$(document).ready(function()
		{	
			var jobstatus = $("#edit_jobstatus").val();
			if(jobstatus==2)
			{
				$(".status_rsn").css('display','block');
				$("#status_error").css('display','block');
			}
		}); 
		
		function myfun()
		{
			var myvar = null;
			var currval = $(this).val();
			var status_rsn = $(".status_rsn").val();
			
			if(currval==2 && status_rsn=='')
			{
				$(".status_rsn").css('display','block');
				$("#status_error").css('display','block');
				myvar= false;
			}
			else
			{
				myvar= true;
			}
			return myvar;
		}
		
		
   /* webshims.setOptions('forms-ext', {types: 'date'});	
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

  $.webshims.activeLang('en');*/
	
</script>

<style>
.step-controls{margin-bottom:20px !important;}

/***** DateTime Picker css*****/
	.form-control1 
	{
		/* display: block; */
		/* width: 100%; */
		width: 71%;
		height: 34px;
		padding: 6px 12px;
		font-size: 14px;
		line-height: 1.42857143;
		color: #555;
		background-color: #fff;
		background-image: none;
		border: 1px solid #ccc;
		border-radius: 4px;
		-webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		-webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
		-o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
		transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
	}
	.input-append .add-on, .input-prepend .add-on 
	{
		display: inline-block;
		width: auto;
		height: 34px;
		min-width: 16px;
		padding: 4px 5px;
		font-size: 14px;
		font-weight: normal;
		line-height: 20px;
		text-align: center;
		text-shadow: 0 1px 0 #fff;
		background-color: #eee;
		border: 1px solid #ccc;
	}
</style>
    
<!-- PAGE CONTENT WRAPPER -->
		<!---------------edit User form start-------------------------------->
							<?php if(isset($msg)){ echo $msg; } ?>

		<?php if(isset($EditData)){ //echo "<pre>";print_r($EditData);exit(); ?>
									<?php //echo "<pre>";print_r($Getcompany);exit(); ?>
		
		<div class="page-inner">
			<div class="page-title">
				<h3 class="breadcrumb-header">Edit Job History Form</h3>
			</div>
			<div class="page-content-wrap">
				<form id="UpdateJobHistory" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>jobhistory/update">
				<input type="hidden" name="UpdateId" value="<?php echo $EditData[0]->Id; ?>">
				<input type="hidden" name="CompanyId" value="<?php echo $EditData[0]->CompanyId; ?>">
				<input type="hidden" name="JobStatus" value="<?php echo $EditData[0]->JobStatus; ?>">
					
					<div class="panel panel-default">
					<div class="panel-heading clearfix">
						<h4 class="panel-title">Pick Up Details</h4>
					</div>
					<div class="col-md-12 form-group">
						<div class="ms" style="display:none;"></div>
					</div>
					
					<?php if($roleid==1){ ?>
							<!--<div class="form-group">
								<label class="col-md-2 control-label">Company</label>
								<div class="col-md-5">      
									<input type="text" class="form-control" id="Company" name="Company" placeholder="Company" value="<?php echo $UserCompanyName[0]->Name; ?>" disabled>
								</div>								
							</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Company</label>
						<div class="col-md-5">      
							<select class="form-control select" name="CompanyId" id="CompanyId">
							  <option value="">Select Company</option>
							  <?php foreach($Getcompany as $comp){ ?>
							  <option value="<?php echo $comp->Id;?>"<?php if($EditData[0]->CompanyId == $comp->Id){echo 'selected'; } ?>><?php echo $comp->Name;?></option>
							  <?php } ?>
							</select>
						</div>	
								
					</div>
					<?php } ?>
					
					<?php if($roleid==2){ ?>
					
						<div class="form-group">
							<label class="col-md-2 control-label">Company</label>
							<div class="col-md-5">      
								<input type="text" class="form-control" id="Company" name="Company" placeholder="Company" value="<?php echo $UserCompanyName[0]->Name; ?>" disabled>
							</div>								
						</div>
						<input type="hidden" name="ApiKey" value="<?php //echo $UserCompanyName[0]->Apikey; ?>">
						
						<!--<div class="form-group">
							<label class="col-md-2 control-label">Driver</label>
							<div class="col-md-5">      
								<select class="form-control select" name="Driver" id="Driver">
								  <option value="">Select Driver</option>
								  <?php //foreach($CompanyDriver as $comp_drvr){ ?>
								  <option value="<?php //echo $comp_drvr->Id;?>"><?php //echo $comp_drvr->Name;?></option>
								  <?php //} ?>
								</select>
							</div>								
						</div>-->
					 
					<?php } ?>
					
					<?php 
						$pkup_dtl = json_decode($EditData[0]->PickupDetail);
						$drp_dtl = json_decode($EditData[0]->DropoffDetail);
						//echo "<pre>";print_r($drp_dtl);exit();
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
						  <input type="text" class="form-control" id="pickupphone" name="pickupphone" placeholder="Phone" value="<?php echo $pkup_dtl->phone;?>" minlength="10" maxlength="12">
						</div>
					</div>
					
					<!--<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea class="form-control" name="pickupaddress" id="pickupaddress" placeholder="Address"><?php //echo $pkup_dtl->address;?></textarea>
						</div>
					</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						<input type="text" class="form-control" id="pickupaddress" name="pickupaddress" value="<?php echo $pkup_dtl->address;?>">
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
						  <input type="text" class="form-control" id="dropoffphone" name="dropoffphone" placeholder="Phone" value="<?php echo $drp_dtl->phone;?>" minlength="10" maxlength="12">
						</div>
					</div>
					
					<!--<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea class="form-control" name="dropoffaddress" id="dropoffaddress" placeholder="Address"><?php //echo $drp_dtl->address;?></textarea>
						</div>
					</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						<input type="text" class="form-control" id="dropoffaddress" name="dropoffaddress" value="<?php echo $drp_dtl->address;?>">
						</div>
					</div>
					
					<?php 
						if(isset($EditData[0]->ScheduleJobTime))
						{
							$timestamp = $EditData[0]->ScheduleJobTime;
							$splitTimeStamp = explode(" ",$timestamp);
							$date = $splitTimeStamp[0];
							$time = $splitTimeStamp[1];
						}
					?>
				<!--<?php //if(isset($timestamp) && $timestamp!=null){ ?>	-->
				
				<?php if($EditData[0]->ScheduleJobTime !=0){ ?>	
					<div class="panel-heading clearfix">
						<div class='toggle_parent'>
							<div class='toggleHolder'>
								<span class='toggler'><img src="<?php echo base_url();?>/assets/images/details_open.png"></img> Scheduled Times</span>
								<span class='toggler' style='display:none;'><img src="<?php echo base_url();?>/assets/images/details_close.png"></img>Scheduled Times</span>
							</div>
							<div class='toggled_content' style='display:none;'>
								<div class="form-group">
									<label class="col-md-2 control-label">Pick Up Details</label>
									<div class="col-md-5">
									  <div id="datetimepicker4" class="input-append">
										<input data-format="yyyy-MM-dd" type="text" class="form-control1" name="pickupdate" id="pickupdate" value="<?php echo $date; ?>" ></input>
										<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
									  </div>
									</div>
									<div class="col-md-5">
									  <div id="datetimepicker3" class="input-append">
										<input data-format="hh:mm:ss" type="text" class="form-control1" name="pickuptime" value="<?php echo $time; ?>" ></input>
										<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
									  </div>
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php } ?>
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
<script>
    var pickupaddress = document.getElementById('pickupaddress');
    var autocomplete = new google.maps.places.Autocomplete(pickupaddress);
	
	var dropoffaddress = document.getElementById('dropoffaddress');
    var autocomplete1 = new google.maps.places.Autocomplete(dropoffaddress);
    </script>	
			<?php } else { ?>
			<!------------- Edit User Form End ------------------>
			
			<!------------- User Insert Form Start --------------->
			<?php //echo '<pre>';print_r($Getcompany);exit;?>
				
		<div class="page-inner">
				<div class="page-title">
					<h3 class="breadcrumb-header">Create Job Form</h3>
				</div>
			<form name="jvalidate" id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>jobhistory/insert">
				<div class="panel panel-default">
					
					<div class="panel-heading clearfix">
                        <h4 class="panel-title">Pick Up Details</h4>
					</div>
					<div class="col-md-12 form-group">
						<div class="ms" style="display:none;"></div>
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
					
					<?php if($roleid==2){ ?>
					
						<div class="form-group">
							<label class="col-md-2 control-label">Company</label>
							<div class="col-md-5">      
								<input type="text" class="form-control" id="Company" name="Company" placeholder="Company" value="<?php echo $UserCompany[0]->Name; ?>" disabled>
							</div>								
						</div>
						<input type="hidden" name="ApiKey" value="<?php echo $UserCompany[0]->Apikey; ?>">
						<input type="hidden" name="CompanyId" value="<?php echo $CompanyId; ?>">
						
						<!--<div class="form-group">
							<label class="col-md-2 control-label">Driver</label>
							<div class="col-md-5">      
								<select class="form-control select" name="Driver" id="Driver">
								  <option value="">Select Driver</option>
								  <?php //foreach($CompanyDriver as $comp_drvr){ ?>
								  <option value="<?php //echo $comp_drvr->Id;?>"><?php //echo $comp_drvr->Name;?></option>
								  <?php //} ?>
								</select>
							</div>								
						</div>-->
					
					<?php } ?>
					<div class="form-group">
					  <label class="col-md-2 control-label">Job Status</label>
					  <div class="col-md-5">      
					   <select class="form-control select" name="jobstatus" id="jobstatus" disabled>
						 <?php foreach($jobstatus as $jbstatus){  ?>
						 <option value="<?php echo $jbstatus->Id;?>" <?php if($jbstatus->StatusName == "Created"){ echo 'selected';} else{ echo 'disabled';}?>><?php echo $jbstatus->StatusName;?></option>
						 <?php } ?>
					   </select>
					  </div>        
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Email</label>
						<div class="col-md-5">
						  <input type="email" class="form-control" id="Email" name="Email" placeholder="Email" value="">
						</div>
					</div>
					<div class="panel-heading clearfix">
                                    <h4 class="panel-title"><b>Pickup Details : </b></h4>
                    </div>
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="pickupname" name="pickupname" placeholder="Name" value="">
						</div>
					</div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="pickupphone" name="pickupphone" placeholder="Phone" minlength="10" maxlength="12" value="">
						</div>
					</div>
					
					<!--<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea class="form-control" name="pickupaddress" id="pickupaddress" placeholder="Address"></textarea>
						</div>
					</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						<input type="text" class="form-control" id="pickupaddress" name="pickupaddress" value="">
						</div>
					</div>
					
					<div class="panel-heading clearfix">
                            <h4 class="panel-title"><b>DropOff Details</b></h4>
                    </div>
					
					<div class="form-group">
						<label class="col-md-2 control-label">Name</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffname" name="dropoffname" placeholder="Name" value="">
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Phone</label>
						<div class="col-md-5">
						  <input type="text" class="form-control" id="dropoffphone" name="dropoffphone" placeholder="Phone" minlength="10" maxlength="12" value="">
						</div>
					</div>
					
					<!--<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						  <textarea class="form-control" name="dropoffaddress" id="dropoffaddress" placeholder="Address"></textarea>
						</div>
					</div>-->
					
					<div class="form-group">
						<label class="col-md-2 control-label">Address</label>
						<div class="col-md-5">
						<input type="text" class="form-control" id="dropoffaddress" name="dropoffaddress"  value="">
						</div>
					</div>
					
					<div class="panel-heading clearfix">
						<div class='toggle_parent'>
							<div class='toggleHolder'>
								<span class='toggler'><img src="<?php echo base_url();?>/assets/images/details_open.png"></img> Scheduled Times</span>
								<span class='toggler' style='display:none;'><img src="<?php echo base_url();?>/assets/images/details_close.png"></img>Scheduled Times</span>
							</div>
							<div class='toggled_content' style='display:none;'>
								<div class="form-group">
									<label class="col-md-2 control-label">Pick Up Details</label>
									<div class="col-md-5">
									  <div id="datetimepicker4" class="input-append">
										<input data-format="yyyy-MM-dd" type="text" class="form-control1" name="pickupdate" id="pickupdate"></input>
										<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
									  </div>
									</div>
									<div class="col-md-5">
									  <div id="datetimepicker3" class="input-append">
										<input data-format="hh:mm:ss" type="text" class="form-control1" name="pickuptime"></input>
										<span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
									  </div>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="form-group">
							<div class="col-md-offset-2 col-sm-1">
								<!--<button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;" onclick="JobCreate()">Submit</button>-->
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
	
	$('#pickupphone,#dropoffphone').keyup(function()
	{
		if (/\D/g.test(this.value))
		{
		  // Filter non-digits from input value.
		  this.value = this.value.replace(/\D/g, '');
		}
	});
	
	$.validator.addMethod('divisible', function(value, element) 
	{
		if(!$('.status_rsn').css("display") == "none")
		{
			return true
		}       
		else
		{
			return false
		}
		//return parseInt(value) % 5 == 0
    }, 'Please enter job status reason');
	
		var jvalidate = $("#jvalidate").validate({
			//ignore: [],
			rules: {
				CompanyId : {
					required: true,
				},
				pickupname: {
					required: true,
				},
				pickupphone:"required",
				pickupaddress:{
						required:true,
						//minlength:8
				},
				dropoffname: {
						required: true,
				},
				dropoffphone: "required",
				
				dropoffaddress:{
						required:true,
					   //minlength:8
                },
				Email: {
						required: true,
						email: true
				},
				Driver: {
						required: true,
						
				},
				status_reason: {
						divisible: true,
				}
			},

			messages:
			{
			  CompanyId : "Company field is required.",
			  pickupname:"Pickup Name field is required.",
			  //pickupphone:"Pickup Phone field is required.",
			 pickupaddress:"Pickup Address is required.",
			  dropoffname:"Dropof Name is required.",
			  //dropoffphone:"Dropof Phone Number is required.",
			  dropoffaddress:"Dropof Address is required.",
			  Driver:"Driver is Required",
			  Email:"Email is Required",
			},
			submitHandler: function(form)
			{
				var data = $("#jvalidate").serialize();
				if(data)
				{
					$.ajax({
							url:"<?php echo base_url(); ?>jobhistory/insert",  
							type:'post',
							data:data,
							dataType: 'JSON',
							success:function(data)
							{
								//debugger;
								if(data.success==false)
								{
									//alert("Yes");
									swal("Error!", data.message, "warning");
									// $('.ms').html(data.message).show();	
									// setTimeout(function(){			// wait for 6 secs						
									// 	$('.ms').slideUp('slow');
									// }, 6000);
								}
								else
								{
									swal("Great Job Created!", data.message, "success");
									// $('.ms').html(data.message).show();	
									// setTimeout(function(){			// wait for 6 secs						
									// 	$('.ms').slideUp('slow');
									// }, 6000);
								}
							}
						});
						return false;
				}
				else
				{
					return false;
				}
				
				var myvar = null;
			    var currval = $(this).val();
				var status_rsn = $(".status_rsn").val();
				//alert(currval);
				if(currval==2 && status_rsn=='')
				{
				  $(".status_rsn").css('display','block');
				  $("#status_error").css('display','block');
				  return false;
				}
				else
				{
					return false;
				}
			}	
		});
		
	/*** Update Form Validation Start **/
	var UpdateJobHistory = $("#UpdateJobHistory").validate({
			//ignore: [],
			rules: {
				pickupname: {
					required: true,
				},
				pickupphone:"required",
				pickupaddress:{
						required:true,
						//minlength:8
				},
				
				dropoffname: {
						required: true,
				},
				dropoffphone: "required",
				
				dropoffaddress:{
						required:true,
					   //minlength:8
                },
				
				Email: {
						required: true,
						email: true
				},
				Driver: {
						required: true,
						
				},
				
				status_reason: {
						divisible: true,
				}
			},

			messages:
			{
			  pickupname:"Pickup Name field is required.",
			  //pickupphone:"Pickup Phone field is required.",
			  pickupaddress:"Pickup Address is required.",
			  dropoffname:"Dropof Name is required.",
			  //dropoffphone:"Dropof Phone Number is required.",
			  dropoffaddress:"Dropof Address is required.",
			  Driver:"Driver is Required",
			  Email:"Email is Required",
			},
			submitHandler: function(form)
			{
				var data = $("#UpdateJobHistory").serialize();
				if(data)
				{
					$.ajax({
							url:"<?php echo base_url(); ?>jobhistory/update",  
							type:'post',
							data:data,
							dataType: 'JSON',
							success:function(data)
							{
								if(data.success==false)
								{
									swal("Error!", data.message, "warning");
									// $('.ms').html(data.message).show();	
									// setTimeout(function(){			// wait for 6 secs						
									// 	$('.ms').slideUp('slow');
									// }, 6000);
								}
								else
								{
									swal("Great Job Updated!", data.message, "success");
									
									// $('.ms').html(data.message).show();	
									// setTimeout(function(){			// wait for 6 secs						
									// 	$('.ms').slideUp('slow');
									// }, 6000);
								}
							}
						});
						return false;
				}
				else
				{
					return false;
				}
				
				var myvar = null;
			    var currval = $(this).val();
				var status_rsn = $(".status_rsn").val();
				//alert(currval);
				if(currval==2 && status_rsn=='')
				{
				  $(".status_rsn").css('display','block');
				  $("#status_error").css('display','block');
				  return false;
				}
				else
				{
					return false;
				}
			}	
		});
	
	$(function()
	{ 
	    $('.toggler').click(function(e)
		{
			$(this).parent().children().toggle();  //swaps the display:none between the two spans
			$(this).parent().parent().find('.toggled_content').slideToggle();  //swap the display of the main content with slide action
		}); 
	});
	
		
		$(function() 
		{
			$('#datetimepicker3').datetimepicker({
				pickDate: false,
				
			});
		});
		
		$(function() 
		{
			$('#datetimepicker4').datetimepicker({
				pickTime: false,
				startDate: new Date()
			});
		});
      
	
</script>
 
  <script>
    var pickupaddress = document.getElementById('pickupaddress');
    var autocomplete = new google.maps.places.Autocomplete(pickupaddress);
	
	var dropoffaddress = document.getElementById('dropoffaddress');
    var autocomplete1 = new google.maps.places.Autocomplete(dropoffaddress);
    </script>
    
   

	
	