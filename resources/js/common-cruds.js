var resourceSelectorQueryParams = {};
let isEdit = false;
let resourceId = 0;
$(function () {
    initDomSelectors(`#create-${resource}-form .selectors`);
    initDomDatePicker('input[name="date_of_birth"]');
    initUploader();
    let defaultAttachment = $('.dropify').attr('data-default-file');
    let createRoles= formRules();
    let createValidator = initValidation.call(createRoles,`#create-${resource}-form`,() => {
        saveResource(`#create-${resource}-form`,'.save-btn').done((res) => {
            $(`#create-${resource}-modal`).modal("hide");
        }).always(()=> {
            $(`#${resource}-table`).DataTable().ajax.reload();
        });
    });

    $(`#create-${resource}-modal`).on('hide.bs.modal', function(){
        $(`#create-${resource}-form`).trigger("reset");
        createValidator.resetForm();
        $(`#create-${resource}-form select`).each(function(i, obj) {
            let jElem = $(`#${obj.id}`);
            jElem.val('').trigger('change');
        });
    });

    console.log(createRoles);

    $(document).on("click", ".create-btn", function () {
        isEdit = false;
        $(`#create-${resource}-modal`).modal("show");

    });

    $(document).on("click", ".edit-btn", async function () {
        isEdit = true;
        const url = $(this).attr('data-url');
        await getModal(url,this).done(res => {
            $(`#edit-modal-container`).html(res);
            $(`#edit-${resource}-modal`).modal('show');
            resourceId = $(`#edit-${resource}-form`).attr('data-id');
            initDomSelectors(`#edit-${resource}-form .selectors`);
            initDomDatePicker(`#edit-${resource}-form input[name="date_of_birth"]`);
            initUploader();
            let editRoles = formRules();
            initValidation.call(editRoles,`#edit-${resource}-form` ,() => {
                saveResource(`#edit-${resource}-form`,'.save-btn').done((res) => {
                    $(`#edit-${resource}-modal`).modal("hide");
                }).always(()=> {
                    $(`#${resource}-table`).DataTable().ajax.reload();
                });
            });
        });
    });

    $(document).on("click", ".show-btn", async function () {
        const url = $(this).attr('data-url');
        await getModal(url,this).done(res => {
            $(`#show-modal-container`).html(res);
            $(`#show-${resource}-modal`).modal('show');
        });
    });

    $(document).on("click", ".delete-btn", function () {
        showConfirmDeleteModal($(this).attr("data-id"), $(this).attr("data-url"));
    });

    $(document).on("click", ".toggle-status-btn", function () {
        showConfirmBanModal($(this).attr("data-id"), $(this).attr("data-url") + '/toggle-ban',$(this).text());
    });

    $(document).on("click", ".confirm-delete-btn", function () {
        const url = $(this).attr("data-url");
        const id = $(this).attr("data-id");
        destroyResource(url,this).done((res) => {
            $(`.delete-btn[data-id=${id}]`).parents('table').DataTable().ajax.reload();
        }).always(()=> {
            $("#delete-modal").modal("hide");
        });
    });

    $(document).on("click", ".confirm-toggle-status-btn", function () {
        const url = $(this).attr("data-url");
        const id = $(this).attr("data-id");
        toggleBanResource(url,this).done((res) => {
            $(`.toggle-status-btn[data-id=${id}]`).parents('table').DataTable().ajax.reload();
        }).always(()=> {
            $("#ban-modal").modal("hide");
        });
    });

    $(document).on("click", ".attend-btn",async function () {
        const url = $(this).attr("data-url");
        const id = $(this).attr("data-id");
       await attendTrainingSession(url).always(()=> {
           $(`.attend-btn[data-id=${id}]`).parents('table').DataTable().ajax.reload();
       });
    });

});

function showConfirmDeleteModal(id, url) {
    $("#delete-modal").modal("show");
    $("#delete-modal").find(".confirm-delete-btn").attr("data-id", id);
    $("#delete-modal").find(".confirm-delete-btn").attr("data-url", url);
}

function showConfirmBanModal(id, url, text) {
    $("#ban-modal").modal("show");
    $("#ban-modal").find(".toggle-span").text(text.toLowerCase());
    $("#ban-modal").find(".confirm-toggle-status-btn span").text(text);
    $("#ban-modal").find(".confirm-toggle-status-btn").attr("data-id", id);
    $("#ban-modal").find(".confirm-toggle-status-btn").attr("data-url", url);
}

function destroyResource(url,elem) {
    return $.ajax({
        url:url,
        type: "DELETE",
        cache: false,
        beforeSend: function(){
            $(elem).addClass('button--loading');
        },
        complete: function(){
            $(elem).removeClass('button--loading');
        },
    });
}

function toggleBanResource(url,elem) {
    return $.ajax({
        url:url,
        type: "PUT",
        cache: false,
        beforeSend: function(){
            $(elem).addClass('button--loading');
        },
        complete: function(){
            $(elem).removeClass('button--loading');
        },
    });
}

function saveResource(formSelector,elem) {
    const formElement = $(formSelector);
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
            $(elem).addClass('button--loading');
        },
        complete: function(){
            $(elem).removeClass('button--loading');
        },
    });
}

function getModal(url,elem) {
    return $.ajax({
        url,
        type: 'GET',
        cache: false,
        beforeSend: function(){
            $(elem).addClass('button--loading');
        },
        complete: function(){
            $(elem).removeClass('button--loading');
        },
    });
}

function attendTrainingSession(url) {
    return $.ajax({
        url,
        type: 'PUT',
        cache: false,
    });
}

