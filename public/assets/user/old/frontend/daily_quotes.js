$(document).on('click','.quote_category',function () {
	var category_id = $(this).data('id');
    $('.quote_category').removeClass('active');
    $(this).addClass('active');
    $('.allQuotes').remove();
	$.ajax({
        type : 'POST',
        url : 'getQuotes',
        data : { category_id : category_id,},
        // dataType:'json',
        success:function(response){
        	$(response).insertAfter('.category_list');
            model();
        }
    });
})