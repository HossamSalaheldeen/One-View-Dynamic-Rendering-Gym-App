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
            city_id: {
                required: true,
            },
            cover:{
                required: !(!!($(`#cover-${resourceId}`).get(0).files.length)),
            }
        },
    }
};









