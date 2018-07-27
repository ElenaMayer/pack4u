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