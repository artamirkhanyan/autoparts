<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link href="<?=base_url()?>public/css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="<?=base_url()?>public/css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?=base_url()?>public/css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="<?=base_url()?>public/css/font-awesome.min.css" rel="stylesheet">
    
    <!--My Css-->
    <link href="<?=base_url()?>public/css/user.css" rel="stylesheet">

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
                <a class="navbar-brand" href="<?php echo site_url('');?>">Home</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" href="http://fiat.ilcats.ru/pid/25936">FIAT</a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?php echo site_url('/currency/usd')?>">
                        <i> USD </i>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?php echo site_url('/currency/amd')?>">
                        <i> AMD </i>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="<?php echo site_url('/cart')?>">
                        <i class="fa fa-cart-plus user_cart">  
                            
                            <?php                                 
                                if (!empty($this->session->userdata('user_cart')['owndb']) || !empty($this->session->userdata('user_cart')['externaldb'])) {
                                    $cart_count = $this->session->userdata('user_cart');
                                    echo count($cart_count['owndb']) + count($cart_count['externaldb']);
                                }else{
                                   echo ""; 
                                }
                                
                            ?>
                        </i>
                    </a>
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i id="user_email">&nbsp<?php echo $this->session->userdata('user_id')['email'];?></i>
                    </a>
                </li>
                <!-- /.dropdown -->
                <?php if(!$this->session->userdata('user_id')): ?>
                <li class="dropdown">
                    <a href="<?php echo site_url('auth/login');?>">
                        <i >Log In</i></i>
                    </a>
                </li>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a href="<?php echo site_url('auth/registration');?>">
                        <i>Registration</i>
                    </a>
                </li>
                <?php endif;?>
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-user fa-fw"></i> User Profile</a>
                        </li>
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i> Settings</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="<?php echo site_url('auth/logOut');?>"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
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
                                <form class="form-inline" method="post" action="<?php echo site_url('search');?>" >
                                    <input style="width: 80%;" type="search" name="parts_search_input" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" type="submit">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </span>
                                </form>
                            </div>
                            <!-- /input-group -->
                        </li>
<!--                        <li>
                            <a href="<?php echo site_url('admin/statistics');?>"><i class="fa fa-bar-chart-o  fa-fw"></i> Statistics</a>
                        </li>-->
<!--                        <li>
                            <a href="#"><i class="fa fa-dashboard fa-fw"></i> Charts<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="flot.html">Flot Charts</a>
                                </li>
                                <li>
                                    <a href="morris.html">Morris.js Charts</a>
                                </li>
                            </ul>
                             /.nav-second-level 
                        </li>-->
<!--                        <li>
                            <a href="<?php echo site_url('admin/upload');?>"><i class="fa fa-table fa-fw"></i> Upload</a>
                        </li>-->
                        <li>
                            <a href="<?php echo site_url('search');?>"><i class="fa fa-edit fa-fw"></i> Search</a>
                        </li>
                        <?php if (!empty($this->session->userdata('user_id'))):?>
                        <li>
                            <a href="<?php echo site_url('orders');?>"><i class="fa fa-edit fa-fw"></i> Orders</a>
                        </li>
                        <?php endif;?>
<!--                        <li>
                            <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="panels-wells.html">Panels and Wells</a>
                                </li>
                                <li>
                                    <a href="buttons.html">Buttons</a>
                                </li>
                                <li>
                                    <a href="notifications.html">Notifications</a>
                                </li>
                                <li>
                                    <a href="typography.html">Typography</a>
                                </li>
                                <li>
                                    <a href="icons.html"> Icons</a>
                                </li>
                                <li>
                                    <a href="grid.html">Grid</a>
                                </li>
                            </ul>
                             /.nav-second-level 
                        </li>-->
<!--                        <li>
                            <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Second Level Item</a>
                                </li>
                                <li>
                                    <a href="#">Third Level <span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                        <li>
                                            <a href="#">Third Level Item</a>
                                        </li>
                                    </ul>
                                     /.nav-third-level 
                                </li>
                            </ul>
                             /.nav-second-level 
                        </li>-->
<!--                        <li class="active">
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a class="active" href="blank.html">Blank Page</a>
                                </li>
                                <li>
                                    <a href="login.html">Login Page</a>
                                </li>
                            </ul>
                             /.nav-second-level 
                        </li>-->
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

    <!-- jQuery -->
    <script src="<?=base_url()?>public/js/jquery-min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>public/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>public/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>public/js/sb-admin-2.js"></script>
    
    <!--My Admin js-->
    <script src="<?=base_url()?>public/js/init.js"></script>

</body>

</html>

