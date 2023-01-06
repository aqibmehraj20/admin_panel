const $post_category = $("#post_category");
const $token = $("#post_token");

$post_category.change( function (){

    const $form = $(this).closest('form');

    const data = {};

    data[$token.attr('name')] = $token.val()
    data[$post_category.attr('name')] = $post_category.val()

    $.post($form.attr('action'), data).then(function(response){
        $("#post_sub_category").replaceWith(
            $(response).find('#post_sub_category')
        )
    })

});