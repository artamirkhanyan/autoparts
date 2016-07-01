$(document).ready( function() {
    $('.btn-file :file').on('fileselect', function(event, numFiles, label) {
        
        var input = $(this).parents('.input-group').find(':text'),
            log = numFiles > 1 ? numFiles + ' files selected' : label;
        
        if( input.length ) {
            input.val(log);
        } else {
            if( log ) alert(log);
        }
        
    });
    
    //Upload csv file dropdown action
    $('.upload_csv_from').on('click', function(){
        var shop_name = $(this).data('from');
        $('.upload_csv_from_header').text(shop_name);
        $('#hidden_shop_name').val(shop_name);
    });
    //Upload csv file currency choosing
    $('.upload_csv_currency').on('click', function(){
        var currency = $(this).data('currency');
        $('.upload_csv_currency_header').text(currency);
        $('#hidden_currency').val(currency);
    });
    
    $('.shop_delete').on('click', function(){
        var deletedRow = $(this);
        var shopId     = $(this).attr('id');
        var shopName   = $(this).attr('shop_name');
        
        $.ajax({
            url: '/admin/deleteShop',
            type: 'post',
            data: {
                'shopId': shopId,
                'shopName': shopName
            },
            success: function(response){
                if(response){
                    deletedRow.parent().fadeOut(300);
                }
                console.log(response);
            }
        });
    });
    
    //Handler on order status change
    $('.order_status').on('change', function(){
        var $row = $(this).parent();
        var $order_status   = $(this).val();
        var $order_id       = $(this).attr('id');
        $.ajax({
            url: '/admin/changeOrderStatus',
            type: 'post',
            data: {
                'order_id'     : $order_id,
                'order_status' : $order_status
            },
            success: function(response){
                if(response){
                    $row.removeClass();
                    $row.addClass("order_status_"+ $order_status);
                }
            }
        });
    });
    
    $('.delete_order').click(function(e){
        e.preventDefault();
        var $row = $(this).parent().parent();
        var id = $(this).attr('data-id');
        var r = confirm("Are you sure?");
        if (r == true) {
            $.ajax({
                url: '/admin/deleteOrder/'+id,
                type: 'get',
                success: function(response){
                    if(response){
                        $row.remove();
                    }
                }
            });
            
        }
    });
    
    $('select#active_user').change(function(){
        var value = $(this).val();
        var id = $(this).data('id');
        loadingOverlay(true, $(this).parent().parent());
        $.ajax({
            url: '/admin/activateUser/'+id,
            type: 'post',
            data: {
                active: value.toString()
            },
            success: function(response){
                loadingOverlay(false, $(this).parent().parent());
            }
        });
       
    });
    
});

$(document).on('change', '.btn-file :file', function() {
  var input = $(this),
      numFiles = input.get(0).files ? input.get(0).files.length : 1,
      label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
  input.trigger('fileselect', [numFiles, label]);
});

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