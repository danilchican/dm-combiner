<form class="form-horizontal form-label-left">
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Data
            <span class="required">*</span>
        </label>
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input type="url" id="data-url" name="data-url" class="form-control col-md-7 col-xs-12"
                           placeholder="URL link to .csv file">
                </div>
                <div class="col-md-1 col-sm-1 col-xs-12" style="text-align: center; padding-top: 7px;">or</div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <input type="file" id="data-file" class="form-control" accept=".csv">
                    <button id="data-file-clear" class="btn btn-danger">
                        X
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">Options
            <span class="required">*</span>
        </label>
        <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label style="padding-left: 0;">
                            <input type="checkbox" class="flat" checked="checked" id="normalize-option">
                            Normalize
                        </label>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="checkbox">
                        <label style="padding-left: 0;">
                            <input type="checkbox" class="flat" checked="checked" id="scale-option">
                            Scale
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
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
            // TODO add validation "OR rule"
            let $clearBtn = $('#data-file-clear');

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

                if(url === undefined || url.length < 1) {
                    alert('You should paste link to load data.');

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
        });
    </script>
@endpush