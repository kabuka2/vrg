
$(document).ready(function(){
    

    $(document).on('click','.ajax_button',function () {
        $('#modalAuthor').modal('show').find('#modalContent').load($(this).attr('value'));
    });


    $(document).on('click', '.images_block', function (e) { 
        // e.preventDefault();

        let block = $(this);
        block.remove();
        $('#input_photo').show();
        $('#books-hidden_image_status').val('0');
    });


    $(document).on('beforeSubmit', '#Books', function(e) {
        e.preventDefault();
        if ( !$(this).find('button').hasClass('form_button__no_ajax') ) {
            var fd = new FormData(this);
            let action = $(this).attr('action');
            const form = $(this);
            fd.append('img', $('#input_photo').prop('files')[0]);
            $.ajax({
                type: "post",
                url: action,
                data: fd,
                processData: false,
                contentType: false,
                success: function (result) {
                    console.log(result);
                    if (result.message == 'success') {

                        console.log(result.message);
                        $(document).find('#modalAuthor').modal('hide');
                        $.pjax.reload({container:'#table_grid_books'});
                    } else {
                        $(form).trigger('reset');
                        $('#message').html(result.message)
                    }

                },
                error: function (error) {
                    console.log(error);
                    return false;
                }
            });
            return false;
        }
        return false;
    });




});