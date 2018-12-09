window.config = {};

window.tableContentRowTemplate = "<tr class='{0} pointer'>{1}</tr>";
window.tableHeaderRowTemplate = "<th class='column-title'>" +
    "<input type='checkbox' class='data-head-option flat' value='{0}'> {1}</th>";

window.showPreviewTable = function (headers, data) {
    // console.log(headers, data);
    let $tableHead = $('#preview-table .headings');
    let $tableContent = $('#preview-table tbody');

    for (let i = 0; i < headers.length; i++) {
        $tableHead.append(getHeaderItem(i, headers[i]));
    }

    $("input").iCheck({checkboxClass: "icheckbox_flat-green"});

    for (let i = 0; i < data.length; i++) {
        let tdClass = (i % 2 === 0) ? 'even' : 'odd';
        $tableContent.append(getTableContentItem(data[i], tdClass));
    }
};

window.getHeaderItem = function (index, title) {
    return tableHeaderRowTemplate.format(index, title);
};

window.getTableContentItem = function (itemTds, tdClass) {
    let tds;

    $.each(itemTds, function (index, value) {
        tds += '<td>' + value + '</td>';
    });

    return tableContentRowTemplate.format(tdClass, tds);
};

window.saveProject = function () {
    console.log('Saving project...');
    let dataUrl = $('input#data-url').val();

    let normalize = $('input#normalize-option').attr('checked');
    let scale = $('input#scale-option').attr('checked');

    let checkedCols = [];
    $.each($('.data-head-option:checked'), function (index, option) {
        checkedCols.push(option.val());
    });

    let configuration = []; // TODO
    let executionResults; // TODO if executed

    let formData = new FormData();

    formData.append('title', $('input#project-title').val());
    formData.append('cols', JSON.stringify(checkedCols));
    formData.append('normalize', JSON.stringify(normalize));
    formData.append('scale', JSON.stringify(scale));
    formData.append('configuration', JSON.stringify(configuration));
    formData.append('results', executionResults);  // TODO if executed

    if (dataUrl === '') {
        formData.append('file', $('input#data-file')[0].files[0]);
    } else {
        formData.append('file-url', dataUrl);
    }

    console.log('data:');
    console.log(formData);
};