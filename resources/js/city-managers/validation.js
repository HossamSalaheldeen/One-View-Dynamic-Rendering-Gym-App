let formRules = () =>{
    return {
        rules: {
            name: {
                required: true,
                minlength:1,
                maxlength:100,
                normalizer: function (value) {
                    return $.trim(value);
                },
            },
            national_id: {
                required: true,
                minlength:1,
                maxlength:100,
                normalizer: function (value) {
                    return $.trim(value);
                },
            },
            email: {
                required: true,
                email: true,
                minlength:1,
                maxlength: 100,
                normalizer: function (value) {
                    return $.trim(value);
                },
            },
            password: {
                required: !isEdit,
                minlength:8,
                normalizer: function (value) {
                    return $.trim(value);
                },
            },
            password_confirmation: {
                required: !isEdit,
                equalTo: `#password-${resourceId}`,
                normalizer: function (value) {
                    return $.trim(value);
                },
            },
            city_id: {
                required: true,
            },
        },
    }
};









