$(document).ready(function() {

    $(document.body).on('click', '.image_remove', function () {
        removeImage($(this));
        return false;
    });

    $(document.body).on('click', '.add-item-link', function () {
        $('.add-item-link').addClass('hide');
        $('.add-item-form').removeClass('hide');
    });

    $(document.body).on('click', '.update_qty' ,function(){
        updateOrderItemQty($(this));
    });

    $(document.body).on('change', '#order-shipping_method' ,function(){
        if($(this).children("option:selected").val() == 'self'){
            $('.shipping_method_field').each(function(){
                $(this).hide();
            });
        } else if($(this).children("option:selected").val() == 'rcr'){
            $('.shipping_method_field').each(function(){
                $(this).hide();
            });
            $('.method_rcr').show();
        } else if($(this).children("option:selected").val() == 'rp'){
            $('.shipping_method_field').each(function(){
                $(this).hide();
            });
            $('.method_rp').show();
        } else if($(this).children("option:selected").val() == 'tk'){
            $('.shipping_method_field').each(function(){
                $(this).hide();
            });
            $('.method_tk').show();
            $('.city_field').show();
        }
    });
});

function removeImage(e) {
    $.ajax({
        method: 'get',
        url: e.attr('href'),
        dataType: 'json',
    }).done(function( data ) {
        if(data) {
            e.parent('.product-image').remove();
        }
    });
}

function updateOrderItemQty(e) {
    var qty = e.parent().children('input.cartitem_qty_value').val();
    var id = e.parent().children('input.cartitem_id').val();
    if(!e.hasClass('disable') && qty > 0){
        e.addClass('disable').prop('readonly', true);
        window.location.replace('/order/update_order_item_qty?id=' + id + '&qty=' + qty);
    }

}