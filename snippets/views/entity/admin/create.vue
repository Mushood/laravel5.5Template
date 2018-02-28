<template>
    <div class="page_wrapper">
        <div class="row">
            <div :class="'col-md-' + create_width_preview">
                <h1>Preview Entity</h1>
                <h3 v-if="entity.title">{{entity.title}}</h3>
                <p v-if="entity.body" v-html="entity.body"></p>
                <img :src="route_image + '/' + picture" class="img-responsive col-md-10" alt="image" v-if="picture"/>
                <div class="row">
                    <button type="button" class="btn btn-warning col-md-3" @click.prevent="show_form">
                        Continue Editing
                    </button>
                </div>
            </div>
            <div :class="'col-md-' + create_width_form">
                  <h1>Write Entity</h1>
                  <form @submit.prevent="submitForm">
                  <div class="row">
                      <div class="form-group">
                          <label for="title">Title</label>
                          <input type="text" class="form-control" name="title" id="title" v-model="entity.title" v-validate="'required'">
                          <span v-show="errors.has('title')" class="help is-danger">{{ errors.first('title') }}</span>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group">
                          <label for="content">Content</label>
                          <vue-editor v-model="entity.body" name="body" v-validate="'required'"></vue-editor>
                          <span v-show="errors.has('body')" class="help is-danger">{{ errors.first('body') }}</span>
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group">
                          <label for="image">Image</label>
                          <select  name='image' v-model="picture" v-validate="'required'">
                              <option value="upload">Upload a new picture</option>
                              <option v-for="picture in pictures" :value="picture.value">{{picture.name}}</option>
                          </select>
                          <span v-show="errors.has('image')" class="help is-danger">{{ errors.first('image') }}</span>
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
                      <button type="submit" class="btn btn-primary col-md-3" v-if="!in_progress">
                          I like what I see!
                      </button>
                      <button type="submit" class="btn btn-danger col-md-3" disabled v-else>
                          Processing...
                      </button>
                      <button type="button" class="btn btn-warning col-md-3 col-md-offset-1" @click.prevent="show_preview">
                          Preview
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
                entity: {},
                create_width_form: 12,
                create_width_preview: '0 hide',
                server_error:[],
                in_progress: false,
          };
        },

        methods : {
            submitForm: function() {
                const vm = this;
                var url = this.route_entity_submit;


                this.$validator.validateAll().then((result) => {
                    if (result) {
                        vm.in_progress = true;
                        axios.post(url, {
                            entity: vm.entity,
                            pictureId: vm.pictureId,
                        })
                        .then(function (response) {
                            vm.in_progress = false;
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
                            vm.in_progress = false;
                            var text = error.response.data.message;
                            vm.server_error = error.response.data.errors;

                            if(vm.server_error){
                                text += "<br />" + vm.server_error['entity.title'][0];
                                //text += "<br />" + vm.server_error['entity.body'][0];
                            }

                            Vue.swal({
                                title: 'Failure!',
                                html: text,
                                type: 'warning',
                                confirmButtonText: "Let's fix it!"
                            });
                        });
                    } else {
                        vm.in_progress = false;
                        Vue.swal({
                            title: 'Failure!',
                            text: 'Please fix form values',
                            type: 'warning',
                            confirmButtonText: "Let's fix it!"
                        });
                    }
                });
            } ,

            show_form: function(){
                this.create_width_form = 12;
                this.create_width_preview = '0 hide';
            },

            show_preview: function(){
                this.create_width_form = '0 hide';
                this.create_width_preview = 12;
            },

            show_side: function(){
                this.create_width_form = 6;
                this.create_width_preview = 6;
            },

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

<style>
    .page_wrapper{
        padding: 10px;
    }
</style>
