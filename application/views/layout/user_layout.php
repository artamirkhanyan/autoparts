<html><head>

<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title><?=isset($pTitle)?$pTitle.' |':'' ?> YerevanMotors</title>
<meta name="description" content="yerevanmotors.com - <?php echo $this->lang->line('slagon'); ?>">
<link href="http://fonts.googleapis.com/css?family=Julius+Sans+One" rel="stylesheet" type="text/css">
<link href='http://fonts.googleapis.com/css?family=Exo+2:400,100&subset=latin,cyrillic' rel='stylesheet' type='text/css'>



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
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-TileImage" content="<?=base_url()?>public/images/fav/mstile-144x144.png">
<meta name="theme-color" content="#ffffff">


<!-- Bootstrap Core CSS -->
<link href="<?=base_url()?>public/css/bootstrap.min.css" rel="stylesheet">

<!-- MetisMenu CSS -->
<link href="<?=base_url()?>public/css/metisMenu.min.css" rel="stylesheet">   

<!-- Custom Fonts -->
<link href="<?=base_url()?>public/css/font-awesome.min.css" rel="stylesheet">

<!--template style-->
<link href="<?=base_url()?>public/css/style.css" rel="stylesheet" type="text/css" media="all">

<!--My Css-->
<link href="<?=base_url()?>public/css/user.css" rel="stylesheet">

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-63756381-1', 'auto');
  ga('send', 'pageview');

</script>

</head>
<body>



<!--Background image-->
<div class="bg_image"></div>
<!-- start content -->
<div class="content_bg">
<div class="wrap">
<div class="wrapper">
	<div class="main">
        <!--Slogan container-->
        <div class="slogan">
            <div id="logo_img" title="yerevanmotors"></div>
            <div id="slogan_wrapper">
                <a href="<?=  base_url();?>"> 
                    <span class="slogan_1">YEREVANMOTORS.COM</span>
                    <span class="slogam_2"><?php echo $this->lang->line('slagon'); ?></span>
                </a>
            </div>
            <div class="lang-holder">
                <?php if(!$this->session->userdata('lang') || $this->session->userdata('lang')=='armenian'): ?>
                <?php $class = 'arm-button';?>
                    <a class="selected-lang">Հայերեն</a> 
                    |
                    <a href="<?=base_url('setLang/russian');?>">Русский</a>
                <?php else:?>
                    <?php $class = 'rus-button';?>
                    <a href="<?=base_url('setLang/armenian');?>">Հայերեն</a> 
                    |
                    <a class="selected-lang">Русский</a>
                <?php endif;?>
                
            </div>
        </div>
	<!-- start content_left -->
	<div class="content_left">
            <div class="text3-nav">
                <ul>                    
                    <li>
                        <a class="cart_li" href="<?php echo base_url();?>" title="<?=$this->lang->line('homepage'); ?>">
                            <span class="label_pointer"><?=$this->lang->line('homepage'); ?></span>
                        </a>
                    </li>
                     <?php if(!$this->session->userdata('user_id')): ?>
                        <li>
                            <a class="cart_li" href="<?php echo site_url('orders');?>" title="Заказы">
                                <i class="fa fa-file-text-o user_orders" style="margin-right: -16px;"></i>
                                <span class="label_pointer"><?=$this->lang->line('orders'); ?></span>
                            </a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
            <div class="text2-nav">
                <?php if($this->session->userdata('user_id')): ?>
                <!--User info-->                
                <ul>                    
                    
                    <li>
                        <a class="cart_li" href="<?php echo site_url('orders');?>" title="Заказы">
                            <i class="fa fa-file-text-o user_orders"></i>
                            <span class="label_pointer"><?=$this->lang->line('orders'); ?></span>
                        </a>
                    </li>
                    <li>
                        <?php $user = $this->session->userdata('user_id');?>
                        <a class="cart_li" title="">
                            <i class="fa fa-user user_orders"></i>
                            <span class="label_pointer"><?=$user['f_name'].' '.$user['l_name']?></span>
                        </a>
                    </li>
                    <li>
                        <a class="cart_li" href="<?php echo site_url('auth/logOut');?>" title="Выйти">
                            <i class="fa fa-sign-out user_signout"></i>
                            <span class="label_pointer"><?=$this->lang->line('logout')?></span>
                        </a>
                    </li>                    
                </ul>     
                <?php endif; ?>
            </div>
            
            <div class="text1-nav">
                <h2><?php echo $this->lang->line('catalog'); ?></h2>
                <ul>
                    <li>
                        <a class="car_logo_a brands kia"    href=" http://kia.ilcats.ru/pid/26455"   title="Kia" target="_blank">
                            <span>Kia</span>
                        </a>                         
                        <a class="car_logo_a brands nissan" href="http://nissan.ilcats.ru/pid/26452" title="Nissan" target="_blank">
                            <span>Nissan</span>
                        </a>
                    </li>
                    <li>
                        <a class="car_logo_a brands toyota" href="http://toyota.ilcats.ru/pid/26448" title="Toyota" target="_blank">
                            <span>Toyota</span>
                        </a>
                        <a class="car_logo_a brands lexus"  href="http://lexus.ilcats.ru/pid/26453"  title="Lexus" target="_blank">
                            <span>Lexus</span>
                        </a>
                    </li>
                    <li>
                        <a class="car_logo_a brands infinity"   href="http://nissan.ilcats.ru/pid/26452"    title="Infinity" target="_blank">
                            <span>Infinity</span>
                        </a>
                        <a class="car_logo_a brands mitsubishi" href="http://mitsubishi.ilcats.ru/pid/26532" title="Mitsubishi" target="_blank">
                            <span>Mitsubishi</span>
                        </a>                        
                    </li>
                    <li>
                        <a class="car_logo_a brands hyundai" href="http://hyundai.ilcats.ru/pid/26456"    title="Hyundai" target="_blank">
                            <span>Hyundai</span>
                        </a>
                        <a class="car_logo_a brands honda"   href="http://honda.ilcats.ru/pid/26450" title="Honda" target="_blank">
                            <span>Honda</span>
                        </a>
                    </li>
                    <li>
                        <a class="car_logo_a brands subaru" href="http://subaru.ilcats.ru/pid/26457" title="Subaru" target="_blank">
                            <span>Subaru</span>
                        </a>
                        <a class="car_logo_a brands mazda"  href="http://mazda.ilcats.ru/pid/26524"  title="Mazda" target="_blank">
                            <span>Mazda</span>
                        </a>
                    </li>
                    <li>
                        <a class="car_logo_a brands suzuki" href=" http://suzuki.ilcats.ru/pid/26458" title="Suzuki" target="_blank">
                            <span>Suzuki</span>
                        </a>                        
                    </li> 
                </ul>
            </div> 
            <?php if(!$this->session->userdata('user_id')): ?>
                <div class="search1">                    
                    <form method="post" style="text-align: center;" action="<?=site_url('auth/login');?>">
                        <label><?=$this->lang->line('email'); ?> <?=$this->lang->line('or'); ?> <?=$this->lang->line('phone'); ?>:</label>
                                <input type="text" name="email">
                        <label><?=$this->lang->line('pass'); ?>:</label>
                                <input type="password" name="password">
                                <input type="submit" value="<?=$this->lang->line('login'); ?>" name="submit" class="submit">
                                <h3 style="text-align: center;"><a style="font-size: 18px; color: #979797;" href="<?php echo site_url('auth/registration');?>"><?=$this->lang->line('reg'); ?></a></h3>
                                <h3 style="text-align: center;"><a style="font-size: 15px; color: #979797;" href="<?php echo site_url('auth/passRecovery');?>"><?=$this->lang->line('pass_forgot'); ?></a></h3>
                    </form>	
                </div>
            <?php endif;?>
        </div>
	<!-- start content_right -->        
	<div class="content_right"> 
            <!--Menu-->
             <div class="text2-nav menu">              
                <ul>   
                    <li class="menu_item">
                        <a class="cart_li menu_label" href="<?php echo site_url('aboutUs');?>" title="About us">
                            <span class="label_pointer"><?=$this->lang->line('about'); ?></span>
                        </a>
                    </li> 
                    <li class="menu_item">
                        <a class="cart_li menu_label" href="<?php echo site_url('method');?>" title="method">
                            <span class="label_pointer "><?php echo $this->lang->line('method'); ?></span>
                        </a>
                    </li>
                    <li class="menu_item">
                        <a class="cart_li menu_label" href="<?php echo site_url('delivery');?>" title="delivery">
                            <span class="label_pointer "><?php echo $this->lang->line('delivery'); ?></span>
                        </a>
                    </li>
                    <li class="menu_item">
                        <a class="cart_li menu_label" href="<?php echo site_url('contacts');?>" title="delivery">
                            <span class="label_pointer "><?php echo $this->lang->line('contact'); ?></span>
                        </a>
                    </li>
                    <li class="menu_item">
                        <a class="cart_li menu_label" style="padding: 6px 5px;" href="<?php echo site_url('/cart')?>" title="Корзина">
                            <i class="fa fa-shopping-cart cart_icon">                                                           
                            <i class="user_cart">
                                 <?php                                 
                                    if (!empty($this->session->userdata('user_cart')['owndb']) || !empty($this->session->userdata('user_cart')['externaldb'])) {
                                        $cart_count = $this->session->userdata('user_cart');
                                        echo count($cart_count['owndb']) + count($cart_count['externaldb']);
                                    }else{
                                       echo ""; 
                                    }
                                ?>
                            </i>
                                </i>
                            <span class="label_pointer" style="float: right;margin-right: 6px;"><?=$this->lang->line('basket'); ?></span>
                        </a>
                    </li>
                    <li class="menu_item">
                        <?php if($this->session->userdata('rate')):?>
                        <span class="change_1">1$ = <?=$this->session->userdata('rate')?><img src="/public/images/dram.png" /></span>
                        <?php else:?>
                            <span class="change_2"><?=$this->lang->line('rate_txt')?></span>
                        <?php endif;?>
                        <span class="change_3"><?=$this->lang->line('rate_bank')?></span>
                    </li>
                </ul>
             </div>
            <?php if($this->session->userdata('announcement') && $this->session->userdata('announcement') != 'empty'):?>
            <!--Notification bar-->
            <div class="notification_bar">                           
                    <?=$this->session->userdata('announcement')?>               
            </div>
            <?php endif;?>
            <!--Search by part number-->
            <div class="gallery">                           
                <div class="search">
                    <span class="small-title"><?=$this->lang->line('searchbycode')?></span>
                    <form id="part_search" method="get" action="<?php echo site_url('search');?>">
                       <input type="text" name="parts_search" value="" placeholder="<?=$this->lang->line('type_code'); ?>">
                       <input type="submit" value="<?=$this->lang->line('search'); ?>">
                    </form>
                    <h3>
                        <label class="example_code"><?=$this->lang->line('example'); ?>:&nbsp;</label>
                        <label class="example_code example_link">48510-49017</label>&nbsp;
                        <label class="example_code example_link"></label>
                    </h3>
                </div>                
            </div>
            <!--Go to catalog-->
             <div class="gallery2">                           
                <div class="search2">
                    <a href="<?php echo site_url('catalog')?>"><?php echo $this->lang->line('type_model'); ?></a>
                </div>                
            </div>
            
            <div class="image group" style="padding: 10px;margin-top: 5px">
                <?php echo $content_for_layout ?>
            </div>
	</div>
	<div class="clear"></div>
</div>
</div>
</div>
</div>
<div class="footer_bg">
<div class="wrap">
<div class="wrapper">
	<div class="footer" style="text-align:center">
                
		<div class="copy">
			
                        <div class="cardlogo">
                            <img src="/public/img/arca.gif" />
                            <img src="/public/img/visa.jpg" />
                            <img src="/public/img/MasterCard.jpg" />
                            <img src="/public/img/amex.jpg" />
                        </div>
                    <p class="w3-link">YerevanMotors <?=date('Y')?> © <?php echo $this->lang->line('copyright'); ?></p>
		
                </div>
                
                <ul style="overflow:hidden; display:inline-block">
                    
                    <li style="  width: 125px;float:left; padding: 3px 10px;text-align: center;color: white;">
                    	<img style="vertical-align: middle;height: 25px;width: 25px;" src="/public/img/viva.png">
                    	<div style="font-size: 15px;padding-top: 5px;  color: white;">098 79 20 10</div>
                    </li>
                    <li style="  width: 125px;float:left;padding: 3px 10px;text-align: center;color: white;">
                    	<img style="vertical-align: middle;background-color: white;height: 25px;    width: 25px;" src="/public/img/beeline.png">
                    	<div style="font-size: 15px;padding-top: 5px;  color: white;">096 79 20 10</div>
                    </li>
                    <li style="  width: 125px;float:left;padding: 3px 10px;text-align: center;color: white;">
                    	<img style="height: 25px;width: 25px;vertical-align: middle;" src="/public/img/orange.png">
                    	<div style="font-size: 15px;  color: white;padding-top: 5px;">041 79 20 10</div>
                    </li>
        	</ul>
        
        
		<div class="f_nav" style="display:inline-block">
		<ul>
                    <li><a href="http://coderiders.am/" target="_blank"><img title="coderiders.am" alt="coderiders" src="<?=base_url()?>public/img/coderiders-banner.png"></a></li>
		</ul>
		</div>
		<div class="clear"></div>
	</div>
</div>
</div>
</div>

<div class="overlay-wrapper">
    <div class="loading-overlay"></div>
    <div class="overlay-ajax-loader">
        <img class="ajax-loader-big" src="<?=base_url()?>public/img/preloader.gif" />
	</div>
</div>

<div id="chat_holder">
    <div class="main-holder">
        <div class="form-group col-sm-12">
            <label class=""><?=$this->lang->line('name');?> </label>
            <input class="form-control" type="text" name="f_name" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label>Email</label>
            <input type="text" class="form-control" name="email" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('phone');?></label>
            <input class="form-control" type="text" name="phone" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label>VIN <?=$this->lang->line('code');?></label>
            <input class="form-control" type="text" name="vincode" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('maker');?></label>
            <input class="form-control" type="text" name="model" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('year');?></label>
            <input class="form-control" type="text" name="year" size="50" />
            
        </div>
        <div class="form-group col-sm-12">
            <label><?=$this->lang->line('message');?></label>
            <textarea class="form-control" name="message" rows="3"></textarea>
            
        </div>
        <div class="col-sm-12 btn-group-sm" style="text-align: center">
            <input class="btn btn-success sendMess" type="submit" value="<?=$this->lang->line('send');?>" />
            <input class="btn btn-default close-chat" type="submit" value="<?=$this->lang->line('close');?>" />
        </div>
        
    </div>
    <div class="modal-message"></div>
    <div class="chat-button <?=$class?>"></div>
</div>


<script src="<?=base_url()?>public/js/jquery-min.js"></script>

<script>
var loadingOverlay = function(bShow, oElementToOverlay, oParams) {
    var oLoadingOverlayWrapper = $(".overlay-wrapper"), oAjaxLoader = $(".overlay-ajax-loader");
    if (bShow) {
        this.oParams = oParams || {};
        this.bShowLoader = true;
        oLoadingOverlayWrapper.css({top: oElementToOverlay.offset().top,left: oElementToOverlay.offset().left,width: oElementToOverlay.outerWidth(),height: oElementToOverlay.outerHeight()});
        oAjaxLoader.css({top: (oElementToOverlay.height() / 3),left: (oElementToOverlay.width() / 2.7),display: 'block'});
        if (typeof this.oParams.bShowLoader !== 'undefined') {
            this.bShowLoader = this.oParams.bShowLoader;
        }
        if (!this.bShowLoader) {
            oAjaxLoader.css({display: 'none'});
        }
        if (typeof this.oParams.attributes !== 'undefined') {
            $.each(this.oParams.attributes, function(name, value) {
                oLoadingOverlayWrapper.attr(name, value);
            });
        }
        if (typeof this.oParams.onInit === 'function') {
            this.oParams.onInit(oLoadingOverlayWrapper);
        }
        oLoadingOverlayWrapper.fadeIn(175);
        $('.modal').on('hidden', function() {
            oLoadingOverlayWrapper.hide();
        });
    } else {
        oLoadingOverlayWrapper.fadeOut(0);
    }
}
</script>

    <script src="<?=base_url()?>public/js/jquery-min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?=base_url()?>public/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="<?=base_url()?>public/js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="<?=base_url()?>public/js/sb-admin-2.js"></script>
    
    <!--My Admin js-->
    <script src="<?=base_url()?>public/js/init.js"></script>
</script>
</body>

</html>