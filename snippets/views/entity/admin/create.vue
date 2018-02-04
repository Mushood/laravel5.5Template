<template>
    <div class="page_wrapper">
        <div class="row">
            <div class="col-md-6">
                <h1>Preview Entity</h1>
                <h3 v-if="entity.title">{{entity.title}}</h3>
                <p v-if="entity.body" v-html="entity.body"></p>
                <img :src="route_image + '/' + picture" class="img-responsive col-md-10" alt="image" v-if="picture"/>
            </div>
            <div class="col-md-6">
                  <h1>Write Entity</h1>
                  <form @submit.prevent="submitForm">
                  <div class="row">
                      <div class="form-group">
                          <label for="title">Title</label>
                          <input type="text" class="form-control" name="title" id="title" v-model="entity.title">
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group">
                          <label for="content">Content</label>
                          <vue-editor v-model="entity.body"></vue-editor>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group">
                          <label for="image">Image</label>
                          <select  name='image' v-model="picture">
                              <option value="upload">Upload a new picture</option>
                              <option v-for="picture in pictures" :value="picture.value">{{picture.name}}</option>
                          </select>
                      </div>
                      <div class="row" v-if="picture == 'upload'">
                          <multiplefileuploader
                            :posturl="route_image_create"
                            method="post"
                            successmessagepath="File Upload Successful"
                            errormessagepath="File Upload Failure"
                            headermessage="Image upload"
                            :minItems="1"
                            :maxItems="2"
                            :automatic="true"
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
            Event.$on('fileUploaded', function(event){
              console.log(event);
              vm.onFileUpload(event);
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
            route_entity_submit: {
                required: false,
            },
            route_setup: {
                required: false,
            },
            original: {
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
            submitForm: function() {
                const vm = this;
                var url = this.route_entity_submit;


                this.$validator.validateAll().then((result) => {
                    if (result) {
                        axios.post(url, {
                            entity: vm.entity,
                            pictureId: vm.pictureId,
                        })
                        .then(function (response) {

                            if(response.data.code == 200){
                                Vue.swal({
                                    title: 'Success!',
                                    text: 'The entity has been saved',
                                    type: 'success',
                                    confirmButtonText: 'Cool'
                                }).then(function (response) {
                                    window.location.href = "/admin/entity";
                                });
                                vm.resetForm();
                            }

                        })
                        .catch(function (error) {
                            Vue.swal({
                                title: 'Failure!',
                                text: error.response.data.message,
                                type: 'warning',
                                confirmButtonText: "Let's fix it!"
                            });

                            vm.errors = error.response.data;
                        });
                    } else {
                        Vue.swal({
                            title: 'Failure!',
                            text: 'Please fix form values',
                            type: 'warning',
                            confirmButtonText: "Let's fix it!"
                        });
                    }
                });
            } ,

            resetForm: function() {
                this.entity = {};
            } ,

            onFileUpload: function(event) {
                let originalFile = event.filename.split("_")[1];
                let originalName = originalFile.split(".")[0];

                this.pictures.push({id:event.id ,name: originalName, value: event.filename});
                this.picture = event.filename;
                this.pictureId = event.id;
            },

            setup: function(){
                this.entity = this.original;
                let originalFile = this.original.image.name.split("_")[1];
                let originalName = originalFile.split(".")[0];
                this.pictures.push({id:this.original.image.id ,name: originalName, value: this.original.image.name});
                this.picture = this.original.image.name;
                this.pictureId = this.original.image.id;
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
