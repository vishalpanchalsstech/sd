
                <div class="page-inner">
                    <div class="page-title">
                        <h3 class="breadcrumb-header">Dashboard</h3>
                    </div>
                    <?php if(isset($lastlogin)) {?>
                    <div class="row"> 
                    <div class="col-lg-12"> 
                        <div class="alert alert-success alert-dismissible" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                          <strong>Last Login At</strong> <?php echo date('d-m-Y H:i:s',strtotime($lastlogin)); ?>
                        </div>
                        
                    </div>
                    </div>
                    <?php } ?>
                    <div id="main-wrapper">
                        <?php if($roleid==1){?>
                        <div class="row">
                            <div class="col-lg-3 col-md-6">
                                <div class="panel panel-white stats-widget">
                                    <a href="<?php echo base_url(); ?>usermaster">
                                    <div class="panel-body">
                                        <div class="pull-left">
                                            <span class="stats-number"><?php if($totalusers){echo $totalusers;}?></span>
                                            <p class="stats-info">Total Admin Users</p>
                                        </div>
                                        <div class="pull-right">
                                            <i class="icon-users stats-icon"></i>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="panel panel-white stats-widget">
                                    <a href="<?php echo base_url(); ?>drivermaster">
                                    <div class="panel-body">
                                        <div class="pull-left">
                                            <span class="stats-number"><?php if($totaldriver){echo $totaldriver;}?></span>
                                            <p class="stats-info">Total Drivers</p>
                                        </div>
                                        <div class="pull-right">
                                            <i class="icon-menu-icon icon-accessible stats-icon"></i>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="panel panel-white stats-widget">
                                    <a href="<?php echo base_url(); ?>companymaster">
                                    <div class="panel-body">
                                        <div class="pull-left">
                                            <span class="stats-number"><?php if($totalcompany){echo $totalcompany;}?></span>
                                            <p class="stats-info">Total Company</p>
                                        </div>
                                        <div class="pull-right">
                                            <i class="icon-menu-icon icon-home4 stats-icon"></i>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6">
                                <div class="panel panel-white stats-widget">
                                    <a href="<?php echo base_url(); ?>customermaster">
                                    <div class="panel-body">
                                        <div class="pull-left">
                                            <span class="stats-number"><?php echo $totalcustomer;?></span>
                                            <p class="stats-info">Total Customer</p>
                                        </div>
                                        <div class="pull-right">
                                            <i class="icon-users stats-icon"></i>
                                        </div>
                                    </div>
                                    </a>
                                </div>
                            </div>
                        </div><!-- Row -->
                        
                   <?php }else{
                                    
                                    }?>
                    </div><!-- Main Wrapper -->
                    