<?php
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
        }?>
        
        <div class='user_order'>
               <?php if($value['payed'] == 1): ?>
                    <div class="alert alert-success"><?=$this->lang->line('payed')?></div>
               <?php else: ?>
                    <div class="alert alert-danger">
                        <span><?=$this->lang->line('notpayed')?></span>
                        <form class=" pull-right" method="post" action="">
                            <input type="submit" name="markAsPayed" class="btn btn-success" value="Կանխիկ վճարում">
                        </form> 
                    </div>
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
                            ."<th>{$this->lang->line('order_from')}</th>"
                            ."<th>{$this->lang->line('price')}($)</th>"
                            ."<th>{$this->lang->line('discount')}(%)</th>"
                            ."<th>{$this->lang->line('delivery')} (օր)</th>"
                            ."<th>{$this->lang->line('order_orig_price')}($)</th>"
                            ."<th>{$this->lang->line('quan')}</th>"
                        ."</tr>"
                    ."</thead>"
                    ."<tbody>";
                foreach($value['items'] as $key1 => $value1){               
                    echo "<tr>";
                    echo "<td>{$value1['part_number']}</td>";
                    echo "<td>{$value1['description']}</td>";
                    echo "<td>{$value1['part_maker']}</td>";
                    echo "<td>{$value1['from']}</td>";
                    echo "<td>{$value1['price']}</td>";
                    echo "<td>{$value1['discount']}</td>";
                    echo "<td>{$value1['delivery']}</td>";
                    echo "<td>{$value1['first_price']}</td>";
                    echo "<td>{$value1['count']}</td>";
                    echo "</tr>";                    
                }?>
            </tbody>
           </table>               
        </div>
    <?php } ?>
