<style>
    strong{
        font-weight: bold;
    }
</style>    
<div id="container" style="padding:15px">
    <h1></h1>
    <?php if(!$error):?>
    <div>
        <ul>
            <li><?=$this->lang->line('order')?>: <strong>#<?=$order_id?></strong></li>
            <li><?=$this->lang->line('price_usd')?>: <strong><?=$orderDetails[$order_id]['total_price']?></strong></li>
            <li><?=$this->lang->line('price_amd')?>: <strong><?=$price2pay?></strong></li>
            
        </ul>
    </div>    
    
    <p style="padding:15px; line-height: 30px;">
        <form  ACTION='https://epay.arca.am/svpg/BPC/AcceptPayment.jsp' METHOD='POST'>
                <INPUT TYPE='Hidden' NAME='title' value='YerevanMotors' />
                <INPUT TYPE='Hidden' NAME='MDORDER' VALUE="<?php echo trim($result);?>" />
<!--                <INPUT TYPE='Hidden' NAME='path' VALUE="http://test.yerevanmotors.am/public/css" />-->
                <INPUT TYPE='Submit' style="background-color: #46BE46;color:white" class="btn btn-default" NAME='Submit' VALUE="<?=$this->lang->line('pay')?>: <?=$price2pay?> <?=$this->lang->line('amd')?>">
                <a style="margin-left: 15px;" href="<?=base_url()?>" class="btn btn-default"><?=$this->lang->line('paylater')?></a>
        </form>
        <hr />
    </p>
    <?php else:?>
        <div class="alert alert-danger"><?=$error?></div>
    <?php endif;?>
    
        <div class="cardlogo">
            <img src="/public/img/arca.gif" />
            <img src="/public/img/visa.jpg" />
            <img src="/public/img/MasterCard.jpg" />
            <img src="/public/img/amex.jpg" />
            <span>Վճարումներն ընդունվում են ՀՀ դրամով և միայն ՀՀ բանկերի կողմից տրամադրված քարտերով:</span>
        </div>  
</div>

