window.$previewBlock = $('#preview-block');
window.$previewTable = $('#preview-table');

window.$tableHead = $previewTable.find('.headings');
window.$tableContent = $previewTable.find('tbody');

window.tableContentRowTemplate = "<tr class='{0} pointer'>{1}</tr>";

window.showPreviewTable = function (headers, data) {
    // console.log(headers, data);
    $tableHead.empty();
    $tableContent.empty();

    for (let i = 0; i < headers.length; i++) {
        $tableHead.append(getHeaderItem(headers[i]));
    }

    $("input").iCheck({checkboxClass: "icheckbox_flat-green"});

    for (let i = 0; i < data.length; i++) {
        let tdClass = (i % 2 === 0) ? 'even' : 'odd';
        $tableContent.append(getTableContentItem(data[i], tdClass));
    }
};

window.getHeaderItem = function (title) {
    return '<th class="column-title"> <input type="checkbox" class="flat"> ' + title + '</th>'
};

window.getTableContentItem = function (itemTds, tdClass) {
    let tds;

    $.each(itemTds, function (index, value) {
        tds += '<td>' + value + '</td>';
    });

    return tableContentRowTemplate.format(tdClass, tds);
};