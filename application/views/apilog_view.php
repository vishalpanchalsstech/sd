
<?php if(isset($msg)){ echo $msg; } ?>
	<div class="page-inner">
		<div class="page-title">
            <h3 class="breadcrumb-header">ApiLog</h3>
		</div>
	<div class="panel panel-default">		
		<div class="panel-body"	>
			<div class="table-responsive">
				<table id="recordshow" class="display table" style="width: 100%; cellspacing: 0;">
				<thead>
					<tr>
						<th class="text-center" style="width:10%!important;">Id</th>
						<th class="text-center" style="width:10%!important;">Api Url</th>
						<th class="text-center" style="width:10%!important;">Request Type</th>
						<!-- <th class="text-center">Request</th>
						<th class="text-center">Response</th> -->
						<th class="text-center" style="width:15%!important;">Response Code</th>
						<th class="text-center" style="width:10%!important;">Origin IP</th>
						<th class="text-center" style="width:12%!important;">Request By</th>
						<th class="text-center" style="width:15%!important;">Response Time</th>
						<th class="text-center" style="width:12%!important;">Created At</th>
					</tr>
				</thead>
				<tbody>
				<?php if(isset($GetApiData))
						{
							$i = 1;
							$time=date('d-m-Y h:i:s');
							foreach($GetApiData as $row)
							{ //echo "<pre>";print_r($GetApiData);exit();
								
								$RequestBy = $row->RequestBy;
								$chk_token = $this->general_model->token_matching($RequestBy);
								$chk_token = $chk_token[0]->Name;
								//echo "<pre>";print_r($chk_token);exit();
				?>
					<tr>
						<?php $row->Id = '<a class="search-details" href="javascript:void(0)" onclick="get_row_detail_api('.$row->Id.')"><i style="padding:5px;" class="fa fa-search" aria-hidden="true"></i></a>'; ?>
						<td class="text-center"><?php echo $row->Id=$row->Id.$i;  ?></td>
						<td class="text-center"><?php echo $row->ApiUrl;?></td>
						<td class="text-center"><?php echo $row->RequestType;?></td>
					<!--<td><?php //echo $row->Request; ?></td>
						<td><?php //echo $row->Response; ?></td> -->
						<td class="text-center"><?php echo $row->ResponseCode; ?></td>
						<td class="text-center"><?php echo $row->OriginIP; ?></td>
						<!-- <td><?php echo $row->RequestBy; ?></td> -->
						<td class="text-center"><?php echo $chk_token; ?></td>
						<td class="text-center"><?php echo $row->ResponseTime; ?></td>
						<td class="text-center"><?php echo $row->CreatedAt=$time;?></td>
					</tr>
							
				<?php $i++; } } ?>
				</tbody>
				</table>
			</div>
		</div>
    </div>			 
</div>
	<div class="modal fade booking-popup" id="api_popup" tabindex="-1" aria-labelledby="myModalLabel" role="dialog">
				<div class="modal-dialog">
				  <!-- Modal content-->
				  <div class="modal-content">
					<div class="modal-header">
					  <button type="button" class="close" data-dismiss="modal">&times;</button>
					  <h3 class="modal-title" id="popup_title" > </h3>
					</div>
					<div class="modal-body col-md-12" id="apitrace_popup">
					</div>
					<div class="modal-footer">
					<div class="modal-body col-md-12" style="text-align:center;color:#FF392E;" id="resend_msg"></div>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					  
					</div>
				  </div>
				</div>
		</div>
<script type="text/javascript">
	function get_row_detail_api(id)
	{
		//alert(id);
		 var myid = id;
		  $.ajax({
			 type: "POST",
			 url: baseurl+"apilog/api_view_Getdata/"+myid,
			 data:myid, 
			 //dataType: "json",
				success: function(res){
			     var data = $.parseJSON(res)
				// alert(data);return false; 
				$("#apitrace_popup").html(data); 
			    $("#resend_msg").html();
				$('#api_popup').modal('show');
				$("#popup_title").html('ApiLog Details');
			}
		});
	}
</script>
	
		