function initDatePicker(elementSelector, pairSelector = null) {
    $(elementSelector).Zebra_DatePicker({
        show_icon: true,
        direction: true,
        format: 'Y-m-d H:i:s',
        pair: $(pairSelector)
    });
}

function initDomDatePicker(elementsSelector) {
    $(elementsSelector).each(function (i, obj) {
        $(`#${obj.id}`).Zebra_DatePicker({
            show_icon: true,
        });
    });
}

