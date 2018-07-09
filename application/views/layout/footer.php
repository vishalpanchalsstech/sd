	</div><!-- /Page Container -->
	</div><!-- /Page Container -->
	<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="mb-remove-row" class="modal fade" style="display: none;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
					<h4 id="myModalLabel" class="modal-title">Remove <strong>Data</strong></h4>
				</div>
				<div class="modal-body">
					 <p>Are you sure you want to remove this row?</p>                    
                        <p>Press Yes if you sure.</p>
				</div>
				<div class="modal-footer">
				 <button class="btn btn-success btn-lg mb-control-yes">Yes</button>
                  <button type="button" class="btn btn-default btn-lg mb-control-close" data-dismiss="modal">No</button>
				</div>
			</div>
		</div>
	</div>
	 <!-- js-->
	   <script> var baseurl = '<?php echo base_url(); ?>';  </script>
	   
	 
	    <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
        <script src="<?php echo base_url() ?>assets/plugins/datatables/js/jquery.datatables.min.js"></script>
		<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-ui.js" ></script>
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/uniform/js/jquery.uniform.standalone.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/switchery/switchery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/d3/d3.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/nvd3/nv.d3.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.time.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.symbol.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.resize.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.tooltip.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/flot/jquery.flot.pie.min.js"></script>
        <!--<script src="<?php echo base_url() ?>assets/plugins/chartjs/chart.min.js"></script>-->
		<!-- datepicker-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>	
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
        <!-- daterangepicker js goes here-->
	<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- color picker-->
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.js"></script>
	<!-- time picker-->
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/summernote-master/summernote.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/summernote-master/summernote.min.js"></script>
		<script src="<?php echo base_url() ?>assets/js/bootstrap3-typeahead.min.js"></script>
		<!--multiselect-->
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/pages/form-wizard.js"></script>
		<script src="<?php echo base_url() ?>assets/js/bootstrap-multiselect.js"></script>
		<script src="<?php echo base_url() ?>assets/js/space.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/custom.js"></script>
		<script src="<?php  echo base_url() ?>assets/js/jquery.easy-autocomplete.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jquery.easy-autocomplete.js"></script>
        <script src="<?php echo base_url(); ?>assets/plugins/select2/select2.full.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/jstz-1.0.4.min.js"></script>
		   
	<script type="text/javascript">
	 $(".alert-success").fadeTo(5000, 500).slideUp(500, function(){		
	 $(".alert-success").slideUp(500);		});		
   $(".alert-danger").fadeTo(5000, 500).slideUp(500, function(){		$(".alert-danger").slideUp(500);		});
		$('.datepicker').datepicker({
			format: 'dd/mm/yyyy'
		});
		$('.colorpicker').colorpicker({
		});
		$("#timepicker1").timepicker({
		});
		$(document).ready(function(){
			$('#drp').multiselect({
			nonSelectedText: 'Select Vehicle No',
			enableFiltering: true,
			enableCaseInsensitiveFiltering: true,
			buttonWidth:'400px'
			});
		});	
		$('.summernote').summernote({
	
		});
		$(function (){
			$('#recordshow').DataTable({
			});
		});
		
	</script>
	</body>
</html>