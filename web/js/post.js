$(document).ready(function () {

    var form = '[name="add-comment-form"]';

    $(form).submit(function (e) {
        e.preventDefault();

        postForm($(this), function (response) {
            reloadList(response);
            $(form).trigger("reset");
        });

        return false;
    });

});

function postForm($form, callback) {
    var values = {};
    $.each($form.serializeArray(), function (i, field) {
        values[field.name] = field.value;
    });

    $.ajax({
        type: $form.attr('method'),
        url: $form.attr('action'),
        data: values,
        success: function (data) {
            callback(data);
        }
    });

}

function reloadList(comment){
    $(".list-group").append(
        "<div class='list-group-item list-group-item-action flex-column align-items-start'><div class='d-flex w-100 justify-content-between'><h5 class='mb-1'>"+comment['author']+"</h5>"+
        "<small>"+comment['time']+"</small>"+
        "</div><p class='mb-1'>"+comment.content+"</p></div>"
    );
}