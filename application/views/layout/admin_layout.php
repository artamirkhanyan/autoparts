<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <link rel="apple-touch-icon" sizes="57x57" href="<?=base_url()?>public/images/fav/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?=base_url()?>public/images/fav/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?=base_url()?>public/images/fav/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?=base_url()?>public/images/fav/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?=base_url()?>public/images/fav/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?=base_url()?>public/images/fav/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?=base_url()?>public/images/fav/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?=base_url()?>public/images/fav/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=base_url()?>public/images/fav/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="<?=base_url()?>public/images/fav/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="<?=base_url()?>public/images/fav/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="<?=base_url()?>public/images/fav/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="<?=base_url()?>public/images/fav/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="<?=base_url()?>public/images/fav/manifest.json">
    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    
    <!-- jQuery -->
    <script src="<?=base_url()?>public/js/jquery-min.js"></script>
    <link href="<?=base_url()?>public/css/jquery-ui.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>public/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url()?>public/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=base_url()?>public/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <!--My Css-->
    <link href="<?=base_url()?>public/css/admin.css" rel="stylesheet">

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/admin/statistics">YerevanMotors</a>
            </div>
            <!-- /.navbar-header -->
            <?php
                if($this->session->userdata('currency') == 'amd'){
                    $currency = 'amd';
                }else{
                    $currency = 'usd';
                }
            ?>
            <ul class="nav navbar-top-links navbar-right">
                <li>
                    <code>1$ = <?php echo $this->session->userdata('rate')?> դրամ</code>
                </li>
                                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-cogs fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="<?php echo site_url('admin/chagePass');?>"><i class="fa fa-user fa-fw"></i> Փոխել գաղտնաբառը</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo site_url('admin/logOut');?>"><i class="fa fa-sign-out fa-fw"></i> Դուրս գալ</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <form class="form-inline" target="_blank" method="get" action="<?php echo site_url('/search');?>" >
                                    <input style="width: 80%;" type="search" name="parts_search" class="form-control" placeholder="Փնտրել դետալ">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </form>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/allOrders');?>"><i class="fa fa-table fa-fw"></i> <?=$this->lang->line('orders')?></a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/upload');?>"><i class="fa fa-upload fa-fw"></i> Ներբեռնել</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/addShop');?>"><i class="fa fa-university fa-fw"></i> Խանութներ</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/weights');?>"><i class="fa fa-wrench fa-fw"></i> Դետալների քաշեր</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/setKoef');?>"><i class="fa fa-edit fa-fw"></i> Գնային Գործակիցներ</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/users');?>"><i class="fa fa-users fa-fw"></i> Գրանցված հաճախորդներ</a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o  fa-fw"></i> Վիճակագրություն<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href="<?php echo site_url('admin/statistics/finances');?>"> Ֆինանսներ</a></li>
                                <li><a href="<?php echo site_url('admin/statistics/topdetails');?>"> Դետալներ</a></li>
                                <li><a href="<?php echo site_url('admin/statistics/customers');?>"> Հաճախորդներ</a></li>
                            </ul>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/announcement');?>"><i class="fa fa-bullhorn fa-fw"></i> Հայտարարություն</a>
                        </li>
                        <li>
                            <a href="<?php echo site_url('admin/exchange');?>"><i class="fa fa-dollar fa-fw"></i> Փոխարժեք</a>
                        </li>
<!--                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <!-- Page Content -->
        <div id="page-wrapper" style="padding-top: 20px;">
            <div class="container-fluid">
                <div class="row">
                   <?php echo $content_for_layout ?>
                </div>
                
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
    
    <div class="overlay-wrapper">
        <div class="loading-overlay"></div>
        <div class="overlay-ajax-loader">
            <img class="ajax-loader-big" src="<?=base_url()?>public/img/preloader.gif" />
	</div>
    </div>
    

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>public/js/bootstrap.min.js"></script>
    
    <!-- UI Core js -->
    <script src="<?=base_url()?>public/js/jquery-ui.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>public/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>public/js/sb-admin-2.js"></script>
    
    <!--My Admin js-->
    <script src="<?=base_url()?>public/js/admin_init.js"></script>

</body>

</html>
