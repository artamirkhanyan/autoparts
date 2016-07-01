<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
   /**
    * price + weight*13) + (price + weight*13)*10%
    * price = $price;
    * price*10% = $price2
    * weight*13 = $weight2
    * (price + price*10% + weight*13) = $price_block
    * @param int $price
    * @param assoc array $array_of_koefficients
    */
    function calculateTotalPrice($price, $weight, $koefficients, $rate, $valuta, $place, $user){
        if(isset($koefficients['russian_weight']) && $koefficients['russian_weight']){
            $weight_koef = $koefficients['weight_russia'];
            if($koefficients['heavyRussia']){
                $weight_koef = heavyDiscount($weight, $weight_koef);
            }
            
        }else{
            $weight_koef = $koefficients['weight'];
            if($koefficients['heavyBarma']){
                $weight_koef = heavyDiscount($weight, $weight_koef);
            }
        }
        
        $weight2 = ((double)$weight * (double)$weight_koef);  
        $price_block = $price + $weight2;  
         
        $total = $price_block + ($price_block * (double)$koefficients['income'])/100;  
        $total = number_format((float) $total, 2, '.', '');
        
       if($place == 'cart' && $user){
            // Make discount for parts from Barma
            $total -= ((double)$total * (double)$koefficients['barma_discount'])/100;       
       }  
       
        if($valuta == 'amd'){
           $total *= $rate;           
        }  
        
        return $total;
    }
    
    function heavyDiscount($weight, $weight_koef){
        $weightLimit = 5;
        $minus = 1;
        if($weight >= $weightLimit && $weight_koef >= $minus){
            $weight_koef = $weight_koef - $minus;
        }
        return $weight_koef;
    }
    
     /**
     * formula = (price) + (price)*10%
     * price = $price;
     * @param int $price
     * @param assoc array $array_of_koefficients
     */
    function calculateTotalPriceForArmenia($price, $weight, $koefficients, $rate, $valuta, $place, $user, $currency=false){ 
        
        $total = $price + ($price * (double)$koefficients['income_arm'])/100;
        $total = number_format((float) $total, 2, '.', '');
        
        if($place == 'cart' && $user){
            // Make discount for parts from Armenia
            $total -= ((double)$total * (double)$koefficients['arm_discount']) /100;
        } 
        
        $total = convertPrice($total, $currency, $rate, $valuta);
//        if($valuta == 'amd'){
//            if($currency){
//                
//            }else{
//                $total *= $rate;
//            }
//            
//        }
        return $total;
    }
    
    function getNotification(){
        //TODO: get notification from DB
        return False;
    }
    
    function convertPrice($amount, $currentCurrency, $rate, $val){
        
        $val = $val ? $val : 'usd';
        if($currentCurrency != $val){
            $exchanged = $amount;
            if($val == 'usd' && $currentCurrency == 'amd'){
                $exchanged =  $amount/$rate;
            }elseif($val == 'amd' && $currentCurrency == 'usd'){
                $exchanged =  $amount*$rate;
            }
            return $exchanged;
        }
        return $amount;
    }
    
    function sendSMS($number, $message){
        $date = date('Y-m-d H:m:s');
        $messId = date('ymdHms');
        
        $sData = '<?xml version="1.0" encoding="UTF-8"?>
        <bulk-request login="yerevanmotors.com" password="yerevanmotors.com" ref-id="'.$date.'" delivery-notification-requested="true" version="1.0">
         <message id="'.$messId.'" msisdn="'.$number.'" service-number="YerevanMot" validity-period="3" priority="1">
          <content type="text/plain">'.$message.'</content>
         </message>
        </bulk-request>';
        
        $url = 'http://31.47.195.66:80/broker/';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml; charset=UTF-8'));
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $sData );
        $result = curl_exec($ch);
        curl_close($ch);
        
        return $result;
    }

    
    /**
     * ENUM for Orders status
     * NEW
     * READ
     * SHIPPED
     */
    class OrderStatus {
        
        /** @const */
        public static $order_status = array(
            '1' => "NEW",
            '2' => "READ",
            '3' => "SHIPPED"
        );
    }
    
    class Constants{
        public static $bigparts = array(
            "HOOD",
            "hood",
            "trunk",
            "TRUNK",
            "bonet",
            "BONET",
            "DOOR",
            "door",
            'BACK DOOR'
        );
    }