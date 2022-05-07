$(function(){

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },

        success: function (res) {
            if (res){
                if (res.message){
                    fireSweetAlert('success',res.message)
                }
            }
        },

        error: function (res) {

            if (res.status == 422) {
                fireSweetAlert('error', Object.values(res.responseJSON.errors)[0][0]);
            }

            if (res.status == 403) {
                fireSweetAlert('error',res.responseJSON.message);
            }

            if (res.status == 429) {
                fireSweetAlert('error',res.responseJSON.message);
            }

            if (res.status == 500) {
                fireSweetAlert('error',"Internal Server Error");
            }
        },
    });

    $(document).ajaxStart(function (e) {
        loadingButton()
    });

    $(document).ajaxComplete(function () {
        unLoadingButton();
    });

});

function initToast() {
    return Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
}

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

function fireSweetAlert(type,msg) {
    Toast = initToast();

    Toast.fire({
        icon: type,
        title: msg
    })
}

function loadingButton() {
    $('button.ajax-start').attr('disabled',true);
}

function unLoadingButton() {
    $('button.ajax-start').attr('disabled',false);
}

