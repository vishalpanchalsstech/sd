<?php if(isset($msg)){ echo $msg; } ?>
   
<style>
td.details-control {
    background: url("././assets/images/details_open.png") no-repeat scroll center center rgba(0, 0, 0, 0);
    cursor: pointer;
}
td.details-control {
    background: url('././assets/images/details_open.png') no-repeat center center;
    cursor: pointer;
}
tr.shown td.details-control {
    background: url('././assets/images/details_close.png') no-repeat center center;
}
</style>
<script>

$(function() {
$('#enddate').daterangepicker({
    singleDatePicker: true,
     locale: {
                format: 'DD-MM-YYYY'
            },
            startdate: enddate,
            maxDate: new Date()
});
 $('#startdate').daterangepicker({
    singleDatePicker: true,
     locale: {
                format: 'DD-MM-YYYY'
            },
            // startDate: startdate,
           // maxDate: new Date()
   });

});

$(document).ready(function(){
	
	/*var table = $('#jobhistory').DataTable({
		 responsive: true,
        "ajax": baseurl+"jobhistory/jobhistory_table",
        "columns": [
			{
			"className":'details-control',
			"orderable":false,
			"data":null,
			"defaultContent": ''
            },
            { "data": "JobId" },
            { "data": "JobStatus" },
            { "data": "Company" },
            { "data": "UserName" },
			{ "data": "Action" }
        ],
		"fnDrawCallback": function() {
			$('#jobhistory tr').each(function() {		
				$(this).find('td:first').attr("class","details-control");
			});
		}
		
});

 $('#jobhistory tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
 });
	function format (d) {
		// `d` is the original data object for the row
		return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
		
			'<tr>'+
				'<td><b>Id:</b></td>'+
				'<td>'+d.Id+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>Pickup Details:</b></td>'+
				'<td>'+d.PickupDetail+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>DropOff Details:</b></td>'+
				'<td>'+d.DropoffDetail+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>Distance:</b></td>'+
				'<td>'+d.Distance+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>Duration:</b></td>'+
				'<td>'+d.Duration+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>DistanceStatus:</b></td>'+
				'<td>'+d.DistanceStatus+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>Consignment:</b></td>'+
				'<td>'+d.Consignment+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>AcceptedDriverId:</b></td>'+
				'<td>'+d.AcceptedDriverId+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>RejectDriverId:</b></td>'+
				'<td>'+d.RejectDriverId+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>CancelDriverId:</b></td>'+
				'<td>'+d.CancelDriverId+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>CreatedAt:</b></td>'+
				'<td>'+d.CreatedAt+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>CreatedVia:</b></td>'+
				'<td>'+d.CreatedVia+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>CreatedBy:</b></td>'+
				'<td>'+d.CreatedBy+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>DeletedBy:</b></td>'+
				'<td>'+d.DeletedBy+'</td>'+
			'</tr>'+
			'<tr>'+
				'<td><b>JobStatus:</b></td>'+
				'<td>'+d.JobStatus+'</td>'+
			'</tr>'+
		'</table>';
	}
	*/
	
	$("#btnclear").click(function(){
			$("#filterjobhistory")[0].reset()
		});
	
      $('#jobhistory').dataTable({ 
              responsive: true, 
              bFilter: false,
        "ajax": baseurl+"jobhistory/jobhistory_table",  
        "columns": [
		    { "data": "popup_icon" }, 
		    { "data": "JobId" },
                    { "data": "JobStatus" },
                    { "data": "Company" },
                    { "data": "UserName" },
                    { "data": "TimeZone" },
                    { "data": "CreatedAt" },

		    { "data": "Action" }
        ]
      });
      
      
      /*For JobHistory Filter Functionality DataTable Load@@@@uk*/
	$('#filter_jobhistory').DataTable({
			    bFilter: false,
		 		dom: 'lBfrtip',
				responsive: true,
		buttons: [
				{
					exportOptions: {
						modifier: {
							 //bFilter: true,
						}
					}
				}
			],	
		});
	$.fn.dataTable.ext.errMode = 'none';
      
	
});	
</script>

	<div class="page-inner">
		<div class="page-title">
            <h3 class="breadcrumb-header">Job History Master List</h3>
		</div>
		<div class="panel panel-default">
		
			<div class="col-md-12">
			<div class="panel panel-white">
				<div class="panel-heading clearfix">
                                    <h4 class="panel-title">Filter</h4>
                        </div>
			<div class="panel-body">
			<form class="form-horizontal" action="<?php echo base_url(); ?>index.php/jobhistory/filter_jobhistory" method="post" enctype="multipart/form-data" name="filterjobhistory" id="filterjobhistory">
			  <div class="col-md-9">		
				<div class="form-group">
                  <div>
                    <label class="col-sm-2 control-label" for="input-Default">Search</label>
                    <div class="col-sm-10" style="padding-left: 5px;">
                      <input type="text" id="input-Default" name="search" placeholder="CreatedBy" class="form-control" value="<?php if(isset($search_key)){ echo $search_key; } else { echo $search_key=''; } ?>">
                      </div>
                  </div>
                          </div>
							
			  <div class="form-group">	
				<div class="col-md-6">	
				<label for="startdate" class="col-sm-4 control-label" style="padding-right: 20px;">Start Job  <span aria-required="true" class="required"></span></label>
					 <div class="col-sm-8 input-group date">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
					<input type="text" id="startdate" class="form-control" data-date-format="DD-MM-YYYY" name="StartJob" value="<?php if(isset($StartJob)){ echo $StartJob; } else { echo $StartJob; } ?>">
					 </div>
			  </div>
							
			<div class="col-md-6">	
				<label for="enddate" class="col-sm-4 control-label" style="padding-right: 20px;">End Job  <span aria-required="true" class="required"></span></label>
					 <div class="input-group date col-sm-8">
						  <div class="input-group-addon">
							<i class="fa fa-calendar"></i>
						  </div>
				<input type="text" id="enddate" class="form-control" data-date-format="DD-MM-YYYY" name="End Job" value="<?php if(isset($EndJob)){ echo $EndJob; } else { echo $EndJob; } ?>">
					</div>
			</div>			
		  </div>
				  
				  <div class="form-group">
                                 <label class="col-sm-2 control-label" for="input-Default">Company</label>
                                 <div class="col-sm-4" style="margin-left: 0px; padding-left: 3px;">
                                    <select class="form-control select" name="CompanyName" id="CompanyName">
									  <option value="">Select Company</option>
									  <?php foreach($Getcompany as $comp){ ?>
									  <option value="<?php echo $comp->Id;?>" <?php if(isset($CompanyId)) {  if($CompanyId==$comp->Id) { echo 'selected'; } }?>><?php echo $comp->Name;?></option>
									  <?php } ?>
									</select>
                                 </div>
								 <label class="col-sm-2 control-label" for="input-Default">Job Status</label>
                                 <div class="col-sm-4" style="padding-left:5px;">
                                     <select class="form-control select" name="JobStatus" id="JobStatus">
									  <option value="">Select JobStatus</option>
									  <?php foreach($jobstatus as $jbstatus){ ?>
									  <option value="<?php echo $jbstatus->Id;?>" <?php if(isset($CompanyId)) {  if($JobStatusId==$jbstatus->Id) { echo 'selected'; } }?>><?php echo $jbstatus->StatusName;?></option>
									  <?php } ?>
									</select>
                                 </div>
                </div>
				  
				  <div class="form-group">
						    <div class="col-sm-12">
								<button type="button" id="btnclear" class="btn btn-primary col-md-2 pull-right">Clear</button>
								<button class="btn btn-primary col-md-2 pull-right" style="margin:0 15px 15px 0;" id="search_job"  type="submit">Search</button>
							</div>	
							<div class="col-md-4">
							
							</div>	
				</div>
				  
						
			  </div>
					
					<div class="col-md-3">
						<div id="checkbox-status" >
								   <div class="form-group">
										<div class="col-md-11 col-sm-6 col-xs-12">
											 <input type="checkbox" name="checked_box[]" id="scheduledjob" <?php if(isset($checkbox)){  if($checkbox=='Scheduled') { echo "checked='checked'"; } else{$checkbox='';} }?>  value="Scheduled">
											 <label for="status_pending">Scheduled Job Only</label>
										</div>
								  </div>
								  
						</div>
					</div>
					
					
					
					</form>
					</div>
			</div>
		 </div>
			
		
			<h3 class="panel-title">
			<a href="<?php echo base_url(); ?>jobhistory/jobhistoryadvanceform"><button class="btn btn-danger">Create Job Form</button></a>
			</h3>
			<div class="panel-body">
				<div class="table-responsive">
				<?php if(isset($filter_jobhistory_data)){ ?>
				
					<table id="filter_jobhistory" class="display table" style="width:100%; cellspacing: 0;">
						<thead>
							<tr>
								<th class="text-center" >Details</th>
								<th class="text-center">Job Id</th>
								<th class="text-center">Job Status</th>
								<th class="text-center">Company</th>
								<th class="text-center" >Customer Name</th>
								<th class="text-center" style="width:130px;">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php 
								for($i=0;$i<count($filter_jobhistory_data);$i++)	{
									
							?>
							<tr role="row">	
								<?php $filter_jobhistory_data[$i]->popup_icon = '<a class="search-details" href="javascript:void(0)" onclick="get_jobhistory_details('.$filter_jobhistory_data[$i]->Id.')"><i class="fa fa-search" aria-hidden="true"></i></a>'; ?>
								<td class="sorting" style="width:1%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->popup_icon; ?></td>
								<td class="sorting" style="width:5%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->JobId; ?></td>
								<td class="sorting" style="width:5%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->JobStatus; ?></td>	
								<td class="sorting" style="width:5%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->Company; ?></td>
								<td class="sorting" style="width:5%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->UserName; ?></td>
								<td class="sorting" style="width:5%!important;" tabindex="view" aria-controls="datatable" rowspan="1" colspan="1"  aria-label="view: activate to sort column ascending"><?php echo $filter_jobhistory_data[$i]->Action; ?></td>
							</tr>
							<?php } ?>	
						</tbody>
					</table>
				<?php } else { ?>	
				    <?php if(isset($GetJobHistoryData)){ ?>
							<table id="jobhistory" class="display table"  style="width:100%; cellspacing:0;">
								<thead>
									<tr>
										<th class="text-center" style="width:5%!important;">Details</th>
										<th class="text-center" style="width:8%!important;">Job Id</th>
										<th class="text-center" style="width:10%!important;">Job Status</th>
										<th class="text-center" style="width:10%!important;">Company</th>
										<th class="text-center" style="width:12%!important;">Customer Name</th>
										<th class="text-center" style="width:10%!important;">Time Zone</th>
										<th class="text-center" style="width:10%!important;">Created At</th>
										<th style="width:14%!important;">Action</th>
									</tr>
								</thead>
							</table>	
					<?php } ?>
				<?php } ?>
				</div>
			</div>
		</div>		 
	</div>
	
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mb-cancel-row" class="modal fade" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
					<h4 id="myModalLabel" class="modal-title">CANCEL <strong>Data</strong></h4>
				</div>
				<div class="col-md-12 form-group">
						<span><p class="modal-title set_error_msg" style="display:none;color:red;"></p></span>
					</div>
				<div class="modal-body">
					<p>Are you sure you want to Cancel this row?</p>
					<div class="col-bd-12">
						<div class="col-md-2">
							<label>Reason :</label>
						</div>
							<div class="col-md-8">
								<input type="text" class="form-control" placeholder="Add Proper Reason" id="valide_reason" name="valide_reason"><br>
								<span><p class="err_msgg" style="display:none;color:red;">Plz Enter The Reason For Cancel the Job</p></span>
							</div><br>
							<div class="col-md-2"></div><br>
					</div>
					<div class="col-bd-12">
						<div class="col-md-4">
							<input type="checkbox" name="Cancel" id="Cancel" >Parmanent Cancel<br>
						</div>
						<div class="col-bd-8"></div>
					</div><br><br><br>
					<b><p>Press Yes if you sure.</p></b>
				</div>
				<div class="modal-footer">
				    <button class="btn btn-success btn-lg mb-control-yes" id="yesbtn">Yes</button>
					<button type="button" class="btn btn-default btn-lg mb-control-close reject" data-dismiss="modal">No</button>
				</div>
			</div>
		</div>
	</div>
	
	<!--JobHistory Modal Popup -->
	<div class="modal fade jobhistory-popup" id="transacton_popup" tabindex="-1" aria-labelledby="myModalLabel" role="dialog">
				<div class="modal-dialog">
				  <!-- Modal content-->
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h3 class="modal-title" id="popup_title" > </h3>
					</div>
					<div class="modal-body col-md-12" id="transacton-details">
					</div>
					<div class="modal-footer">
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				</div>
	 </div>
	
	<style type="text/css">
		.label.label-Created {
		   background: #FF9800;
    	   color: #ffffff;
		}
		.label.label-Canceled {
		   background: #ec5e69;
    	   color: #ffffff;
		}
		.label.label-Accepted {
		   background: #0070e0;
    	   color: #ffffff;
		}
		.label.label-Inprogress{
		   background: #00BCD4;
    	   color: #ffffff;
		}
		.label.label-Completed {
		   background: #4CAF50;
    	   color: #ffffff;
		}
		.tx_bx
		{
			border:1px solid red;
		}
		
		#filter_return_receipt_view_processing 
		{
			background: url("<?php echo asset_url(); ?>images/ajax-loader.gif") 	no-repeat scroll 0 0; !important;
			height: 100%;
			margin: 0 auto;
			z-index: 1000;
		}
		.jobhistory-popup .modal-dialog {
			color: #333333;
			font-size: 14px;
			width:65%!important;
		}
		
	</style>
	
	<script>
	/*** Button Click Event To Add custom Exported File name @ Krushna @ ****/	

	function cancel_raw_detail(obj)
	{
		var Id = obj;
		$("#yesbtn").click(function()
		{
			var Reason = $('#valide_reason').val();
			var Chk_box = $('#Cancel').val();
			
			if($('#Cancel').is(":checked")) 
			{
				if(Reason=='')
				{
					//alert('XXXXXXX');
					$('.err_msgg').css('display','block');
					$('#valide_reason').addClass('tx_bx');
					setTimeout(function()
					{
						$('.err_msgg').css('display','none');
						$("#valide_reason").removeClass("tx_bx")
						location.reload(); 
					},2000);
					
					
					
				}
				else
				{
					
					$.ajax({
								url:"<?php echo base_url(); ?>jobhistory/Tick_ChkBox",  
								type:'post',
								data:{
										Id:Id,
										Reason:Reason
									 },
									
									success: function(data)
									{
										var data = $.parseJSON(data);
										if(data.success==true)
										{
											$('.set_error_msg').html(data.message).show();	
											setTimeout(function()
											{
												$('.set_error_msg').css('display','block');
												$("#mb-cancel-row").modal('hide');
												location.reload(); 
											},4000);

										}
										else
										{
											$('.set_error_msg').html(data.message).show();
											$("#mb-cancel-row").modal('show');
										}
									}
							});	
				}
				return false;
			} 
			else 
			{
				
				if(Reason=='')
				{
					
					$('.err_msgg').css('display','block');
					$('#valide_reason').addClass('tx_bx');
					setTimeout(function()
					{
						$('.err_msgg').css('display','none');
						$("#valide_reason").removeClass("tx_bx")
						location.reload(); 
					},2000);
				}
				else
				{
				
					$.ajax({
						url:"<?php echo base_url(); ?>jobhistory/removedata",  
						type:'post',
						data:{
								Id:Id,
								Reason:Reason
							 },
							//dataType: 'JSON',
							success:function(data)
							{
								var data = $.parseJSON(data);
								if(data.success==true)
								{
									$('.set_error_msg').html(data.message).show();	
									setTimeout(function()
									{
										$('.set_error_msg').css('display','block');
										$("#mb-cancel-row").modal('hide');
										location.reload(); 
									},4000);
								}
								else
								{
									$('.set_error_msg').html(data.message).show();
									$("#mb-cancel-row").modal('show');
								}
							}
					});	
				}
				return false;
			}
		});
	}
	
	/** For Cancel Button click of Export modal popup To empty textbox value @ KRUSHNA @ **/	
	
	$(document).ready($(function () 
	{
        $(".reject").on("click", function (){
            var valide_reason = $('#valide_reason').val();
            var Chk_box = $('#Cancel').is(":checked");
			
			if(valide_reason)
			{
			   $('#valide_reason').val('');
			}
			$("#mb-cancel-row").modal('hide');
		});
    }));
    
      /*Modal Popup js function for jobhistory goes here*/
	function get_jobhistory_details(id)
	{
		 var myid = id;
		  $.ajax({
			 type: "POST",
			 url: baseurl+"jobhistory/get_datamodal_jobhistory/",
			 data:{myid:myid},
			 success: function(res){                   
			    
				var data = $.parseJSON(res)
				$("#transacton-details").html(data.html); 
				$('#transacton_popup').modal('show');
				//$("#popup_title").html(data.Id);
				$("#popup_title").html('Job History Details');
				
				}
		  });
	}
    
    
	</script>
	