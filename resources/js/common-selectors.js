function initSelector(url, elementSelector, noResults) {
    $(elementSelector).select2({
        language: {
            noResults: function () {
                return noResults;
            },
        },
        placeholder: $(elementSelector).attr("data-placeholder"),
        allowClear: !$(elementSelector).prop("multiple"),
        enable: false,
        ajax: {
            url: url,
            dataType: "json",
            data: function (params) {
                return {
                    page: params.page || 1,
                    ...params,
                };
            },
            processResults: function (data) {
                return {
                    results: mapSelect2Data(data),
                    pagination: {
                        more: data.meta.current_page < data.meta.last_page,
                    },
                };
                // return mapSelect2Data(data);
            },
        },
    });
    $.fn.Select2UpdatePlaceholder = function (newPlaceholder) {
        var $select2Container = $(this).data('select2').$container;
        return $select2Container.find('.select2-selection__placeholder').text(newPlaceholder);
    };
    $(elementSelector)
        .on("select2:select", function (e) {
            if (
                (!$(elementSelector).prop("multiple") && $(this).val()) ||
                ($(elementSelector).prop("multiple") && $(this).val().length)
            ) {
                $(this).removeClass("error");
                // $(this).parents('.input-container').find('span.error').hide()
                if ($(`${elementSelector}-error`).length) {
                    $(`${elementSelector}-error`).hide();
                    $(this).parents(".input-container").find(".select2-selection").css("border", "1px solid #ced4da");
                }
            }
        })
        .trigger("change");

    $(elementSelector)
        .on("select2:unselect select2:clear", function (e) {
            if (
                e.type == "select2:clear" ||
                (!$(elementSelector).prop("multiple") && !$(this).val()) ||
                ($(elementSelector).prop("multiple") && !$(this).val().length)
            ) {
                $(this).addClass("error");
                if ($(`${elementSelector}-error`).length) {
                    $(`${elementSelector}-error`).show();
                    $(this).parents(".input-container").find(".select2-selection").css("border", "1px solid red");
                }
            }
        })
        .trigger("change");
}

function mapSelect2Data(data) {
    console.log(data.data)
    var data2 = [];
    data.data.forEach(function (item) {
        data2.push({
            id: item.id,
            text: item.name,
        });
    });
    return data2;
}

function initResourceSelector(endPoint, elementSelector, noResults, queryParamsKey) {
    initSelector(
        function () {
            return `/api/${endPoint}?` + new URLSearchParams(resourceSelectorQueryParams[queryParamsKey]).toString();
        },
        elementSelector,
        noResults,
        queryParamsKey
    );
}

function initDomSelectors(selectors) {
    $(selectors).each(function(i, obj) {
        console.log(obj.id);
        jElemName = $(`#${obj.id}`).attr('data-name');
        jElemPluralName = $(`#${obj.id}`).attr('data-plural');
        resourceSelectorQueryParams[jElemName] = {};
        initResourceSelector(jElemPluralName,`#${obj.id}`, `No ${jElemPluralName} found!`, jElemName);
    });
}
