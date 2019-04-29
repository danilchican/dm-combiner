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
    toastr.info('Saving project...', 'Info');
    let title = $('input#project-title').val();

    let normalize = $('input#normalize-option').prop('checked');
    let scale = $('input#scale-option').prop('checked');

    let checkedCols = [];
    $.each($('.data-head-option:checked'), function (index, option) {
        checkedCols.push($(option).val());
    });

    let data = {
        title: title,
        normalize: normalize,
        scale: scale,
        columns: checkedCols,
        configuration: config,
    };

    $.ajax({
        url: '/account/projects/create',
        method: "POST",
        data: data,
        success: function (response) {
            console.log(response);
            console.log('Project saved.');
            console.log('Saving project data.');

            var message = response.message;
            toastr.info('Uploading data...', 'Info');

            let id = response.project.id;
            let form = $('#project-data-upload-form')[0];
            let formData = new FormData(form);

            $.ajax({
                url: '/account/projects/' + id + '/upload/data',
                data: formData,
                type: 'POST',
                contentType: false,
                processData: false,
                success: function (response) {
                    console.log(response);
                    toastr.success('Project data was uploaded.', 'Success');
                    toastr.success(message, 'Success');
                    window.lastProjectId = id;
                    return true;
                },
                error: function (xhr) {
                    let response = JSON.parse(xhr.responseText);
                    showErrors(response);
                }
            });
        },
        error: function (xhr) {
            let response = JSON.parse(xhr.responseText);
            showErrors(response);
        }
    });
};

window.updateProject = function () {
    console.log('Updating project...');
    toastr.info('Updating project...', 'Info');
    let projectId = $('input#project-id').val();
    let title = $('input#project-title').val();

    let normalize = $('input#normalize-option').prop('checked');
    let scale = $('input#scale-option').prop('checked');

    let checkedCols = [];
    $.each($('.data-head-option:checked'), function (index, option) {
        checkedCols.push($(option).val());
    });

    let data = {
        id: projectId,
        title: title,
        normalize: normalize,
        scale: scale,
        columns: checkedCols,
        configuration: config,
    };

    $.ajax({
        url: '/account/projects/update',
        method: "POST",
        data: data,
        success: function (response) {
            console.log(response);
            console.log('Project updated.');
            console.log('Updating project data.');

            var message = response.message;
            toastr.success(message, 'Success');

            let id = response.project.id;
            let form = $('#project-data-upload-form')[0];
            let formData = new FormData(form);
            let newDataUrl = $('#data-url').val();

            if (newDataUrl !== window.oldDataUrl) {
                toastr.info('Uploading data...', 'Info');

                $.ajax({
                    url: '/account/projects/' + id + '/upload/data',
                    data: formData,
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        console.log(response);
                        toastr.success('Project data was uploaded.', 'Success');
                        window.lastProjectId = id;
                        return true;
                    },
                    error: function (xhr) {
                        let response = JSON.parse(xhr.responseText);
                        showErrors(response);
                    }
                });
            }
        },
        error: function (xhr) {
            let response = JSON.parse(xhr.responseText);
            showErrors(response);
        }
    });
};

window.runProject = function () {
    $.ajax({
        url: '/account/projects/run',
        data: {id: window.lastProjectId},
        type: 'POST',
        success: function (response) {
            console.log(response);
            let resultData = response.result;
            $('#result-textarea').text(resultData);
            toastr.success(response.message, 'Success');
        },
        error: function (xhr) {
            let response = JSON.parse(xhr.responseText);
            showErrors(response);
        }
    });
};

function showErrors(data) {
    console.log(data);

    if (data.errors !== undefined) {
        // error callback
        $.each(data.errors, function (key, value) {
            toastr.error(value, 'Error')
        });
    } else {
        toastr.error('Something went wrong...', 'Error')
    }
}