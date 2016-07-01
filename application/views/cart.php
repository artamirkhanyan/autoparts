<table id="container" class="table table-striped table-bordered table-hover parts_table">
    <thead>
        <tr>
            <?php 
                echo "<th class='search_th'>".$this->lang->line('maker')."</th>";
                echo "<th class='search_th'>".$this->lang->line('code')."</th>";
                echo "<th class='search_th'>".$this->lang->line('desc')."</th>";

                if($this->session->userdata('currency')){
                    $valuta = $this->session->userdata('currency');
                }else{
                    $valuta = 'usd';
                }
                $selected_usd = "";
                $selected_amd = "";
                if( !$this->session->userdata('currency') || $this->session->userdata('currency') == 'usd'){
                    $selected_usd = 'selected';
                }else{
                    if($this->session->userdata('currency') == 'amd'){
                        $selected_amd = 'selected';
                    }
                }
                echo "<th class='search_th'>". $this->lang->line('price') ."". 
                        "<select id='valuta_select' style='outline:none; margin-left: 5px;'>".
                            "<option value='usd' {$selected_usd}>USD</option>".
                            "<option value='amd' {$selected_amd}>AMD</option>".
                        "</select>".
                    "</th>";
                            
                echo "<th class='search_th'>".$this->lang->line('inventory')."</th>";
                // COMMENTED FOR NOW
                // 
                // echo "<th class='search_th'>".$this->lang->line('weight')."</th>";
                //
                //------------------
                echo "<th class='search_th'>".$this->lang->line('deliv')."</th>";
                echo "<th class='search_th'>".$this->lang->line('quan')."</th>";
                echo "<th class='search_th'>".$this->lang->line('price')."</th>";
                echo "<th class='search_th'>".$this->lang->line('delete')."</th>";
            ?>
        </tr>
    </thead>
    <tbody>
        <tbody>
        <?php
            $total = 0;
            if(!empty($search_result)){                
                foreach ($search_result as $key => $value) {
                    if(!is_array($value))
                        continue;
                    foreach ($value as $key1 => $value1) { 
                        if($value1['category'] == 'barma'){
                            $part_id = $value1['id'];
                            $current_part_count = $value1['part_count'];                            
                        }else{
                            $part_id = $value1['id'];
                            $current_part_count = $value1['part_count'];
                        } 
                        
                        //---------other values---------
                        $from          = $value1['from'];
                        $part_category = $value1['category'];
                        
						if($part_category == 'barma'){
							echo "<tr part_id='".$key1."'>";
						}else{
							echo "<tr part_id='".$part_id."'>";
						}
                                                                        
                        
                        //-------makerName/category-----
                        $makerName = '';
                        if(isset($value1['makerName'])){
                            $makerName = $value1['makerName'];
                        }else{
                            $makerName = $value1['category'];
                        }
                        echo "<td class='search_td'>{$makerName}</td>";
                        
                         //-------part_number-------
                        $part_number = $value1['part_number'];
                        echo "<td class='search_td'>{$part_number}</td>";
                    
                        //-------description-------
                        $description = $value1['description'];
                        echo "<td class='search_td'>{$description}</td>";
                        
                        //-------price-------------                                                
                        $tmp_price = 0;
                        $rate   = (double)$this->session->userdata('rate');
                        $valuta = $this->session->userdata('currency');
                        $tmp_price_without_discount = 0;
                        
                        if($from == 'armenia'){
                            $tmp_price_without_discount = calculateTotalPriceForArmenia($value1['price'], $value1['weight'], $koefficients, $rate, $valuta, false, false, $value1['currency']); 
                            $tmp_price =                  calculateTotalPriceForArmenia($value1['price'], $value1['weight'], $koefficients, $rate, $valuta, 'cart', $this->session->userdata('user_id'), $value1['currency']); 
                        }else{
                            
                            if($value1['catalog'] === 'russia'){
                                $koefficients['russian_weight'] = $koefficients['weight_russia'];
                            }else{
                                $koefficients['russian_weight'] = FALSE;
                            }
                            
                            $tmp_price_without_discount = calculateTotalPrice($value1['price'], $value1['weight'], $koefficients, $rate, $valuta, false, false); 
                            $tmp_price =                  calculateTotalPrice($value1['price'], $value1['weight'], $koefficients, $rate, $valuta, 'cart', $this->session->userdata('user_id')); 
                        }
                        $tmp_price_without_discount = number_format((float) $tmp_price_without_discount, 2, '.', '');
                        $tmp_price = number_format((float) $tmp_price, 2, '.', '');
                        if($tmp_price != $tmp_price_without_discount && $tmp_price_without_discount != 0){?>
                             
                           <td class='price search_td'>
                            <?=$tmp_price_without_discount?> - 
                                <i style='color:red;'><?=($from == 'armenia') ?$koefficients['arm_discount'] :$koefficients['barma_discount']?>%</i>  = 
                                <span style='font-weight: bold;'><?=$tmp_price?></span></td>
                        <?php        
                        }else{
                            echo "<td class='price search_td'>{$tmp_price}</td>";
                        }    
                        
                        $tmp_total = $tmp_price * (int)$current_part_count;
                        $total += $tmp_total;   
                        
                        //-------inventory---------
                        $inventory = $value1['inventory'];
                        $inventory = preg_replace("/[^0-9]/", "", $value1['inventory']);
                        echo "<td class='search_td'>{$inventory}</td>";

                        //-------weight---------
                        $weight = ($value1['weight'] == 0)?'-':number_format((float) $value1['weight'], 3, '.', '');
                        // COMMENTED FOR NOW
                        // 
                        // echo "<td class='weight search_td'>{$weight}</td>";
                        //
                        //------------------

                        //-------delivery---------
                        if($from != 'armenia'){
                            $delivery = (int)$value1['delivery'] + (int)$koefficients['delivery'];
                        }else{
                            $delivery = "2";
                        }
                        
                        if(in_array($description, Constants::$bigparts)){
                            echo "<td class='search_td'><i class='fa fa-question-circle'></i></i></td>";
                        }else{
                            echo "<td class='search_td'>{$delivery} - ".($delivery+4)."</td>";
                        }   
                        //------Quantity------
                        echo '<td>'
                                .'<select class="part_count_cart" part_price="'. $tmp_price .'">';
                                $select = (int)$inventory;
                                if((int)$inventory > 150){
                                    $select = 150;
                                }
                                for($i = 1; $i <= $select; $i++){
                                    if($i == $current_part_count)
                                        echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                    else
                                         echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            echo '</select>'
                        .'</td>';
                            
                        //------Line total------
                        $line_total = $tmp_price * $current_part_count;
                        echo "<td class='search_td'>{$line_total}</td>";

                        //Delete from cart
                        echo '<td class="search_td">';
                            if($part_category == 'barma'){
                                echo '<span class="delete_from_cart" style="cursor: pointer;" part_id="'.$key1.'"><i class="fa fa-times"></i></span>';
                            }else{
                                echo '<span class="delete_from_cart " style="cursor: pointer;" part_id="'.$part_id.'"><i class="fa fa-times"></i></span>';
                            }                                    
                        echo '</td>';
                    echo '</tr>';
                    }
                }
            }           
            $total = number_format((double) $total, 2, '.', '');
        ?>
    </tbody>
    </tbody>
</table>
<div class="group" style="overflow: hidden">
<div class="col-md-4" style="  line-height: 34px;">
    <?=$this->lang->line('total'); ?>(total): 
    <span id="total_price">
        <?php if(isset($total)){ 
            echo $total;
        }?>
    </span> 
    <span>
        <?php
            if(!$this->session->userdata('currency') || $this->session->userdata('currency') == 'usd')
                echo ' $';
            elseif($this->session->userdata('currency') == 'amd')
                echo ' '.$this->lang->line('amd');
        ?>
    </span>
</div>  
<?php if(!empty($this->session->userdata('user_cart')['owndb']) || !empty($this->session->userdata('user_cart')['externaldb'])):?>
<!--</h3>-->

    <div class="col-md-3">
        <a class="cart_ctr btn btn-primary" href="<?php echo base_url();?>buyCart"><?=$this->lang->line('make_order'); ?></a>
    </div>
    <div class="col-md-3 pull-right">
        <input class="cart_ctr btn btn-default pull-right" type="button" id="reset_cart" value="<?=$this->lang->line('clearbasket'); ?>"/>
    </div>
    
   

<?php endif;?>
</div> 