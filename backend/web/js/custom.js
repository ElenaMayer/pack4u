$(document).ready(function() {

    $(document.body).on('click', '.image_remove', function () {
        removeImage($(this));
        return false;
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