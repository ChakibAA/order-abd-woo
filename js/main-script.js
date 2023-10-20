function get_data_from_form() {

    var filledInputs = {};

    jQuery('input, select, textarea').each(function () {
        var input = jQuery(this);
        var name = input.attr('name');
        var type = input.attr('type');
        var value = input.val();

        if (name) {
            if (type === 'checkbox' || type === 'radio') {
                // For checkboxes and radio buttons, check if they are checked
                if (input.is(':checked')) {
                    filledInputs[name] = value;
                }
            } else if (value.trim() !== '') {
                filledInputs[name] = value;
            }
        }
    });

    console.log(filledInputs);
    return filledInputs;
}


function send_data_to_php() {

    var data = get_data_from_form();

    jQuery.ajax({
        url: php_data.ajax_url,
        type: 'POST',
        data: {
            action: 'save_data_order_abd',
            data: data
        },
        success: function (response) {
            console.log(response);
        },
        error: function (error) {
            console.log(error);
        }
    });
}

send_data_to_php()
