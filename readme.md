<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

## Custom Template
This is a custom template that I use to kickstart a new project. Included are designs, composer packages and npm packages that I use frequently.

*** Composer Packages used ***
- Sluggable: https://github.com/cviebrock/eloquent-sluggable
- Role & Permissions: https://github.com/spatie/laravel-permission
- Image: http://image.intervention.io/
- Excel: https://github.com/Maatwebsite/Laravel-Excel

*** NPM packages ***
- SweetAlert: https://www.npmjs.com/package/vue-sweetalert2
- Text Editor: https://github.com/davidroyer/vue2-editor
- Slider: https://github.com/staskjs/vue-slick
- Vee-validate: https://github.com/baianat/vee-validate
- Moment: https://www.npmjs.com/package/vue-moment

*** Admin designs ***
- Laravel Backpack: https://github.com/Laravel-Backpack/Base :: included skins as well, to change as required.
- An initial layout is set using the navbar from bootstrap 3.3.7 required in app.scss

To change skin: 
- Change skin in layout.blade.php
- Change copy command in webpack.mix.js
- command: rpm run dev/watch/production

*** Front end framework ***
- Vue.js is used as a front-end framework to add reactivity to the project. The required packages are included
- A custom file uploader is used in the project. However, it is in the components folder instead of the node_modules folder since I often find myself modifying its functionality


*** Code Snippets ***
When working on a project, I often have the same code repeated for my entities. Hence I include in my template a list of frequently used code to speed up my workflow. Here are the list of included files:
- AdminEntityController
- EntityController
- Entity
- Entity Migration
- Entity Blade: index, create, show
- Entity VueJS: list, create, show
- Entity routes
- Folder for images of the entity

In order to generate the list of above snippets, you need to run the following command: "php artisan create:entity entitySingular/entityPlural". You can also just include the singular version and for the plural an 's' would be appended.

Example: php artisan create:entity testimonial/testimonials OR php artisan create:entity testimonial
- AdminTestimonialController
- TestimonialController
- Testimonial
- Testimonial Migration
- Testimonial Blade: index, create, show
- Testimonial VueJS: list, create, show
- Testimonial routes
- testimonials folder for images in public/images/testimonials
- AdminTestimonialControllerTest

You can then configure your pages, migrations and controller actions. If by chance, the boilerplate works for you. You can just run "php artisan migrate" to create your entity table. You then need to include your routes file in your web.php and require your components in your app.js. And you are done.

*** Upcoming Snippets ***
- Request Validation

*** Even more ***
- Tests for php code
- Test for js code

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


----
Improvements to this package

1. Make CRUD more eloquent oriented
2. Have a media dedicated controller
3. Fix dynamic pagination 
4. Allow orderable function
5. Extract publishable to a trait
6. Add phpdocs
7. Use php-cs fixer
8. Extract a base controller to include generic methods
9. Validation rules and messages to Request object
10. Steamline front js validation and back validation
11. SEO module functionality
12. Page creation for static content
13. Refactor tests
14. Switch to a more API oriented Back
15. Add API metadata
16. Include appropriate http code
17. Use Scope
18. Improve media trait

----
Future plans

1. Convert into a composer package