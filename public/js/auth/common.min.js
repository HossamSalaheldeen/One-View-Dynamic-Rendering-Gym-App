$(function(){
    initUploader();
    initDatePickers('input[name="date_of_birth"]');
});

function initUploader() {
    $('.dropify').dropify({
        messages: {
            'default': 'Drag and drop your avatar',
            'replace': 'Drag and drop or click to replace your avatar',
            'remove':  'Remove',
            'error':   'Ooops, something wrong happended.'
        }
    });
}

function initDatePickers(elementSelector) {
    $(elementSelector).Zebra_DatePicker({
        show_icon: true,
        direction: true,
    });
}
