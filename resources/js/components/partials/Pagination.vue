<template>
    <div v-if="hasPages()" class="dataTables_paginate paging_simple_numbers" id="datatable_paginate">
        <ul class="pagination">

            <!-- Previous page -->
            <li v-if="onFirstPage()" class="paginate_button previous disabled" id="datatable_previous">
                <a aria-controls="datatable" tabindex="0">Предыдущая</a>
            </li>
            <li v-else class="paginate_button previous" id="datatable_previous">
                <router-link :to="{ name: nav_route, query: {page: getPreviousPageNumber() }}">
                    Предыдущая
                </router-link>
            </li>

            <!-- Pages numbers -->
            <li v-for="page in pages" class="paginate_button"
                :class="{ active : paginator.currentPage === parseInt(page) }">
                <a v-if="isNaN(parseInt(page)) || paginator.currentPage === Number.parseInt(page)">{{ page }}</a>
                <router-link v-else :to="{ name: nav_route, query: {page: page }}">{{ page }}</router-link>
            </li>

            <!-- Next page -->
            <li v-if="hasMorePages()" class="paginate_button next" id="datatable_next">
                <router-link :to="{ name: nav_route, query: {page: getNextPageNumber() }}">
                    Следующая
                </router-link>
            </li>
            <li v-else class="paginate_button next disabled" id="datatable_next">
                <a aria-controls="datatable" tabindex="0">Следующая</a>
            </li>
        </ul>
    </div>
</template>

<script>
    const DEFAULT_PAGES_COUNT = 10;
    const DEFAULT_DIRECTION_PAGES_COUNT = 4;

    export default {
        props: ['paginator', 'nav_route'],

        data() {
            return {
                pages: []
            }
        },

        mounted() {
            this.paginate();
            this.$watch('paginator', this.paginate, {deep: true})
        },

        watch: {
            '$route'() {
                this.$emit('pageChanged');
            }
        },

        methods: {
            hasPages() {
                return this.paginator.lastPage > 1;
            },

            onFirstPage() {
                return this.paginator.currentPage <= 1;
            },

            getPreviousPageNumber() {
                return this.paginator.currentPage - 1;
            },

            getNextPageNumber() {
                return this.paginator.currentPage + 1;
            },

            hasMorePages() {
                return this.paginator.currentPage < this.paginator.lastPage;
            },

            paginate() {
                let lastPage = this.paginator.lastPage;

                if (lastPage <= DEFAULT_PAGES_COUNT) {
                    this.pages = Array(lastPage).fill(0).map((e, i) => i + 1).map(String);
                } else {
                    this.generateNumbers(lastPage);
                }
            },

            generateNumbers(lastPage) {
                let currentPage = this.paginator.currentPage;
                let countRightSide = lastPage - currentPage;

                let numbers = [];

                if (currentPage <= DEFAULT_DIRECTION_PAGES_COUNT) {
                    numbers = Array(currentPage - 1).fill(0).map((e, i) => i + 1).map(String);
                } else {
                    let temp = currentPage - 1;
                    let count = DEFAULT_DIRECTION_PAGES_COUNT;

                    numbers.push('1');

                    if(currentPage - DEFAULT_DIRECTION_PAGES_COUNT > 2) {
                        numbers.push('...');
                    }

                    if(currentPage - 1 === DEFAULT_DIRECTION_PAGES_COUNT) {
                        count--;
                    }

                    numbers = numbers.concat(Array(count).fill(0).map((e, i) => temp--).map(String).reverse());
                }

                numbers.push(currentPage);

                let temp = currentPage + 1;

                if (countRightSide <= DEFAULT_DIRECTION_PAGES_COUNT) {
                    numbers = numbers.concat(Array(countRightSide).fill(0)
                        .map((e, i) => temp++).map(String));
                } else {
                    numbers = numbers.concat(Array(DEFAULT_DIRECTION_PAGES_COUNT).fill(0)
                        .map((e, i) => temp++).map(String));

                    if (lastPage - currentPage > DEFAULT_DIRECTION_PAGES_COUNT + 1) {
                        numbers.push('...');
                    }

                    numbers.push(lastPage.toString());
                }

                this.pages = numbers;
            }
        }
    }
</script>