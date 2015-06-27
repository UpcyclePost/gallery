$(function() {
    $('#post-details-form').validate({
        ignore: '',
        highlight: function(el) {
            $(el).parent('div').addClass('has-error');
        },
        unhighlight: function(el) {
            $(el).parent('div').removeClass('has-error');
        },
        errorClass: "help-block"
    });

    var theTags = [];
    if (typeof _tags !== 'undefined')
    {
        theTags = _tags.split(',');
    }

    $('.tm-input').tagsManager({
        prefilled: theTags,
        tagsContainer: '.tags-container',
        tagCloseIcon: '<i class="fa fa-fw"></i>',
        preventSubmitOnEnter: true
    });

    $("#primary ul li").click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        $('#secondary-category-list').empty();
        $('#category-selected').val($(this).attr('category'));
        if (Object.keys(s[$(this).attr('category')].children).length > 0) {
            $.each(s[$(this).attr('category')].children, function(index, value) {
                $('#secondary-category-list').append('<li category="' + index + '">' + value.title + '</li>');
            });
            $('#secondary').addClass('drop').find('li').removeClass('active');
            $('#drop-down').addClass('extend');
        } else {
            $('#secondary').removeClass('drop').find('li').removeClass('active');
            $('#drop-down').removeClass('extend');
            $('#cat-drop').removeClass('drop');
            $(document).unbind("click");
        }
        $('#category').html($(this).html());
    });

    $('#secondary').on('click', 'ul li', function(event) {
        $(this).addClass('active').siblings().removeClass('active');
        $("#cat-drop").removeClass("drop");
        $('#category').html($('#primary li.active').html() + ' | ' + $(this).html());
        event.stopPropagation();
    });

    $("#cat-drop").click(function (event) {
        if ($(event.target).closest('ul')[0] === $('#cat-drop')[0]) {
            if (!$('#drop-down').is(':visible')) {
                $('#cat-drop').addClass('drop');

                $(document).click(function(event) {
                    if (!$(event.target).closest('#cat-drop').length && $('#cat-drop').is(':visible')) {
                        $('#cat-drop').removeClass('drop');
                        $(document).unbind("click");
                    }
                });
            } else {
                $('#cat-drop').removeClass('drop');
                $(document).unbind("click");
            }
        }
    });
});