<style>
    strong{
        font-weight: bold;
    }
</style>    
<div id="container" style="padding:15px">
    <?php if($success):?>
    <h1>
        <div class="alert alert-success">
            <?=$this->lang->line('payed')?>
        </div>
    </h1>
    <?php
        $payed = $order['total_price']*$order['rate'];
        $payed = number_format((double) $payed, 2, '.', '');
    ?>
    <div style="padding:15px 0">
        <ul>
            <li><?=$this->lang->line('order')?>: <strong>#<?=$order['id']?></strong></li>
            <li><?=$this->lang->line('price_usd')?>: <strong><?=$order['total_price']?></strong></li>
            <li><?=$this->lang->line('price_amd')?>: <strong><?=$payed?></strong></li>
            
        </ul>
    </div>    
    
    <?php else:?>
        <h1></h1>
        <div class="alert alert-danger">
            <?=$this->lang->line('notpayederror')?>
        </div>
        
    <?php endif;?>
        
    <a class="btn btn-default" href="<?=base_url('/')?>"><?=$this->lang->line('homepage')?></a>    
</div>
