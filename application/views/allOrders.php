<?php    
        echo "<div class='user_order'>";                                 
            echo "<table class='table table-striped table-bordered table-hover'>"            
                    ."<thead>"
                        ."<tr>"          
                            ."<th>{$this->lang->line('order_id')}</th>"
                            ."<th>{$this->lang->line('payed')}</th>"
                            ."<th>Transaction ID</th>"
                            ."<th>{$this->lang->line('order_items')}</th>"
                            ."<th>Total price($)</th>"
                            ."<th>{$this->lang->line('order_date')}</th>"
                            ."<th>{$this->lang->line('order_status')}</th>"
                            ."<th>Details</th>"
                        ."</tr>"
                    ."</thead>"
                    ."<tbody>";
                foreach($orders as $key => $value){               
                    echo "<tr>";
                    echo "<td>{$value['id']}</td>";
                    echo "<td class='payed_{$value['order_payed']}'></td>";
                    echo "<td>{$value['transaction_id']}</td>";
                    echo "<td>{$value['items_count']}</td>";
                    echo "<td>{$value['total_price']}</td>";
                    echo "<td>{$value['order_date']}</td>";                    
                    echo "<td class='order_status_{$value['order_status']}'>"
                            ."<select id='{$value['id']}' class='order_status'>";
                        foreach (OrderStatus::$order_status as $key => $status) {
                            if($key == $value['order_status'])
                                echo "<option value='{$key}' selected>{$status}</option>";
                            else
                                echo "<option value='{$key}'>{$status}</option>";
                        }                       
                        echo '</select>'
                        .'</td>';
                    echo "<td><a class='btn btn-info btn-xs' href='".site_url('/admin/allOrders/'.$value['id'])."'>Info</a>";
                         if($value['order_payed'] != 1):?>   
                            <a title="delete_order" data-id="<?=$value['id']?>" class="delete_order" href="#"><img class="remove" src="/public/img/remove.png" /></a>

                    <?php endif;
                    echo "</td>";
                    echo "</tr>";                    
                }
            echo "</tbody>"
            ."</table>";                
        echo "</div>";
?>
