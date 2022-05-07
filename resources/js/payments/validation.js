let formRules = () =>{
    return {
        rules: {
            user_id: {
                required: true,
            },
            training_package_id: {
                required: true,
            },
            gym_id:{
                required: true,
            }
        },
    }
};

$(function() {
    initDomSelectors(`#payment-form .selectors`);
    $("#payment-form").validate({
        rules: {
            user_id: {
                required: true,
            },
            training_package_id: {
                required: true,
            },
            gym_id:{
                required: true,
            }
        },
        ...validationProperties,

        submitHandler: function (form) {
            payment().done(()=>{
                $(`#payment-form select`).each(function(i, obj) {
                    let jElem = $(`#${obj.id}`);
                    jElem.val('').trigger('change');
                });
            });
        },
    });
    // var $form = $(".require-validation");
    // $('form.require-validation').bind('submit', function(e) {
    //     var $form = $(".require-validation"),
    //         inputSelector = ['input[type=email]', 'input[type=password]',
    //             'input[type=text]', 'input[type=file]',
    //             'textarea'
    //         ].join(', '),
    //         $inputs = $form.find('.required').find(inputSelector),
    //         $errorMessage = $form.find('div.error'),
    //         valid = true;
    //     $errorMessage.addClass('hide');
    //     $('.has-error').removeClass('has-error');
    //     $inputs.each(function(i, el) {
    //         var $input = $(el);
    //         if ($input.val() === '') {
    //             $input.parent().addClass('has-error');
    //             $errorMessage.removeClass('hide');
    //             e.preventDefault();
    //         }
    //     });
    //     if (!$form.data('cc-on-file')) {
    //         console.log("herrrre")
    //         e.preventDefault();
    //         Stripe.setPublishableKey($form.data('stripe-publishable-key'));
    //         Stripe.createToken({
    //             number: $('.card-number').val(),
    //             cvc: $('.card-cvc').val(),
    //             exp_month: $('.card-expiry-month').val(),
    //             exp_year: $('.card-expiry-year').val()
    //         }, stripeResponseHandler);
    //     }
    // });
    // function stripeResponseHandler(status, response) {
    //     if (response.error) {
    //         $('.error')
    //             .removeClass('hide')
    //             .find('.alert')
    //             .text(response.error.message);
    //     } else {
    //         /* token contains id, last4, and card type */
    //         var token = response['id'];
    //         $form.find('input[type=text]').empty();
    //         $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
    //         $form.get(0).submit();
    //     }
    // }
});

function payment() {
    const formElement = $('#payment-form');
    const action = formElement.attr("action");
    const method = formElement.attr("method");
    const data = new FormData(formElement[0]);
    return $.ajax({
        url: action,
        type: method,
        cache: false,
        data: data,
        contentType: false,
        processData: false,
        beforeSend: function(){
            $('.submit-btn').addClass('button--loading');
        },
        complete: function(){
            $('.submit-btn').removeClass('button--loading');
        },
    });
}

// function stripeResponseHandler(status, response) {
//     if (response.error) {
//         console.log(response.error)
//     } else {
//         /* token contains id, last4, and card type */
//         var token = response['id'];
//         $('#payment-form').append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
//     }
// }
