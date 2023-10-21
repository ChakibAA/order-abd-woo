

document.addEventListener("visibilitychange", function () {
    if (document.hidden) {
        console.log("L'utilisateur est passé à une autre fenêtre ou onglet");
        send_data_to_php()

    } else {
        console.log("L'utilisateur est revenu à cette fenêtre ou onglet");
    }
});

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

    var productInfoArray = {};
    var product_cmt = 1;

    // Use jQuery to select all the <tr> elements with the class "cart_item"
    jQuery('.cart_item').each(function () {
        // Inside each <tr> element, find the elements with class "product-name" and "product-quantity"
        var productName = jQuery(this).find('.product-name').text().trim();
        var productQuantity = jQuery(this).find('.product-quantity').text().trim();

        // Extract the product name and quantity
        productName = productName.replace(productQuantity, '').trim(); // Remove the quantity text from the product name
        productQuantity = productQuantity.replace('×', '').trim(); // Remove the "×" character

        var key = "product_" + product_cmt;
        productInfoArray[key] = productName;

        productInfoArray[key + "_qte"] = productQuantity;

        product_cmt++;
    });
    const combinedData = { ...productInfoArray, ...filledInputs };

    return combinedData
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