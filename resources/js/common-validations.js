var validationProperties = {
    highlight: function (element, errorClass) {
        $(element).css("border", "1px solid red");
        $(element).next("span").find(".select2-selection").css("border", "1px solid red");
    },

    unhighlight: function (element, errorClass) {
        $(element).css("border", "1px solid #ced4da");
        $(element).next("span").find(".select2-selection").css("border", "1px solid #ced4da");
    },

    errorElement: "span",

    errorPlacement: function (error, element) {
        error.appendTo(element.parents(".input-container"));
    },
};

function initValidation(formSelector ,submitHandler) {
   return $(formSelector).validate({

        rules:this.rules,

        ...validationProperties,

        submitHandler: function (form) {
            submitHandler()
        },
    });
}
