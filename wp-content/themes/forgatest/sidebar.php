<?php $hasSidebar   = is_active_sidebar( 'main-sidebar' ); ?>

<?php if ( $hasSidebar ): ?>
	<aside class="ft-widgets mt-3 w-full max-lg:mt-12 shadow-app rounded-md col-span-3 p-8 prose-base prose-h2:mb-3 prose-a:hover:underline prose-ul:pl-0 prose-li:pl-0 bg-white">
		<?php dynamic_sidebar( 'main-sidebar' ); ?>
	</aside>
<?php endif; ?>