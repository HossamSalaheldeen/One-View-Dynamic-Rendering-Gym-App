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
            starts_at:{
                required: true,
            },
            finishes_at: {
                required: true,
            },
            'coaches[]': {
                required: true,
            }
        },
    }
};

$(function () {
    $(window).on('show.bs.modal', function(e){
        console.log(e.currentTarget);
        const jElem = $(`#${e.target.id}`);
        const jElemId= jElem.attr('data-id');
        initDatePicker('input[name="starts_at"]',`#finishes_at-${jElemId}`);
        initDatePicker('input[name="finishes_at"]');
    });
});









