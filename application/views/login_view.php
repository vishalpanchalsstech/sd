<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
        <!-- The above 6 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <!-- Title -->
        <title>SSTech Driver</title>
		<?php //echo base_url();exit; ?>
        <!-- Styles -->
        <link href="https://fonts.googleapis.com/css?family=Ubuntu" rel="stylesheet">	
		<!--multiselect-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/bootstrap/css/bootstrap.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/font-awesome/css/font-awesome.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/icomoon/style.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/uniform/css/default.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/switchery/switchery.min.css"/>
		<link href="<?php echo base_url() ?>assets/css/space.min.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/custom.css" rel="stylesheet">
		
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
        <!-- Page Container -->
        <div class="page-container">
            <!-- Page Inner -->
            <div class="page-inner login-page">
				<div id="main-wrapper" class="container-fluid">
				 
					<div class="row">
						<div class="col-sm-6 col-md-3 login-box ">
							<img src="<?php echo base_url() ?>assets/images/logo-2.png" width="100%" />
							<h4 class="login-title">Sign in to your account</h4>
							<form id="jvalidate" action="<?php echo base_url(); ?>login/process" method="post">
							 <?php if(isset($msg)) echo $msg; ?>
					<?php if(isset($msgf)) echo $msgf; ?>
								<div class="form-group col-md-12 no-padding">
									<label for="exampleInputEmail1">Email address</label>
									<input type="email" name="email" id="email" class="form-control" placeholder="Email" required>
								</div>
								<div class="form-group col-md-12 no-padding">
									<label for="exampleInputPassword1">Password</label>
									<input type="password" name="password" id="password" class="form-control" placeholder="Password" required >
								</div>
								<!--<button type="submit"><a href="" class="btn btn-primary">Login</a></button>-->
								<div class="form-group">
								<div class="col-md-4">
								<button class="btn btn-primary" type="submit">Sign In</button>
								</div>
								<div class="col-md-2">
								</div>
								<div class="col-md-6">
								<a href="<?php echo base_url(); ?>driverregister" class="btn btn-default">Driver Register</a>
								</div>
								</div>
								<!--<a href="index.html" class="forgot-link">Forgot password?</a>-->
							</form>
						</div>
					</div>
				</div>
            </div><!-- /Page Content -->
        </div><!-- /Page Container -->
		
	 <!-- js-->
	 <script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-3.1.0.min.js"></script>
	 <script src="<?php echo base_url() ?>assets/plugins/jquery-validation/jquery.validate.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/uniform/js/jquery.uniform.standalone.js"></script>
        <script src="<?php echo base_url() ?>assets/plugins/switchery/switchery.min.js"></script>
        <script src="<?php echo base_url() ?>assets/js/space.min.js"></script>
		<script type="text/javascript">
		 $(".alert-success").fadeTo(5000, 500).slideUp(500, function(){		
		$(".alert-success").slideUp(500);		
   });		
   $(".alert-danger").fadeTo(5000, 500).slideUp(500, function(){		
		$(".alert-danger").slideUp(500);		
	});
		var jvalidate = $("#jvalidate").validate({
			ignore: [],
			rules: 
			{
				email: 
				{
					required: true,
					email: true
				},
				password: 
				{
					required: true,
				},
				
			}
		});
    </script>
	<style>
.login-box
{
	width:30% !important;
}
</style>
	</body>
	</html>