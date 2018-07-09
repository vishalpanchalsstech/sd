<!-- PAGE CONTENT WRAPPER -->

               
	
				<?php if(isset($msg)){ echo $msg; } ?>
				<!---------------edit form start-------------------------------->
				<?php if(isset($EditData)){ 
				//echo "<pre>";print_r($EditData);exit(); 
				//echo "update"; exit;
				?>
				<div class="page-inner">
                    <div class="page-title">
                        <h3 class="breadcrumb-header">Company Master Edit</h3>
					</div>
				<div class="page-content-wrap">
				<form name="update_userform" id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>companymaster/update">
				<input type="hidden" name="UpdateId" value="<?php echo $EditData[0]->Id; ?>">
				<div class="panel panel-default">
					
					<div class="form-group">
					<label class="col-md-2 control-label">Name:</label>
					<div class="col-md-5">
					  <input type="text" class="form-control" id="Name" name="Name" placeholder="Name" value="<?php echo $EditData[0]->Name; ?>">
					</div>
					</div>
					<div class="form-group">
					<label class="col-md-2 control-label">Email:</label>
					<div class="col-md-5">
					  <input type="email" class="form-control" id="Email" name="Email" placeholder="Email" value="<?php echo $EditData[0]->Email; ?>">
					</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address:</label>  
						<div class="col-md-5">
							<textarea class="form-control" id="Address"  name="Address" placeholder="Address" /><?php echo $EditData[0]->Address; ?></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="logo" class="col-sm-2 control-label">Logo:</label>
						<div class="col-sm-3">
							<input type="file" name="editfile" id="editfile" onchange="validatefile(this);">
							<div id="shfilerr"></div>
							<img style='width:100px;height:100px;' src="<?php echo base_url().$EditData[0]->Logo; ?>" />
							<input type="hidden" name="uploadimg" value="<?php echo $EditData[0]->Logo; ?>"/>
						</div>
					</div>
					<div class="form-group">
						<label for="TimeZone" class="col-sm-2 control-label">Time Zone:</label>
						<div class="col-sm-4">
							<select class="form-control"  name="TimeZone"  id="TimeZone"  multiple="multiple">
								<?php
								if(isset($EditData[0]->TimeZone)){  $timezonevalue = $EditData[0]->TimeZone; }
								 if(isset($timezone)){ 	$html_list = ''; 
											foreach($timezone as $zone){
												if(strtoupper($zone['zone']).' - '.$zone['diff_from_GMT'] == $timezonevalue){ $selected="selected='selected'"; }else{  $selected=""; }
												$html_list .= '<option '.$selected.' ><strong class="name">'.strtoupper($zone['zone']).' - '.$zone['diff_from_GMT'].'</strong></option>';	
								} echo $html_list; }  ?>
	
							</select>
						</div>
					</div>
					<div class="form-group">
					<label for="logo" class="col-sm-2 control-label">Country:</label>
						<div class="col-sm-4">
						<select name="country" id="country" class="selectpicker" data-show-subtext="true" data-live-search="true">
							<option value=""></option>
							<?php foreach($country as $row){ ?>
							<option value="<?php echo $row->Name; ?>" name="<?php echo $row->SortName; ?>" <?php if(isset($EditData[0]->Country)) {  if($EditData[0]->Country==$row->Name) { echo 'selected'; } } ?>><?php echo $row->Name; ?></option>
							<?php } ?>
						  </select>
						</div>	
					</div>
					<div class="form-group">
					<?php //print_r($state); ?>
						<label for="logo" class="col-md-2 control-label">State:</label>
						<div class="col-sm-3">
						<select name="state" id="state" class="selectpicker" data-show-subtext="true" data-live-search="true">
							   <option value=""></option>
							<?php foreach($state as $row){ ?>
							
							<option id="statevalue" value="<?php echo $row->State ; ?>" name="<?php echo $row->CountryStateId ; ?>" <?php if(isset($EditData[0]->State)) {  if($EditData[0]->State==$row->State) { echo 'selected'; } } ?>><?php echo $row->State ; ?></option>
							<?php } ?>
						</select>
						</div>	
					</div>									
					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">City:</label>
						<div class="col-sm-3">
						<input type="text" name="city" id="city" class="typeahead form-control" value="<?php echo $EditData[0]->City; ?>" autocomplete="off"/>

						</div>	
					</div>

					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">Company Prefix:</label>
						<div class="col-sm-3">
						<input type="text" name="companyprefix" id="companyprefix" class="typeahead form-control" autocomplete="off" readonly value="<?php echo $EditData[0]->Prefix; ?>" />
						</div>	
					</div>

					<div class="form-group">
					<label for="logo" class="col-md-2 control-label"></label>
						<div class="col-sm-3">
						<a data-toggle="collapse" href="#advancesetting" aria-expanded="true" style="color:#0070E0;font-weight:bold;font-size:16px;" >Advance Setting</a>
                        </div>
					</div>
					<div id="advancesetting" class="panel-collapse collapse" role="tabpanel">
						<div class="form-group">
							<label for="logo" class="col-md-2 control-label">Token:</label>
							<div class="col-sm-3">
							<input type="text" name="token" id="token" class="typeahead form-control" value="<?php echo $psdata[0]->Token; ?>" autocomplete="off"  readonly required />

							</div>	
						</div>
						<div class="form-group">
							<label for="logo" class="col-md-2 control-label">Notification Method:</label>
							<div class="col-sm-3">
							<select name="notmethod" id="notmethod" class="selectpicker" data-show-subtext="true" data-live-search="true">
							 <?php foreach($notmethod as $row){ ?>
							
							<option value="<?php echo $row->Id ; ?>" <?php if(isset($psdata[0]->NotificationMethodId)) {  if($psdata[0]->NotificationMethodId==$row->Id) { echo 'selected'; } } ?>><?php echo $row->Name ; ?></option>
							<?php } ?>
						</select>
							</div>	
						</div>
					</div>
					<div class="form-group">
						<div class="col-md-offset-2 col-sm-1">
						  <button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Submit</button>
						</div>
						<div class="col-md-offset-1 col-sm-1">
						  <a href="<?php echo base_url().'companymaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
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
                        <h3 class="breadcrumb-header">Company Master Insert</h3>
					</div>
				<form name="company_insrt" id="jvalidate" class="form-horizontal" method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>companymaster/insert">
				<div class="panel panel-default">
					
					<div class="form-group">
					<label class="col-md-2 control-label">Name:</label>
					<div class="col-md-5">
					  <input type="text" class="form-control" id="Name" name="Name" placeholder="Name">
					</div>
					</div>
					<div class="form-group">
					<label class="col-md-2 control-label">Email:</label>
					<div class="col-md-5">
					  <input type="email" class="form-control" id="Email" name="Email" placeholder="Email">
					</div>
					</div>
					<div class="form-group">
						<label class="col-md-2 control-label">Address:</label>  
						<div class="col-md-5">
							<textarea class="form-control" id="Address"  name="Address" placeholder="Address" /></textarea>
						</div>
					</div>
					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">Logo:</label>
						<div class="col-sm-3">
							<input type="file" name="file" id="file" onchange="validatefile(this);">
							<div id="shfilerr"></div>
						</div>
					</div>
					<div class="form-group">
						<label for="TimeZone" class="col-md-2 control-label">Time Zone:</label>
						<div class="col-sm-4">
							<select class="form-control"  name="TimeZone"  id="TimeZone" multiple="multiple" >
								<?php if(isset($timezone)){ 	$html_list = ''; 
									foreach($timezone as $zone){
										$html_list .= '<option><strong class="name">'.strtoupper($zone['zone']).' - '.$zone['diff_from_GMT'].'</strong></option>';	
									} echo $html_list; } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
					<label for="logo" class="col-md-2 control-label">Country:</label>
						<div class="col-sm-4">
						<select name="country" id="country" class="selectpicker" data-show-subtext="true" data-live-search="true">
							<option value=""></option>
							<?php foreach($country as $row){ ?>
							<option value="<?php echo $row->Name; ?>" name="<?php echo $row->SortName; ?>"><?php echo $row->Name; ?></option>
							<?php } ?>
						  </select>
						</div>	
					</div>
					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">State:</label>
						<div class="col-sm-3">
						<select name="state" id="state" class="selectpicker" data-show-subtext="true" data-live-search="true">
							   <option></option>
						</select>
						</div>	
					</div>									
					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">City:</label>
						<div class="col-sm-3">
						<input type="text" name="city" id="city" class="typeahead form-control" autocomplete="off"/>

						</div>	
					</div>

					<div class="form-group">
						<label for="logo" class="col-md-2 control-label">Company Prefix:</label>
						<div class="col-sm-3">
						<input type="text" name="companyprefix" id="companyprefix" class="typeahead form-control cmpnyprfx" autocomplete="off"/>
						<label id="cmpyprfxerror" style="display:none;font-size:12px;color:#ec5e69;font-weight:500;margin-top:5px;float:left;">Company Prefix Already Exist.</label>
						</div>	
					</div>

					<div class="form-group">
						<div class="col-md-offset-2 col-sm-1">
						  <button type="submit" class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Submit</button>
						</div>
						<div class="col-md-offset-1 col-sm-1">
						  <a href="<?php echo base_url().'companymaster'; ?>" ><span  class="btn btn-success" style="margin-top:10px;margin-bottom:-14px;">Back</span></a>
						</div>
					</div>
					
				</div>
				</form>
				</div>
				
				
				
				<!-------------insert form end--------------------------------->
				<?php } ?>
		
				
	
	<script type="text/javascript">
		
		jQuery.validator.addMethod("lettersonly", function(value, element) {
		return this.optional(element) || /^[a-z\s]+$/i.test(value);
		}, "Only alphabetical characters");

		var jvalidate = $("#jvalidate").validate({
		ignore: [],
		rules: {                                       
			   Name: {
						required: true,   
				},
				Email: {
						required: true,
						email: true
					   
				},
				Address: {
						required: true,                               
				},
				file: {
						required: true,
					  
				},
				country: {
						required: true,
					  
				},
				state: {
						required: true,
					  
				},
				city: {
						required: true,
					  
				},
				companyprefix: {
						required: true,
						lettersonly: true,
						maxlength: 3,
				}
				   
			}                                        
		});
		$('#country').change(function(){
		var country = $('option:selected', this).attr('name');
		 
		if(country != ''){
			$.ajax({
				url:"<?php echo site_url(); ?>companymaster/state_list",
				type:'post',
				data:{
					country:country
				},
				success:function(res){
					var data = jQuery.parseJSON(res);
					$('#state').html(data).selectpicker('refresh');
				}
			});
			}
		
		});
		$('#state').change(function(){
			$('#city').val('');
		});
		$(document).ready(function () {
			$('#city').typeahead({
            source: function (query, result) {
			var state = $('#state option:selected').attr('name');;
			$.ajax({
                    url: "<?php echo site_url(); ?>companymaster/key_city_list",
					data:{
					query:query,
					state:state
					},					
                    dataType: "json",
                    type: "POST",
                    success: function (data) {
						result($.map(data, function (item) {
							return item;
                        }));
						
                    }
					
					
                });
            }
			});
		 });
		
		/*Check Prefix Duplication*/
		 $('.cmpnyprfx').blur(function(){
			 $("#cmpyprfxerror").css('display','none');
			 var company_prefix = $(this).val();
			 if(company_prefix.length >= 3){
					$.ajax({
							url: "<?php echo site_url(); ?>companymaster/checkcompanyprefix",
							data:{company_prefix:company_prefix},					
							dataType: "json",
							type: "POST",
							success: function(data) {
								if ($.trim(data)){   
									$("#cmpyprfxerror").css('display','block');
								}
							}
						});
			 }
			else{
				$("#cmpyprfxerror").css('display','none');
			}	
		});
		document.addEventListener('keydown', function(event) {
			const key = event.key; // const {key} = event; ES6+
			if (key === "Delete") {
				$("#cmpyprfxerror").css('display','none');
			}
		});
		
	$(function() 
	{  
	   $("form[name=company_insrt] #TimeZone,form[name=update_userform] #TimeZone").select2({
			maximumSelectionLength: 1,
			placeholder: "Search TimeZone"
		});
	});
		
	</script>
	<style>
.timezone-html ul li a:hover, .retailer-html ul li a:hover, .timezone-html ul li:hover, .retailer-html ul li:hover, .group-html ul  li:hover {
    background: #c0c0c0 none repeat scroll 0 0;
    color: #000 !important;
}

.timezone-html ul li a,.retailer-html ul li a {
    color: #000;
}
</style>