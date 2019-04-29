<form class="form-horizontal form-label-left" id="project-data-upload-form" enctype="multipart/form-data">
    <div class="form-group">
        <p class="col-md-2 col-sm-2 col-xs-12" style="text-align: right;"><b>Old data url:</b></p>
        <div class="col-md-9 col-sm-9 col-xs-12">
            {{ $project->getDataUrl() }}
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Upload new data
            <span class="required">*</span>:
        </label>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input type="url" id="data-url" name="data-url" class="form-control col-md-7 col-xs-12"
                           value="{{ $project->getDataUrl() }}"
                           placeholder="URL link to .csv file">
                </div>
                <div class="col-md-1 col-sm-1 col-xs-12" style="text-align: center; padding-top: 7px;">or</div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" id="data-file" name="data-file" class="form-control" accept=".csv">
                    <button id="data-file-clear" class="btn btn-danger">
                        X
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
<div class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Options
            <span class="required">*</span>:
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label style="padding-left: 0;">
                            <input type="checkbox" class="flat" id="normalize-option"
                                   @if($project->getNormalize()) checked="checked" @endif>
                            Normalize
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label style="padding-left: 0;">
                            <input type="checkbox" class="flat" id="scale-option"
                                   @if($project->getScale()) checked="checked" @endif>
                            Scale
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div>
<br/>
<label class="control-label col-md-2 col-sm-2 col-xs-12" style="text-align: right">Preview:</label>
<div class="col-md-9 col-sm-9 col-xs-12" id="preview-block">
    <p>Data was not uploaded</p>
    <div class="table-responsive" style="display: none">
        <table class="table table-striped jambo_table bulk_action" id="preview-table">
            <thead>
            <tr class="headings"></tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.buttonSave').css({
                'opacity' : '1',
                'cursor' : 'pointer',
                'pointer-events': 'auto'
            });

            // TODO add validation "OR rule"
            let $clearBtn = $('#data-file-clear');
            let selectedColumns = {{ $project->getCheckedColumns() }};

            $clearBtn.click(function (e) {
                e.preventDefault();

                let $tableHead = $('#preview-table .headings');
                let $tableContent = $('#preview-table tbody');

                $('#data-file').val('');
                $clearBtn.hide();
                $('#preview-block').find('p').show();
                $tableHead.empty();
                $tableContent.empty();
            });

            $('#data-file').on('change', function () {
                $clearBtn.show();

                let $tableHead = $('#preview-table .headings');
                let $tableContent = $('#preview-table tbody');

                $('#data-url').val('');
                $clearBtn.hide();
                $('#preview-block').find('p').show();
                $tableHead.empty();
                $tableContent.empty();

                $(this).parse({
                    config: {
                        preview: 10,
                        header: true,
                        skipEmptyLines: true,
                        complete: function (results) {
                            let headers = results.meta.fields;
                            let data = results.data;
                            let headersCount = headers.length;

                            if (headersCount > 0) {
                                let $previewBlock = $('#preview-block');
                                $previewBlock.find('.table-responsive').show();
                                $previewBlock.find('p').hide();
                                showPreviewTable(headers, data);
                            }
                        }
                    }
                });
            });

            $('#data-url').on('focusout', function () {
                let url = $(this).val();
                let $previewBlock = $('#preview-block');

                if (url === undefined || url.length < 1) {
                    let $tableHead = $('#preview-table .headings');
                    let $tableContent = $('#preview-table tbody');

                    $previewBlock.find('p').show();
                    $tableHead.empty();
                    $tableContent.empty();

                    return;
                }

                Papa.parse(url, {
                    preview: 10,
                    header: true,
                    skipEmptyLines: true,
                    download: true,
                    complete: function (results) {
                        var headers = results.meta.fields;
                        var data = results.data;
                        var headersCount = headers.length;

                        if (headersCount > 0) {
                            $previewBlock.find('.table-responsive').show();
                            $previewBlock.find('p').hide();
                            showPreviewTable(headers, data);
                        }
                    }
                });
            });

            Papa.parse($('#data-url').val(), {
                preview: 10,
                header: true,
                skipEmptyLines: true,
                download: true,
                complete: function (results) {
                    var headers = results.meta.fields;
                    var data = results.data;
                    var headersCount = headers.length;

                    if (headersCount > 0) {
                        $clearBtn.click();
                        $('#preview-block').find('.table-responsive').show();
                        $('#preview-block').find('p').hide();
                        showPreviewTable(headers, data);

                        let cols = $('input[type="checkbox"].data-head-option')
                            .filter(e => selectedColumns.includes(e));

                        $.each(cols, function (index, col) {
                            console.log(col);
                            $(col).attr('checked', true);
                            $(col).closest('.icheckbox_flat-green').addClass('checked');
                        });
                    }
                }
            });

            $('.buttonSave').on('click', function () {
                // TODO
            });
        });
    </script>
@endpush