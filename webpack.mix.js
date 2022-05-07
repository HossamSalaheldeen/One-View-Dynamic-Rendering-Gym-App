const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .copyDirectory("resources/images", "public/images")
    .copyDirectory("resources/css/vendor", "public/css/vendor")
    .copyDirectory("resources/js/vendor", "public/js/vendor")
    .copyDirectory("resources/plugins", "public/plugins")
    .scripts(
        [
            "resources/js/auth/common.js",
        ],
        "public/js/auth/common.min.js"
    )
    .scripts(
        [
            "resources/js/common-rules.js",
            "resources/js/common-selectors.js",
            "resources/js/common-dates.js",
            "resources/js/common-validations.js",
            "resources/js/common-cruds.js",
        ],
        "public/js/common.min.js"
    )
    .scripts(
        [
            "resources/js/cities/validation.js",
        ],
        "public/js/cities/main.min.js"
    )
    .scripts(
        [
            "resources/js/gyms/validation.js",
        ],
        "public/js/gyms/main.min.js"
    )
    .scripts(
        [
            "resources/js/city-managers/validation.js",
        ],
        "public/js/city-managers/main.min.js"
    )
    .scripts(
        [
            "resources/js/gym-managers/validation.js",
        ],
        "public/js/gym-managers/main.min.js"
    )
    .scripts(
        [
            "resources/js/users/validation.js",
        ],
        "public/js/users/main.min.js"
    )
    .scripts(
        [
            "resources/js/training-sessions/validation.js",
        ],
        "public/js/training-sessions/main.min.js"
    )
    .scripts(
        [
            "resources/js/training-packages/validation.js",
        ],
        "public/js/training-packages/main.min.js"
    )
    .scripts(
        [
            "resources/js/coaches/validation.js",
        ],
        "public/js/coaches/main.min.js"
    )
    .scripts(
        [
            "resources/js/attendances/validation.js",
        ],
        "public/js/attendances/main.min.js"
    )
    .scripts(
        [
            "resources/js/revenues/validation.js",
        ],
        "public/js/revenues/main.min.js"
    )
    .scripts(
        [
            "resources/js/payments/validation.js",
        ],
        "public/js/payments/main.min.js"
    )
    .scripts(
        [
            "resources/js/dashboard/validation.js",
        ],
        "public/js/dashboard/main.min.js"
    )
    .scripts(
        [
            "resources/js/home/validation.js",
        ],
        "public/js/home/main.min.js"
    )
    // .sass('resources/sass/app.scss', 'public/css')
    .css('resources/css/app.css', 'public/css/app.css')
    .postCss('resources/css/main.css', 'public/css/main.min.css');



