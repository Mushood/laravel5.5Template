<template>
<div class="page_wrapper">
    <div class="row">
        <div class="col-md-12">
            <h3 v-if="entity.title">{{entity.title}}</h3>
            <p v-if="entity.body" v-html="entity.body"></p>
            <img :src="route_image + '/' + entity.image.name" class="img-responsive col-md-10" alt="image" v-if="entity.image.name"/>
            <a class="btn btn-success" :href="route_back">Back</a>
        </div>
    </div>
</div>
</template>

<script>

    export default {
        mounted() {
            console.log('Component entity view mounted.');
        },

        props: {
            entity: {
              required: true,
            },
            route_image: {
                required: true,
            },
            route_back: {
                required: false,
            },
        },


        components: {

        },


        data() {
            return {
                show: true,
          };
        },

        methods : {

        },

    }
</script>
