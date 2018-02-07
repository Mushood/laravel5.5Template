<template>
    <div class="search_box">
        <div class="form-group">
            <input type="text" class="form-control" v-model="query" placeholder="Search" @change="search">
        </div>
    </div>
</template>

<script>

import axios from 'axios';

component: {axios}
export default {
    props: {
        route_search: {
            required: true
        },
    },

    /*
     * The component's data.
     */
    data() {
        return {
            query: "",
        }
    },

    methods: {
        search: function() {
            const vm = this;
            axios.post(vm.route_search , {
                query: vm.query,
            })
            .then(function (response) {
                if(response.data.code == 200){
                    Event.$emit('search_done',{
                        results: response.data.results,
                    });
                }

            })
            .catch(function (error) {

            });
        },
    }
}
</script>

<style>
</style>
