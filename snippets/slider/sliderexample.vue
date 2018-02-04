<template>
<div class="page_wrapper">
  <div class="row">
    <div class="col-md-8">
      <h1>Preview Sliders</h1>
      <div class="slider row" v-for="slider in sliders">
          <img :src="route_image + '/' + slider.image.name" class="img-responsive col-md-6" alt="image"/>
          <div class="col-md-5">
              <button class="btn btn-danger btn-block" :id="slider.id" @click.prevent="remove">Remove</button>
          </div>
      </div>
    </div>
    <div class="col-md-4">
      <h1>Upload sliders</h1>
      <form @submit.prevent="submitForm">
      <div class="row">
        <div class="row">
          <multiplefileuploader
            :posturl="route_image_create"
            method="post"
            successmessagepath="File Upload Successful"
            errormessagepath="File Upload Failure"
            headermessage="Image upload"
            :minItems="1"
            :maxItems="10"
            :automatic="false"
          >
          </multiplefileuploader>
        </div>
      </div>

      <hr />
      <div class="row">
        <button type="submit" class="btn btn-primary">
          I like what I see!
        </button>
      </div>
    </form>
    </div>
  </div>

</div>
</template>

<script>
    import { VueEditor } from 'vue2-editor'

    export default {
        mounted() {
            console.log('Component entity create mounted.');
            let vm = this;
            Event.$on('fileUploaded', function(entity){
              console.log(entity);
              vm.onFileUpload(entity);
            });
            if(this.edit>0){
                this.setup();
            }
        },

        props: {
            edit: {
              required: false,
              default: 0
            },
            route_image: {
                required: true,
            },
            route_image_create: {
                required: true,
            },
            route_entity_destroy: {
                required: false,
            },
            sliders: {
                required: false,
            },
        },


        components: {
           VueEditor
        },


        data() {
            return {
                pictures: [],
                picture: "",
                pictureId: "",
                errors: {},
                entity: {}
          };
        },

        methods : {

          onFileUpload: function(entity) {

            this.sliders = entity.filename;
          },

            remove: function(e) {
            const vm = this;
            var id = e.target.id;
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
                    axios.post(vm.route_entity_destroy , {
                        'slider' : id,
                    })
                    .then(function (response) {

                        if(response.data.code == 200){
                            Vue.swal(
                                'Deleted!',
                                'Your slider has been deleted.',
                                'success'
                            ).then((result2) => {
                                if (result2.value) {
                                    vm.sliders = response.data.sliders;
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
                    'Your slider is safe :)',
                    'error'
                )
            }
          })

         },

          setup: function(){
              /*
              const vm = this;
              axios.get(vm.route_setup, {

              })
              .then(function (response) {

                  if(response.data.code == 200){

                  }

              })
              .catch(function (error) {
                  vm.errors = error.response.data;
              });
              */
          }

        },

    }
</script>

<style>
    .slider{
        margin-bottom: 15px;
    }
</style>
