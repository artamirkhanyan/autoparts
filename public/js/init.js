$(document).ready(function(){
    
    $('html').click(function() {
        $(".set_part_count_wrapper").fadeOut(300);
    });
    
    $(".example_link").on('click', function(){
        $("input[name=parts_search]").val($(this).text());
    });
    
    $("#part_search").on('submit', function(event){
        var search_text = $.trim($("input[name=parts_search]").val());
        if(search_text.length == 0){
            return false;
        }        
    });
    
    $(".weight_question").on('click', function(){
        $(".chat-button").trigger('click');
    });
    
    $("#valuta_select").on('change', function(){
        var $currency = $(this).val();
        if($currency == 'usd'){
            window.location.href = '/currency/usd';
        }else{
            window.location.href = '/currency/amd';
        }
    });
    
    $(".add_to_cart_btn").on('click', function(){
        if($(this).next().is(":visible")){
             $(".set_part_count_wrapper").fadeOut(300);
        }
        else{
            $(".set_part_count_wrapper").fadeOut(300);
            $(this).next().css({"top":$(this).parent().innerHeight()+"px"});
            $(this).next().fadeToggle(300);
        }        
        event.preventDefault();
        event.stopPropagation();
    });
    
    $(".set_part_count_wrapper").on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
    });
    $(".my_search_td").on('click', function(event){
        event.preventDefault();
        event.stopPropagation();
    });
    
    $(".set_part_count").on('click', function(){
        var current_obj   = $(this);
        var part_id       = $(this).attr('part_id');
        var part_category = $(this).attr('part_category');
        var part_count    = $(this).prev().val();
      
        loadingOverlay(true, current_obj.parent().parent().parent());
        $.ajax({
            url: "/addToCart",
            type: 'post',
            data: {
                'part_id' : part_id +'_'+ part_category,
                'part_count': part_count
            },
            success: function(response){
                console.log(response);
                if( parseInt(response) == response){
                    if($('.user_cart').text() == ""){
                        $('.user_cart').html('&nbsp').text("1");
                    }else{
                        $('.user_cart').text(+parseInt(response));
                    }
                    loadingOverlay(false, current_obj.parent().parent().parent());
                }else{
                    console.log("Error during add to cart");
                }
            }
        });
        $(this).parent().fadeOut(300);
    });
    
    //Handler for select in user cart
    var prev_val;
    var new_val;
    $('.part_count_cart').on('focus', function(){
        
        prev_val = $(this).val();       
        
    }).on('change', function(){
        this_obj = $(this);
        $(this).blur();
        new_val = $(this).val();        
        
        new_val  = parseInt(new_val);  
        prev_val = parseInt(prev_val); 
        
        var part_id = $(this).parent().next().next().find('.delete_from_cart').attr('part_id');
        
        $(this).attr('disabled', true);
        $.ajax({
            url:'/editCartItemCount',
            type:'post',
            data: {
                'part_id' : part_id,
                'part_count' : new_val
            },
            success: function(response){
                if(response){
                    this_obj.attr('disabled', false);
                    
                    var part_price   = parseFloat(this_obj.attr('part_price'));
                    var total_price  = parseFloat($('#total_price').text());

                    var delta = Math.abs(new_val - prev_val);

                    var line_total = Math.round(new_val * part_price * 100) / 100;
                    this_obj.parent().next().text(line_total);

                    if(new_val > prev_val)
                        total_price += delta * part_price
                    else
                        total_price -= delta * part_price

                    //Set total_price format like a 00.00
                    total_price = Math.round(total_price * 100) / 100;

                    $('#total_price').text(total_price);       
                }
            }
        });              
    });
    
    //Clean all items from the cart
    $('#reset_cart').on('click', function(){
        $.ajax({
            url : '/clearCart',
            type: 'post',
            success: function(response){
                if(response){
                    $('.user_cart').text('');
                    $('table tbody tr').fadeOut(0);
                    $('#total_price').text('0');                  
                    $('.cart_ctr').fadeOut(0);
                    $('#discount_wrapper').css({'display':'none'});
                }
            }
        });
    });
    
    //Delete item from the cart
    $('.delete_from_cart').on('click', function(){
        var this_row = $(this);
        var item_id  = $(this).attr('part_id');
        loadingOverlay(true, this_row.parent().parent().parent());
        $.ajax({
            url: '/deleteFromCart',
            type: 'post',
            data: {
                'item_id': item_id,
            },
            success: function(response){
                console.log(response);
                this_row.parent().parent().fadeOut(300);
                this_row.parent().parent().remove();
                var cart_volume = parseInt($('.user_cart').text());
                cart_volume -= 1;
                cart_volume = (cart_volume == 0)?"":cart_volume;
                if(cart_volume == "")
                    $('.cart_ctr').fadeOut(0);
                $('.user_cart').text(cart_volume);
                checkTotal();
                loadingOverlay(false, this_row.parent().parent().parent());
            }
        });
    });
    
    $('#buy_cart').on('click', function(){
        $.ajax({
            url: '/buyCart',
            type: 'post',
            success: function(response){
                if(response){
                    $('.user_cart').text('');
                    $('table tbody tr').fadeOut(0);
                    $('#total_price').text('0');                  
                    $('.cart_ctr').fadeOut(0);
                    $('#discount_wrapper').css({'display':'none'});
                }
            }
        });
    });
    
    $('.link2search').click(function(e){
        e.preventDefault();
        $('#part_search > input[type=text]').focus();
    });
    
    $('.link2chat').click(function(e){
        e.preventDefault();
        $('.chat-button').trigger('click');
    });
    
    $('.chat-button, .close-chat').click(function(){
       $('#chat_holder > .main-holder').toggleClass('opened-chat'); 
    });
    $('.main-holder .sendMess').click(function(){
        loadingOverlay(true, $('.main-holder'));
        
        var name = $.trim($('.main-holder input[name=f_name]').val());
        var email = $.trim($('.main-holder input[name=email]').val());
        var phone = $.trim($('.main-holder input[name=phone]').val());
        var vincode = $.trim($('.main-holder input[name=vincode]').val());
        var model = $.trim($('.main-holder input[name=model]').val());
        var year = $.trim($('.main-holder input[name=year]').val());
        var message = $.trim($('.main-holder textarea[name=message]').val());
        
        
        $.ajax({
            url: '/sendRequest',
            type: 'post',
            dataType: 'json',
            data: {
                name:name,
                email:email,
                phone:phone,
                vincode:vincode,
                model:model,
                year:year,
                message:message
            },
            success:function(response){
                console.log(response);
                loadingOverlay(false, $('.main-holder'));
                
                $('.modal-message').html('<div style="margin-top:50%;text-align: center;font-size: 20px;">'+response.mess+'</div>').fadeIn(100);
                if(!response.error){
                    $('.main-holder input[type=text], .main-holder textarea').val('');
                    
                    setTimeout(function(){ 
                        $('.modal-message').text('').fadeOut(100, function(){
                            $('.close-chat').trigger('click');
                        });
                        
                    }, 2000);
                    
                }else{
                    setTimeout(function(){ 
                        $('.modal-message').text('').fadeOut(100);
                    }, 2000);
                }
                
                
            }
        });
        
    });
    
    $('span.dropdown-make').click(function(){
        $(this).next('div.dropdown-custom').slideToggle();
    });
    
    $('td.analogopener').click(function(){
        $('table tr.hidden').first().before('<tr><td colspan="8" class="text-center">* * *</td></tr>');
        $('table tr.hidden').removeClass('hidden');
        $(this).remove();
    });
    
});

/*var aa = {
			     
    method: 'feed',
    link: window.location.href,
    picture: data.img,
    name: 'Japanese car auction.',
    description: 'Coming up for Auction on the '+data.auction_date+', this '+data.year+' '+data.make+' '+data.model+' '+data.sub_model+', '+data.cc_rating+', the auction grade is '+data.condition+', for further information please contact us before the auction date on info@salnet.jp, you can see the vehicle on our Japanese vehicle auction website at '+data.page_url+' to register on our auction site please click here http://auction.salnet.jp/register',
}*/

function checkTotal(){
    $total = 0;
    $('.part_count_cart').each(function(index, tableElement){
        $price = $(this).attr('part_price');
        $count = $(this).val();
        $total += parseFloat($price) * parseFloat($count);
    });
    $total = Math.round($total * 100) / 100;

    $('#total_price').text($total);
}

