jQuery(function ($) {

    $(document.body).on('teg-select', function (node, selector) {

        var target = node.target;

        $(target).select2();
    });

    $('.teg-select').trigger('teg-select');


});
