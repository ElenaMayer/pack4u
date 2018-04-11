$(document).ready(function() {

    $(document.body).on('click', '.image_remove', function () {
        removeImage($(this));
    });
});

function removeImage(e) {
    $.ajax({
        method: 'get',
        url: '/image/delete',
        dataType: 'json',
        data: {
            id: e.attr('id'),
        },
    }).done(function( data ) {
        if(data) {
            e.parent('.product-image').remove();
        }
    });
}