# How to use the Forgatest Theme

### Some notes
- This theme is designed for testing purposes.
- It includes several custom templates and styles for demonstration.
- Ideally vendors folder shouldn't be included in the theme, but for testing purposes, it is included here. Usually, it should be installed and later updated on the server by running `composer install` and `composer update` commands.
- Even better would be to use a deployment pipeline to handle dependencies and updates.
- Make sure to check the compatibility of the theme with your WordPress version.

## Task 1
### Folder structure with explanations
- As we're using composer and PSR-4 autoloading we are including `src` folder where all the theme logic is located. So we cannot use recommended WordPress class naming conventions like 'class-wp-<something>.php' for our classes and similar.
- Vite.js is used for asset building and bundling.
- The `resources` folder contains all the assets including CSS, JS, and images.
- The `templates` folder contains custom page templates.
- The `woocommerce` folder contains custom WooCommerce templates and overrides.
- The `vendor` folder contains all the composer dependencies.
- The `inc` folder contains additional PHP files for theme functionality like hooks, filters and similar.

### Folder tree structure
```forgatest-theme/
├── inc/
│   ├── theme-hooks.php
│   └── wc-hide-categories.php
│   └── woocommerce.php
├── resources/
│   ├── css/
│   │   └── index.ts
│   │   └── style.css
├── src/
├── template-parts/
│   ├── header-main.php
│   └── main-nav.php
│   └── main-nav-mobile.php
├── vendor/
│   └── composer/
│   └── autoload.php
├── woocommerce/
│   ├── single-product.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── style.css
├── vite-config.ts
└── readme.md
```

### How assets are enqueued
- Assets are built using Vite.js and are located in the `resources` folder.
- The location of the built assets is specified in the `vite.config.js` file but by default they are located in the `dist` folder.
- In the `inc/theme-hooks.php` file, we enqueue the built CSS and JS files using WordPress's `wp_enqueue_style` and `wp_enqueue_script` functions.
- There are multiple ways how this can be achieved, depending on the complexity of the theme and the requirements. From the class-based approach to simple function-based approach.

### Where WooCommerce customizations live
- All WooCommerce customizations are located in the `woocommerce` folder.
- This folder contains custom templates that override the default WooCommerce templates.
- Caveat is that if WooCommerce updates their templates, you need to manually update the custom templates to ensure compatibility.
- If we're adding custom functions or hooks related to WooCommerce, they should be placed in the `inc/woocommerce.php` file or a specifically dedicated WooCommerce functions file within the `inc`.
- If the customizations are extensive like adding new product types or custom product meta fields I would prefer to use a class-based approach and place the logic in the `src` folder.

### How you avoid tightly coupling logic to templates
- To avoid tightly coupling logic to templates, we use a separation of concerns approach.
- We use hooks and filters to pass data from the logic layer to the presentation layer.
- For WooCommerce specifically , we use WooCommerce hooks and filters to modify the output without directly modifying the template files.
- For some examples, check the `inc/woocommerce.php` file for WooCommerce-related hooks and filters.

## Task 2
### Example single-product.php override or partials
- The code can be found in the `woocommerce/single-product.php` file.
- I haven't used partials in this example, but they can be implemented by creating separate template files for different sections of the product page and including them in the main `single-product.php` file using `get_template_part()` function.
- Working partials expamples you can find in the `template-parts` folder.

### Usage of WooCommerce hooks (woocommerce_before_single_product, etc.)
- In the `woocommerce/single-product.php` file, we utilize various WooCommerce hooks to add or modify content on the product page.
- The hooks for overriding default WooCommerce behavior can be found in the `inc/woocommerce.php` file.
- If we have many then we could separate them in `inc` folder like `inc/wc-single-product-hooks` etc. or for complex projects separate it into a dedicated WooCommerce class in the `src` folder for better organization.

### Add-to-cart handling for simple and variable products
- The add-to-cart functionality for both simple and variable products is handled by WooCommerce itself.
- In the `inc/woocommerce.php` file, we ensure that the appropriate add-to-cart forms are displayed based on the product type.
- First example in `inc/woocommmerce.php` shows how to handle add-to-cart for simple products by using the `woocommerce_template_single_add_to_cart` that handles form and all logic natively.

### Why you chose hooks vs template overrides
- I chose to use hooks wherever possible to maintain compatibility with future WooCommerce updates.
- Hooks allow us to modify the output without directly changing the template files, which reduces the risk of conflicts during updates.
- Template overrides are used only when necessary, such as when we need to make significant changes to the layout or structure of the product page that cannot be achieved through hooks alone, but still keeping the overrides minimal to ensure easier maintenance in the future.

### How your solution stays compatible with WooCommerce updates
- By primarily using hooks and filters, we minimize the risk of breaking changes when WooCommerce updates its templates.
- We only override templates when absolutely necessary and keep those overrides up-to-date with the latest WooCommerce versions.
- Regularly testing the theme with the latest WooCommerce releases is essential to ensure compatibility.

## Task 3
### How would you clone the production site safely?
- To clone the production site safely, I would follow these steps:
  1. **Backup the Production Site**: Use a reliable backup plugin or tool to create a full backup of the production site, including the database and all files.
  2. **Set Up a Staging Environment**: Create a separate staging environment on a subdomain or local server where the cloned site will reside.
  3. **Copy Files and Database**: Transfer the backed-up files and database to the staging environment. This can be done using FTP/SFTP for files and phpMyAdmin or command line for the database.
  4. **Update Configuration**: Update the `wp-config.php` file in the staging environment to reflect the new database credentials and any other necessary configurations.
  5. **Search and Replace URLs**: Use a search and replace tool to update any URLs in the database that point to the production site to point to the staging site instead.
  6. **Test the Staging Site**: Thoroughly test the staging site to ensure everything is functioning correctly.
  7. **Secure the Staging Site**: Implement security measures such as password protection or restricting access to prevent unauthorized access.
- We can use plugins like Duplicator, All-in-One WP Migration, or WP Staging to simplify the cloning process or maybe hosting provides that feature for us. Many of them do.
- For database search and replace we can use WP-CLI or plugins like Better Search Replace. Ultimately, we can use mysql commands directly if we have access to the database.
- We should always ensure that sensitive data is protected during the cloning process.
- Emails, payment gateways, and other live services should be disabled or redirected in the staging environment to prevent unintended actions.

### Which plugins or services related to headless setup would be removed or disabled?
- In a headless WordPress setup, we would typically disable or remove plugins that are not necessary for the backend functionality. This may include:
  1. **WPGraphQL / REST API Extensions**: Headless sites often use WPGraphQL or JWT Authentication for secure data fetching. Once you return to a classic theme, these become unnecessary overhead and potential security holes.
  2. **Webhook Managers**: Any plugins sending real-time data to front-end services would be redundant.
  3. **Custom Redirect Plugins**: Many headless setups use plugins to redirect WordPress front-end requests to the headless front-end. These would no longer be needed.

### How would you ensure products • orders • users • SEO data remain untouched?
- First of all, the classic theme uses the same WordPress and WooCommerce core functionalities as the headless setup, so data integrity is maintained by simply cloning or preserving the database.
- Products & Orders: These live in `wp_posts`, `wp_postmeta`, and specialized `wc_` tables. Since the classic theme uses the same core WooCommerce functions as the API, this data remains untouched.
- Users: User data is stored in the `wp_users` and `wp_usermeta` tables, which remain unaffected by theme changes.
- The images and media files are stored in the `wp-content/uploads` directory, which is also unaffected by theme changes.
- SEO Data: SEO plugins like Yoast or Rank Math store their data in custom tables or in post meta. As long as these plugins remain active and their settings are preserved, SEO data will remain intact.

### Include: Migration checklist and rollback strategy
- **Migration Checklist**:
  1. Backup the production site (files and database).
  2. Set up a staging environment.
  3. Clone the production site to the staging environment.
  4. Update configuration files and URLs.
  5. Test the staging site thoroughly.
  6. Disable unnecessary plugins for headless setup.
  7. Ensure all data (products, orders, users, SEO) is intact.
  8. Develop and test the classic theme on the staging site.
  9. Finalize the classic theme and ensure all functionalities work as expected.
     10Prepare for deployment to production.
  11. Deploy the staging site to production.
  12. Monitor the live site for any issues.


- **Rollback Strategy**:
  1. Keep a recent backup of the production site before migration.
  2. If issues arise post-migration, restore the backup to revert to the previous state.
  3. Document any changes made during the migration for easier troubleshooting.

## Task 4
- The code for this task can be found in `inc/wc-hide-categories.php` file.
- There I used WP hooks to hide specific product categories from the menu and category listings.
- I made sure that products from the hidden categories are not displayed in the shop loop by using the `woocommerce_product_query` action hook to modify the WC main query.
- I also made sure that the hidden categories and the products from the hidden categories are still accessible via direct links by not modifying the product query itself.
- I created custom term meta fields to manage the visibility of categories.
- This way, we can easily toggle the visibility of categories without modifying the core WooCommerce functionality.
- This approach ensures that the hidden categories do not interfere with the overall functionality of the WooCommerce
- The other option is to create a global setting in the theme customizer or theme settings page to manage hidden categories.
- This way, we can have a centralized place to manage the visibility of categories.
- In `inc/theme-hooks.php` file, I hooked the functionality to appropriate WooCommerce hooks to ensure that the categories are hidden from the menu that was custom created.

## Optional Bonus Task

### Option A - QA & Production Readiness

#### How you would QA WooCommerce functionality (cart, checkout, emails)
- Test the cart by adding, updating, and removing products to ensure correct behavior.
- Test the checkout process with various payment gateways to ensure successful transactions.
- Verify that order confirmation emails are sent correctly to both the customer and admin.
- Test edge cases such as empty carts, invalid payment details, and out-of-stock products.
- Use automated testing tools like Selenium or Cypress to simulate user interactions and validate functionality.
- Session persistence testing to ensure cart contents are retained across sessions.
- Race condition testing to ensure that simultaneous actions (like multiple users checking out) do not cause issues.
- Guest checkout testing to ensure that users can complete purchases without creating an account.
- Email deliverability testing (new order, invoice and similar) to ensure that order confirmation emails are not marked as spam.

#### What you would test before launch
- Ensure all WooCommerce features (product display, cart, checkout, payment gateways) work as expected.
- That all the links are preserved and working correctly to avoid losing SEO rankings.
- That the theme is fully responsive and works on all devices.
- Verify that SEO settings and metadata are correctly implemented.
- Check site speed and responsiveness under load.
- Verify that sensitive data is protected and that there are no vulnerabilities.
- Ensure compatibility with different browsers and devices.

#### Common WooCommerce issues you proactively test for
- Payment gateway failures or errors during checkout.
- Cart abandonment issues.
- Email deliverability problems.
- Plugin conflicts that may affect WooCommerce functionality.
- Running out of memory or server resources during high traffic.
- Broken links or missing images.
- Issues with tax calculations or shipping rates.
