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
                                        <draggable v-model="framework.commands" :options="{group:{name:'frameworks', pull:'clone', put: false}}">
                                            <li v-for="command in framework.commands">
                                                <p><input type="checkbox" class="flat"> {{ command }}</p>
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
                        <draggable v-model="selectedAlgorithms" :options="{group:'frameworks', handle: 'draggable'}" :element="'tbody'">
                            <tr class="draggable" v-for="(algorithm, index) in selectedAlgorithms">
                                <td>{{ index + 1 }}</td>
                                <td>{{ algorithm }}</td>
                                <td>
                                    <a class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> Edit </a>
                                    <a class="btn btn-danger btn-xs"
                                       @click="removeAndRestore(index)">
                                        <i class="fa fa-trash-o"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <tr v-if="selectedAlgorithms.length < 1"><td colspan="3">No one algorithm is selected.</td></tr>
                        </draggable>
                    </table>
                    <!-- end selected algorithm list -->
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
                selectedAlgorithms: []
            }
        },

        created() {
            // init data
            this.uploadFrameworks();
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
            removeAndRestore(index) {
                this.selectedAlgorithms.splice(index, 1);
            }
        },

        components: {
            draggable
        }
    }
</script>