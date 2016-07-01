<div class="container">
    <?php if($error):?>
    <div class="col-sm-12 alert alert-danger">
        <?=$error?>
    </div>
    <?php endif;?>
    <div class="row" style="padding-left: 17px;">
        <h2><?=$this->lang->line('orders_search')?></h2>
        <form method="post" action="/orders">
            <div class="orders_search">
                <?php if(!$this->session->userdata('user_id')): ?>
                    <input type="text" name="order_search_phone" class="form-control input-sm" maxlength="64" placeholder="<?=$this->lang->line('orders_phone')?>" />
                <?php endif;?>
                <input type="text" name="order_search_id" class="form-control input-sm" maxlength="64" placeholder="<?=$this->lang->line('orders_id')?>" />
                <input type="submit" name="order_search_submit" class="orders_search_btn" value="<?=$this->lang->line('search'); ?>"/>
            </div>
        </form>
    </div>
</div>
<?php
    if(!$orders){
        return;
    }
    foreach ($orders as $key => $value) {
        
        $order_status = OrderStatus::$order_status[$value['order_status']];
        if($order_status == "NEW"){
            $order_status = $this->lang->line('order_new');
        }
        elseif($order_status == "READ"){
            $order_status = $this->lang->line('order_read');
        }
        elseif($order_status == "SHIPPED"){
            $order_status = $this->lang->line('order_shipped');
        }
        
        echo "<div class='user_order'>";
                if($value['payed'] == 1): ?>
                    <div class="alert alert-success"><?=$this->lang->line('payed')?></div>
               <?php else: ?>
                    <div class="alert alert-danger"><?=$this->lang->line('notpayed')?></div>
                <?php endif;
            echo "<div class='pull-left'>";    
            echo "<p><span class='order_01'>{$this->lang->line('order_id')}:</span> {$value['order_id']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('order_date')}:</span> {$value['order_date']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('order_total_payed')}:</span> {$value['total_price']}$</p>"
                ."<p><span class='order_01'>{$this->lang->line('order_rate')}:</span> {$value['rate']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('order_status')}:</span>{$order_status}</p>";
            echo "</div>";    
            
            
            echo "<div class='pull-left-custom'>"; 
            
            echo "<p><span class='order_01'>{$this->lang->line('name')}:</span> {$value['f_name']} {$value['l_name']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('email')}:</span> {$value['email']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('phone')}:</span> {$value['phone']}</p>"
                ."<p><span class='order_01'>{$this->lang->line('address')}:</span> {$value['address']}</p>";
            
            echo "</div>";
            
            echo "<p style='clear:both'><span class='order_01'>{$this->lang->line('order_items')} </span></p>";                        
            echo "<table class='table table-striped table-bordered table-hover'>"            
                    ."<thead>"
                        ."<tr>"          
                            ."<th>{$this->lang->line('code')}</th>"
                            ."<th>{$this->lang->line('desc')}</th>"
                            ."<th>{$this->lang->line('maker')}</th>"
                            ."<th>{$this->lang->line('delivery')}</th>"
                            ."<th>{$this->lang->line('price')}($)</th>"
                            ."<th>{$this->lang->line('quan')}</th>"
                        ."</tr>"
                    ."</thead>"
                    ."<tbody>";
                foreach($value['items'] as $key1 => $value1){               
                    echo "<tr>";
                    echo "<td>{$value1['part_number']}</td>";
                    echo "<td>{$value1['description']}</td>";
                    echo "<td>{$value1['part_maker']}</td>";
                    echo "<td>{$value1['delivery']}</td>";
                    echo "<td>{$value1['price']}</td>";
                    echo "<td>{$value1['count']}</td>";
                    echo "</tr>";                    
                }
            echo "</tbody>"
            ."</table>";                
        echo "</div>";
    }