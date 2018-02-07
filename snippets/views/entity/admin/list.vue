<template>
    <table class="table table-striped">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Date</th>
            <th scope="col">Actions</th>
        </tr>
        </thead>
        <tbody>
            <tr class="testimonial_row" v-for="(entity,index) in entitys">
                <td>{{index+1}}</td>
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
            originals: {
                required: true,
            },
        },

        data() {
            return {
                active: false,
                entitys:{},
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

        },
    }
</script>

<style>
    .fc-button{
        cursor: pointer;
    }
</style>