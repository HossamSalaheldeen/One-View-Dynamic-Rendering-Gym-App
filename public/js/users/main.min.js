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
            date_of_birth: {
                required: true,
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
                minlength:6,
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
            'gender[]':{
                required: true
            }
        },
    }
};
