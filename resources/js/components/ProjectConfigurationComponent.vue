<template>
    <div>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-align-left"></i> Frameworks / Algorithms</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- start accordion -->
                    <div class="accordion" id="accordion1" role="tablist" aria-multiselectable="true">
                        <div class="panel" v-for="(framework, index) in frameworks" v-if="frameworks.length > 0">
                            <a class="panel-heading" role="tab" :id="'headingOne-' + index" data-toggle="collapse"
                               data-parent="#accordion1" :href="'#collapseOne-' + index" aria-expanded="true"
                               aria-controls="collapseOne">
                                <h4 class="panel-title">{{ framework.title }}</h4>
                            </a>

                            <div :id="'collapseOne-' + index" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="headingOne">
                                <div class="panel-body">
                                    <ul class="to_do" v-if="framework.commands.length > 0">
                                        <draggable v-model="framework.commands"
                                                   :options="{group:{name:'frameworks', pull:'clone', put: false}}">
                                            <li v-for="command in framework.commands">
                                                <p>{{ command.title }}</p>
                                            </li>
                                        </draggable>
                                    </ul>
                                    <p v-else>No commands found.</p>
                                </div>
                            </div>
                        </div>
                        <p v-else>No frameworks found.</p>
                    </div>
                    <!-- end of accordion -->
                </div>
            </div>
        </div>
        <div class="col-md-8 col-sm-8 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-sellsy"></i> Selected algorithms</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <!-- start selected algorithm list -->
                    <table class="table table-striped projects">
                        <thead>
                        <tr>
                            <th style="width: 1%">#</th>
                            <th>Algorithm Name</th>
                            <th style="width: 30%">Action</th>
                        </tr>
                        </thead>
                        <draggable v-model="selectedAlgorithms" :options="{group:'frameworks', handle: '.draggable'}"
                                   :element="'tbody'">
                            <tr class="draggable" v-for="(algorithm, index) in selectedAlgorithms">
                                <td>{{ index + 1 }}</td>
                                <td>{{ algorithm.title }}</td>
                                <td>
                                    <a data-toggle="modal" data-target="#editCommandModal"
                                       class="btn btn-info btn-xs" @click="editAlgorithm(index, algorithm)">
                                        <i class="fa fa-pencil"></i> Edit
                                    </a>
                                    <a class="btn btn-danger btn-xs" @click="remove(index)">
                                        <i class="fa fa-trash-o"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="selectedAlgorithms.length < 1">
                                <td colspan="3">No one algorithm is selected.</td>
                            </tr>
                        </draggable>
                    </table>
                    <!-- end selected algorithm list -->
                </div>
            </div>
        </div>

        <div class="modal fade" id="editCommandModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="editCommandModalLabel">
                            Command configuration "{{ editCommand.framework }}/{{ editCommand.title }}"
                        </h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group" v-for="(option, index) in editCommand.options">
                            <label for="link-title-edit">{{ option.title }}</label>
                            <input :type="option.type" id="link-title-edit" @keyup.enter="updateLink()"
                                   v-model="editCommand.options[index].value"
                                   placeholder="Enter the value" class="form-control">
                        </div>

                        <p v-if="editCommand.options.length < 1">No options found.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="button" @click="updateCommandConfig()" class="btn btn-primary">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import draggable from 'vuedraggable'

    export default {
        data() {
            return {
                frameworks: [],
                selectedAlgorithms: [],
                options: [],
                editCommand: {
                    index: null,
                    title: '',
                    framework: '',
                    options: [],
                },
            }
        },

        created() {
            this.uploadFrameworks();
        },

        watch: {
            selectedAlgorithms: function () {
                console.log(this.selectedAlgorithms);
                config = this.selectedAlgorithms;
            }
        },

        methods: {
            uploadFrameworks() {
                let requestURL = '/account/projects/frameworks';

                if (useMock) {
                    requestURL = '/mocks/frameworks.json';
                }

                this.$http.get(requestURL).then((response) => {
                    console.log(response);

                    if (response.status === 200) {
                        this.frameworks = response.body;
                    }
                }, () => {
                    toastr.error('Something went wrong...', 'Error');
                });
            },
            remove(index) {
                this.selectedAlgorithms.splice(index, 1);
            },
            editAlgorithm(index, command) {
                this.editCommand = JSON.parse(JSON.stringify(command));
                this.editCommand.index = index;

                if(this.editCommand.options.length < 1) {
                    let requestURL = '/account/projects/args/' + command.framework + '/' + command.title;

                    this.$http.get(requestURL).then((response) => {
                        console.log(response);

                        if (response.status === 200) {
                            this.editCommand.options = response.body;
                        }
                    }, () => {
                        toastr.error('Something went wrong...', 'Error');
                    });
                }
            },

            updateCommandConfig() {
                let editCommandIndex = this.editCommand.index;
                this.selectedAlgorithms[editCommandIndex].options = this.editCommand.options;

                this.editCommand.index = null;
                this.editCommand.title = '';
                this.editCommand.framework = '';
                this.editCommand.options = [];


                $('#editCommandModal').modal('hide');
            }
        },

        components: {
            draggable
        }
    }
</script>