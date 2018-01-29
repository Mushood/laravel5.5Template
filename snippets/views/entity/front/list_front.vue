<template>
  <div class="entityblock">
    <div class="col-lg-4 col-md-2 col-sm-2 col-xs-12">
        <img :src="route_image + '/' + entity.image.name" :alt="entity.image.name" class="img-responsive">
    </div>
    <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
        <h3>{{entity.title}}</h3>
        <span v-html="entity.body.substring(0,600)"></span>
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <a :href="route_show">
                    <button class="btn btn-warning btn-block">show</button>
                </a>
            </div>
        </div>
      </div>
  </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component list entity mounted.');
            this.active = this.entity.active;
        },

        props: {
            edit: {
                required: false,
                default: 0
            },
            route_show: {
                required: false,
            },
            route_image: {
                required: true,
            },
            route_edit: {
                required: false,
            },
            route_publish: {
                required: false,
            },
            route_unpublish: {
                required: false,
            },
            route_delete: {
                required: false,
            },
            is_admin: {
                required: true,
                default: false,
            },
            entity: {
                required: true,
            },
        },

        data() {
            return {
                errors: {},
                active: false,
            };
        },

        methods : {

          publish: function(e) {
            const vm = this;
            axios.get(vm.route_publish , {

            })
            .then(function (response) {
              if(response.data.code == 200){
                Vue.swal({
                  title: 'Success!',
                  text: 'The entity has been published',
                  type: 'success',
                  confirmButtonText: 'Cool'
                });

                vm.entity.active = !vm.entity.active;
                vm.active = vm.entity.active;
              }


            })
            .catch(function (error) {

            });
          },

          unpublish: function(e) {
            const vm = this;
            axios.get(vm.route_unpublish , {

            })
            .then(function (response) {
                if(response.data.code == 200){
                    Vue.swal({
                      title: 'Warning!',
                      text: 'The entity has been unpublished. It can no longer be viewed by your users!',
                      type: 'warning',
                      confirmButtonText: 'Cool'
                    });

                    vm.entity.active = !vm.entity.active;
                    vm.active = vm.entity.active;
                }

            })
            .catch(function (error) {

            });
          },

          delete_entity: function(e) {
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
                      axios.delete(vm.route_delete , {

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

          }

        },
    }
</script>
