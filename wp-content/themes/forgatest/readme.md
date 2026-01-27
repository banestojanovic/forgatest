# How to use the Forgatest Theme

### Some notes
- This theme is designed for testing purposes.
- It includes several custom templates and styles for demonstration.
- Ideally vendors folder shouldn't be included in the theme, but for testing purposes, it is included here. Usually, it should be installed and later updated on the server by running `composer install` and `composer update` commands.
- Make sure to check the compatibility of the theme with your WordPress version.

### Folder structure
- As we're using composer and PSR-4 autoloading we are including `app` folder where all the theme logic is located. So we cannot use recommended WordPress class naming conventions like 'class-wp-<something>.php' for our classes and similar.
- Vite.js is used for asset building and bundling.
- The `resources` folder contains all the assets including SCSS, JS, and images.
- The `templates` folder contains custom page templates.
- The `woocommerce` folder contains custom WooCommerce templates and overrides.

## Task 1

## Task 2

## Task 3

## Task 4
