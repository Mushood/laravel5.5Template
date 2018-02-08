<template>
    <div>
        <div class="row" v-if="selections.length > 0">
            <div class="col-md-4 text-center">
                <a class="btn btn-primary btn-block" @click.prevent="delete_entitys"><i class="fa fa-trash"></i> <br/>Delete Selected</a>
            </div>
            <div class="col-md-4 text-center">
                <a class="btn btn-primary btn-block" @click.prevent="unpublish_entitys"><i class="fa fa-eye-slash"></i> <br/>Unpublish Selected</a>
            </div>
            <div class="col-md- text-center">
                <a class="btn btn-primary btn-block" @click.prevent="publish_entitys"><i class="fa fa-bullhorn"></i> <br/>Publish Selected</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" v-model="all_selected" @click="selectAll"></th>
                <th scope="col">
                    <a :href="route_order_title">
                        Title
                    </a>
                </th>
                <th scope="col">Description</th>
                <th scope="col">Date</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr class="testimonial_row" v-for="(entity,index) in entitys">
                <td><input type="checkbox" v-model="selections" @change="update_selection" :value="entity.id"></td>
                <td>{{entity.title}}</td>
                <td v-html="entity.body.substring(0,100)"></td>
                <td>{{new Date(entity.created_at)  | moment("dddd, DD/MM/YYYY")}}</td>
                <td>
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <a class="fc-button" :href="entity.route.edit"><i class="fa fa-edit"></i> <br/>Edit</a>
                        </div>
                        <div class="col-md-3 text-center">
                            <a class="fc-button" @click.prevent="delete_entity(entity.route.delete)"><i class="fa fa-trash"></i> <br/>Delete</a>
                        </div>
                        <div class="col-md-5 text-center" v-if="entity.active">
                            <a class="fc-button" @click.prevent="unpublish(entity.route.unpublish , $event)" :id="index"><i class="fa fa-eye-slash"></i> <br/>Unpublish</a>
                        </div>
                        <div class="col-md-5 text-center" v-else>
                            <a class="fc-button" @click.prevent="publish(entity.route.publish, $event)" :id="index"><i class="fa fa-bullhorn"></i> <br/>Publish</a>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component list entity mounted.');
            this.entitys = this.originals;
            const vm = this;
            Event.$on('search_done', function(event){
                vm.reload_results(event);
            });
        },

        props: {
            index: {
                required: false,
                default: 0
            },
            route_image: {
                required: true,
            },
            route_bulk_action: {
                required: true,
            },
            originals: {
                required: true,
            },
            route_order_title: {
                required: true,
            },
        },

        data() {
            return {
                all_selected: false,
                entitys:{},
                selections:[],
            };
        },

        methods : {

            publish: function(route, event) {
                const vm = this;
                let index = event.target.id;
                axios.get(route , {

                })
                .then(function (response) {
                    if(response.data.code == 200){
                        Vue.swal({
                            title: 'Success!',
                            text: 'The entity has been published',
                            type: 'success',
                            confirmButtonText: 'Cool'
                        });
                        vm.entitys[index].active = !vm.entitys[index].active;
                    }


                })
                .catch(function (error) {

                });
            },

            unpublish: function(route, event) {
                const vm = this;
                let index = event.target.id;
                axios.get(route , {

                })
                .then(function (response) {
                    if(response.data.code == 200){
                        Vue.swal({
                            title: 'Warning!',
                            text: 'The entity has been unpublished. It can no longer be viewed by your users!',
                            type: 'warning',
                            confirmButtonText: 'Cool'
                        });
                        vm.entitys[index].active = !vm.entitys[index].active;
                    }

                })
                .catch(function (error) {

                });
            },

            delete_entity: function(route) {
                const vm = this;

                Vue.swal({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, cancel!',
                    confirmButtonClass: 'btn btn-success',
                    cancelButtonClass: 'btn btn-danger',
                    buttonsStyling: false,
                    reverseButtons: true
                }).then((result) => {
                    if (result.value) {
                        axios.delete(route , {

                        })
                        .then(function (response) {

                            if(response.data.code == 200){
                                Vue.swal(
                                    'Deleted!',
                                    'Your entity has been deleted.',
                                    'success'
                                ).then((result2) => {
                                    if (result2.value) {
                                        location.reload();
                                    }
                                });
                            }

                      })
                      .catch(function (error) {

                      });
                  // result.dismiss can be 'cancel', 'overlay',
                  // 'close', and 'timer'
                    } else if (result.dismiss === 'cancel') {
                        Vue.swal(
                            'Cancelled',
                            'Your entity is safe :)',
                            'error'
                        )
                    }
                })

            },

            reload_results: function(event) {
                this.entitys = event.results;
            },

            update_selection(e){
                let target = e.target.id;
                let index = this.selections.indexOf(target);

                if(index > 0){
                    this.selections.splice(index, 1);
                } else {
                    this.selections.push(target);
                }
            },

            delete_entitys(){
                this.bulk_action('delete');
            },

            unpublish_entitys(){
                this.bulk_action('unpublish');
            },

            publish_entitys(){
                this.bulk_action('publish');
            },

            bulk_action(task){
                const vm = this;
                axios.post(vm.route_bulk_action , {
                    action: task,
                    selections: this.selections,
                })
                .then(function (response) {
                    if(response.data.code == 200){
                        Vue.swal({
                            title: 'Success!',
                            text: 'Action Complete',
                            type: 'success',
                            confirmButtonText: 'Cool'
                        });
                        vm.entitys = response.data.updated_results;
                        vm.selections = [];
                        vm.all_selected = false;
                    }


                })
                .catch(function (error) {

                });
            },

            selectAll: function() {
                this.selections = [];

                if (!this.all_selected) {
                    for (var i=0;i<this.entitys.length;i++) {
                        this.selections.push(this.entitys[i].id);
                    }
                }
            },


        },
    }
</script>

<style>
    .fc-button{
        cursor: pointer;
    }
</style>