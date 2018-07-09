<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Responsive Admin Dashboard Template">
        <meta name="keywords" content="admin,dashboard">
        <meta name="author" content="stacks">
       
        
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
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/summernote-master/summernote.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/nvd3/nv.d3.min.css"/>
        
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/select2.min.css" rel="stylesheet"/>

		<!-- datepicker-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/bootstrap-datepicker/css/datepicker3.css"/>      
        <!-- Daterange picker -->
	<link rel="stylesheet" href="<?php echo base_url() ?>assets/plugins/daterangepicker/daterangepicker.css">
		<!-- color picker-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.css"/>      
		<!-- time picker-->
		<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css"/>
        <!-- Theme Styles -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/css/bootstrap-multiselect.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datatables/css/jquery.datatables.min.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datatables/css/jquery.datatables.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/datatables/css/jquery.datatables_themeroller.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>assets/plugins/jqvmap/jqvmap.css"/>
		<link href="<?php echo base_url() ?>assets/css/space.min.css" rel="stylesheet">
        
		<!-- load js datatatables-->
		<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-1.11.2.min.js"></script>
		<!--<script src="<?php echo base_url() ?>assets/plugins/jquery/jquery-3.1.0.min.js"></script>-->
		<script src="<?php echo base_url() ?>assets/plugins/datatables/js/jquery.datatables.js"></script>   
		<script src="<?php echo base_url() ?>assets/plugins/jquery-validation/jquery.validate.js"></script>
       
		<!-- for dropdown search -->
		<link rel="stylesheet" href="<?php echo base_url() ?>assets/css/bootstrap-select.min.css" />
		<script src="<?php echo base_url() ?>assets/js/bootstrap-select.min.js"></script>
		
		<link href="<?php echo base_url() ?>assets/plugins/jquery/jquery-ui.css" rel="Stylesheet">
		<link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.css" rel="stylesheet">
        <link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.min.css" rel="stylesheet">
        
		<link href="<?php echo base_url() ?>assets/css/easy-autocomplete/easy-autocomplete.themes.min.css" rel="stylesheet">
		<style>
			.logo-side{display: none;}
		</style>


       
    </head>
    <body>
        <?php if(!defined('BASEPATH')) exit('No direct script access allowed');
        if(!function_exists('active_link')) {
         function activate_menu($controller) {
        // Getting CI class instance.
        $CI = get_instance();
        // Getting router class to active.
        $class = $CI->router->fetch_class();
       return ($class == $controller) ? 'active-page' : '';
         }
       }?>
        <!-- Page Container -->
        <div class="page-container">
            <!-- Page Sidebar -->
            <div class="page-sidebar">
                <a class="logo-box" href="<?php echo base_url()?>">
					<img class="logo-side" src="<?php echo base_url() ?>assets/images/logo-Icon.png" width="40px"/>
                    <img class="logo-full" src="<?php echo base_url() ?>assets/images/logo-2.png" width="100%" />
                    

                    <!-- <span>SSTech Driver</span> -->
                    <!-- <i class="icon-radio_button_unchecked" id="fixed-sidebar-toggle-button"></i>
                    <i class="icon-close" id="sidebar-toggle-button-close"></i> -->
                </a>
				
				
                <div class="page-sidebar-inner">
                    <div class="page-sidebar-menu">
                        <ul class="accordion-menu">
							<li >

                                <a href="<?php echo base_url()?>editprofile" class="user-title">
                                   <i class="menu-icon icon-user"></i>
                                   <span class="udesk"><?php if(isset($name)) { echo $name; }?><br><?php if(isset($name)) { echo $email; } ?></span>

                                </a>


                            </li>
							<li class="menu-divider"></li>
                            <li  class="<?php echo activate_menu('dashboard'); ?>">
                                <a href="<?php echo base_url()?>dashboard">
                                    <i class="menu-icon icon-home4"></i><span>Dashboard</span>
                                </a>
                            </li>
							<!-- for super admin start-->
							
							<?php if($roleid == '1'){ ?>
							<li class="<?php echo activate_menu('companymaster'); ?>">
                                <a href="<?php echo base_url()?>companymaster">
                                    <i class="menu-icon icon-code"></i><span>Company Management</span>
                                </a>
                            </li>
							<li class="<?php echo activate_menu('usermaster'); ?>">
                                <a href="<?php echo base_url()?>usermaster">
                                    <i class="menu-icon icon-users"></i><span>User Management</span>
                                </a>
                            </li>
							<li class="<?php echo activate_menu('drivermaster'); ?>">
                                <a href="<?php echo base_url()?>drivermaster">
                                    <i class="menu-icon icon-accessible"></i><span>Driver Management</span>
                                </a>
                            </li>	
							<?php } ?>
							<!-- for super admin end-->
							<li class="<?php echo activate_menu('companydriver'); ?>">
                                <a href="<?php echo base_url()?>companydriver">
                                    <i class="menu-icon icon-truck"></i><span>Company Driver</span>
                                </a>
                            </li>

                            <li class="<?php echo activate_menu('jobhistory'); ?>">
                                <a href="<?php echo base_url()?>jobhistory">
                                    <i class="menu-icon icon-users"></i><span>Job Management</span>
                                </a>
                            </li>
                            
                            <?php if($roleid == '1'){ ?>
                            <li class="<?php echo activate_menu('customermaster'); ?>">
                                <a href="<?php echo base_url()?>customermaster">
                                    <i class="menu-icon icon-user"></i><span>Customer Management</span>
                                </a>
                            </li>

                            <li class="<?php echo activate_menu('apilog'); ?>">
                                <a href="<?php echo base_url()?>apilog">
                                    <i class="menu-icon icon-map"></i><span>ApiLog</span>
                                </a>
                            </li>
                            
                            <?php } ?>

							<li class="<?php echo activate_menu('findmap'); ?>">
                                <a href="<?php echo base_url()?>findmap">
                                    <i class="menu-icon icon-map"></i><span>Find Map</span>
                                </a>
                            </li>   
							<!-- for super admin and admin and driver start-->
							<?php if($roleid == '1' || $roleid == '2' || $roleid == '3' ){ ?>
							
							<?php } ?>
							<!-- for super admin and admin and driver end-->
							
							
							
							
                            <!--<li>
                                <a href="javascript:void(0)">
                                    <i class="menu-icon icon-user"></i><span>attributes</span><i class="accordion-icon fa fa-angle-left"></i>
                                </a>
                                <ul class="sub-menu">
                                    <li><a href="<?php echo base_url()?>login">login</a></li>
                                    
                                </ul>
                            </li>-->
							
                        </ul>
                    </div>
                </div>
            </div><!-- /Page Sidebar -->
			<!-- Page Content -->
            <div class="page-content">
                <!-- Page Header -->
                <div class="page-header">
                    
                    <nav class="navbar navbar-default">
                        <div class="container-fluid">
                            <!-- Brand and toggle get grouped for better mobile display -->
                            <div class="navbar-header">
                                <div class="logo-sm">
                                    <a href="javascript:void(0)" id="sidebar-toggle-button"><i class="fa fa-bars"></i></a>
                                    <a class="logo-box" href="index.html"><span>SSTech Driver</span></a>
                                </div>
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                    <i class="fa fa-angle-down"></i>
                                </button>
                            </div>
                        
                            <!-- Collect the nav links, forms, and other content for toggling -->
                        
                            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                <ul class="nav navbar-nav">
                                    <li><a href="javascript:void(0)" id="collapsed-sidebar-toggle-button"><i class="fa fa-bars"></i></a></li>
                                    <li><a href="javascript:void(0)" id="toggle-fullscreen"><i class="fa fa-expand"></i></a></li>
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                   
                                    <li class="dropdown">
                                        <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-bell"></i></a>
                                        <ul class="dropdown-menu dropdown-lg dropdown-content">
                                            <li class="drop-title">Notifications<a href="#" class="drop-title-link"><i class="fa fa-angle-right"></i></a></li>
                                            <li class="slimscroll dropdown-notifications">
                                                <ul class="list-unstyled dropdown-oc">
                                                    <li>
                                                        <a href="#"><span class="notification-badge bg-primary"><i class="fa fa-photo"></i></span>
                                                            <span class="notification-info">Finished uploading photos to gallery <b>"South Africa"</b>.
                                                                <small class="notification-date">20:00</small>
                                                            </span></a>
                                                    </li>
                                               </ul>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="dropdown user-dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="http://via.placeholder.com/36x36" alt="" class="img-circle"></a>
                                        <ul class="dropdown-menu">
                                           
										   <li><a href="<?php echo base_url() ?>editprofile">Profile</a></li>
										 
                                            <!--<li><a href="#">Calendar</a></li>
                                            <li><a href="#"><span class="badge pull-right badge-danger">42</span>Messages</a></li>
                                            <li role="separator" class="divider"></li>-->
                                            <li><a href="#">Account Settings</a></li>
                                            <li><a href="<?php echo base_url() ?>login/logout">Log Out</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div><!-- /.navbar-collapse -->
                        </div><!-- /.container-fluid -->
                    </nav>
                </div><!-- /Page Header -->
                <!-- Page Inner -->