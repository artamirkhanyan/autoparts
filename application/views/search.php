<style>
    .weight_question::after{
        display: none;
        position: absolute;
        width: 103px;
        padding: 3px;
        top: 9px;
        left: -109px;
        content: "<?=$this->lang->line('info_weight')?>";    
        background: #F8F8F8;
        border: 1px solid #D0D0D0;
        -webkit-box-shadow: 0 0 8px #D0D0D0;
        color: black;
        z-index: 100;
        font-size: 0.8em;
        font-weight: normal;
        -webkit-transition: color .15s;
        -moz-transition: color .15s;
        -o-tra-webkitnsition: color .15s;
        transition: color .15s;
    }
</style>
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
                        "<select id='valuta_select' style='outline:none; margin-left:5px;'>".
                            "<option value='usd' {$selected_usd}>USD</option>".
                            "<option value='amd' {$selected_amd}>AMD</option>".
                        "</select>".
                    "</th>";
                            
                echo "<th class='search_th'>".$this->lang->line('inventory')."</th>";
                // COMMENTED FOR NOW
                // 
                //  echo "<th class='search_th'>".$this->lang->line('weight')."</th>";
                //
                //------------------
                echo "<th class='search_th'>".$this->lang->line('deliv')."</th>";
                echo "<th class='search_th'>".$this->lang->line('tobasket')."</th>";

            ?>
        </tr>
    </thead>
    <tbody>
        <?php
            
            //Присваиваем массив с полученными деталями переменной для его изменения в дальнейшем
            $search_result = $this->session->userdata('current_search_result');
            //Получаем максимальный вес детали из списка поиска
            $weight_max = $this->session->userdata('current_part_weight');
            
            if(!empty($search_result)){ 
                $hasAnalog = false;
                $arm_flag = true;
                $dubai_flag = true;
                foreach ($search_result as $key => &$value) {
                    
                    //$value['weight'] = $weight_max;
                    //$value['new_waight'] = $weight_max;
                    if($value['from'] == 'armenia' && $arm_flag){
                        echo "<tr><td class='search_th2' colspan='8'>".$this->lang->line('fromArmenia')."</td></tr>";
                        $arm_flag = false;
                    }
                    else if($value['from'] != 'armenia' && $dubai_flag){
                        echo "<tr><td class='search_th2' colspan='8'>".$this->lang->line('fromDubai')."</td></tr>";
                        $dubai_flag = false;
                    }
                    
                    //---------other values---------
                    $value_id      = $value['id'];
                    $from          = $value['from'];
                    $part_category = $value['category'];
                       
                     //-------weight---------
                    $weight = ( $value['weight'] == 0)?'-':number_format( (double)$value['weight'], 3, '.', '');
                    
                    if($weight == '-' && $from != 'armenia'){
                        if(isset($value['analog']) && $value['analog']){
                            $hasAnalog = true;
                            echo "<tr class='hidden' style='background-color: rgb(234, 173, 173);'>"; 
                        }else{
                            echo "<tr style='background-color: rgb(234, 173, 173);'>";  
                        }
                    }
                    else {
                        if(isset($value['analog']) && $value['analog']){
                            echo "<tr class='hidden'>";
                            $hasAnalog = true;
                        }else{
                            echo "<tr>"; 
                        }
                    }
                    
                    //-------makerName/category-----
                    $makerName = '';
                    if(isset($value['makerName'])){
                        $makerName = $value['makerName'];
                    }else{
                        $makerName = $value['category'];
                    }
                     echo "<td class='search_td'>{$makerName}</td>";
                    
                    //-------part_number-------
                    $part_number = $value['part_number'];
                    echo "<td class='search_td'>{$part_number}</td>";
                    
                    //-------description-------
                    $description = $value['description'];
                    echo "<td class='search_td'>{$description}</td>";
                                                            
                    //-------price-------------
                    $rate   = (double)$this->session->userdata('rate');
                    $valuta = $this->session->userdata('currency');
                    
                    if($weight == '-' && $from != 'armenia'){
                        $tmp_price = $value['price'];
                    }else{
                        if($from == 'armenia'){
                            $tmp_price = calculateTotalPriceForArmenia($value['price'], $value['weight'], $koefficients, $rate, $valuta, 'search', false, $value['currency']); 
                        }else{
                            if(isset($value['catalog']) && $value['catalog'] === 'russia'){
                                $koefficients['russian_weight'] = $koefficients['weight_russia'];
                            }else{
                                $koefficients['russian_weight'] = FALSE;
                            }
                            $tmp_price = calculateTotalPrice($value['price'], $value['weight'], $koefficients, $rate, $valuta, 'search', false); 
                        }
                    }                                                                        
                        
                    $tmp_price = number_format((float) $tmp_price, 2, '.', '');
                    ?>
                     <td class='price search_td'><?=$tmp_price?>
                     <?php
                       if($weight == '-' && $from != 'armenia'){
                     
                     	    echo '<div style="font-size:12px">'.$this->lang->line('dubaiPrice').'</div>';
                        }
                     ?>
                     </td>
                     <?php
                    
                    //-------inventory---------
//                    $inventory = $value['inventory'];
                    $inventory = preg_replace("/[^0-9]/", "", $value['inventory']);
                    echo "<td class='search_td'>{$inventory}</td>";
                    
                    //-------weight---------
//                    $weight = ( $value['weight'] == 0)?'-':number_format( (double)$value['weight'], 2, '.', '');
                    // COMMENTED FOR NOW
                    // 
                    // echo "<td class='search_td'>{$weight}</td>";
                    // 
                    //---------------------
                    
                    //-------delivery---------
                    if($from != 'armenia'){
                        $delivery = (int)$value['delivery'] + (int)$koefficients['delivery'];
                    }else{
                        $delivery = "2";
                    }
                    if(in_array($description, Constants::$bigparts)){
                        echo "<td class='search_td'><i class='fa fa-question-circle'></i></i></td>";
                    }else{
                        echo "<td class='search_td'>{$delivery} - ".($delivery+4)."</td>";
                    }                   
                        echo '<td class="my_search_td">';
                            if($weight == '-' && $from != 'armenia'){
                                    echo '<span class="fa fa-question-circle weight_question" style="cursor: pointer;"></span>';
                                }else{
                                    echo '<span class="add_to_cart_btn btn-link fa fa-cart-plus" style="cursor: pointer;"></span>';
                                }                                
                                echo '<div class="set_part_count_wrapper">'
                                    .'<select class="part_count">';
                                        $select = (int)$inventory;
                                        if((int)$inventory > 150){
                                            $select = 150;
                                        }
                                        for($i = 1; $i <= $select; $i++){
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }                                        
                                echo '</select>';
                                if($part_category == "barma"){
                                    echo '<button class="set_part_count btn btn-primary btn-xs" part_id="'.$value_id.'" part_category="'. $part_category .'" type="button">';
                                }else{
                                    echo '<button class="set_part_count btn btn-primary btn-xs" part_id="'.$value_id.'" part_category="'. $part_category .'" type="button">';
                                }                                    
                                echo 'Ok</button>'
                                    .'</div>';                                                            
                            echo '</td>'
                         .'</tr>';
                }
                //После изменения всех value['weight'] на максимальное значение 
                //переписываем в сессии масив поиска запчастей на новый уже
                $this->session->set_userdata('current_search_result', $search_result);
            } 
        ?>
        <?php if(isset($hasAnalog) && $hasAnalog):?>
            <tr>
                <td colspan="8" class="analogopener info text-center" style="cursor: pointer;font-weight: bold"><?=$this->lang->line('showanalogs')?></td>
            </tr>
        <?php endif;?>
    </tbody>    
</table>