<?php
	namespace TravelSportsPro;
	
	if (TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items') {
		$sales_items = App::GetClient()->GetController('checkout')->GetSalesItems();
		if (defined('TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE') && TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE == '1')
			$sales_products = $sales_items['sales_items']['item'];
		else
			$sales_products = $sales_items['sales_items'];
		$categories = $sales_items['categories'];
	}
	
?>
<script>
jQuery(function() {
    jQuery('.tsp_ajax_tab').click(function() {
		return tsp_ajax_click(this);
    });
	<?php if (!empty($_SESSION['tsp_client_attrs']['default_tab']) && empty($_SESSION['tsp_redirect']['boot_tab'])) :?>
	tsp_ajax_click(jQuery('.default_tab'));
	<?php endif; ?>
	<?php echo App::GetClient()->NavRedirect(); ?>
});
</script>
<style>
<?php if (!empty(get_option('tsp_nav_item_background'))): ?>
	#tsp_content .nav li a, #tsp_content .dropdown-menu{background-color: <?php echo get_option('tsp_nav_item_background');?>;}
<?php endif; ?>
<?php if (!empty(get_option('tsp_nav_item_color'))): ?>
	#tsp_content .nav li a{color: <?php echo get_option('tsp_nav_item_color');?>;}
<?php endif; ?>
<?php if (!empty(get_option('tsp_active_item_background'))): ?>
	#tsp_content .nav li.active>a{background-color: <?php echo get_option('tsp_active_item_background');?>;}
<?php endif; ?>
<?php if (!empty(get_option('tsp_active_item_color'))): ?>
	#tsp_content .nav li.active>a{color: <?php echo get_option('tsp_active_item_color');?>;}
<?php endif; ?>
</style>
<div>
<ul class="nav nav-pills visible-md visible-lg">
	<li>
		<a href="#tab-dashboard" data-toggle="tab" class="tsp_ajax_tab default_tab" ><i class="fa fa-users"></i> Dashboard</a>
	</li>
	<?php if (TSP_OC_SHOPPING_CART_ENABLED  == '1') : ?>	
			<li>
				<a href="#tab-checkout-cart" data-toggle="tab" class="tsp_ajax_tab" ><i class="fa fa-shopping-cart"></i> Cart</a>
			</li>
	<?php endif; ?>
	<li><a href="#tab-members-edit" class="tsp_ajax_tab"><i class="fa fa-users"></i> Profile</a></li>
	<?php if (TSP_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
		<li><a href="#tab-members-payments" class="tsp_ajax_tab"><i class="fa fa-credit-card"></i> Payments</a></li>
	<?php endif; ?>
	<?php if (TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items'): ?>
		<li><a href="#tab-checkout-sales-items" class="tsp_ajax_tab <?php if (($_SESSION['tsp_client_attrs']['default_tab']) == 'sales-items') echo 'default_tab'; ?>"><i class="fa fa-shopping-bag"></i> <?php echo TSP_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
	<?php endif; ?>
	<li class="dropdown pull-right" id="m-dd">
		<a href="#" data-toggle="dropdown"><i class="fa fa-users"></i> My Account<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#tab-program-registration" data-toggle="tab" class="tsp_ajax_tab"><i class="fa fa-check-circle"></i> Register</a></li>
				<?php if ((TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items') && is_array($sales_products)): ?>
					<?php foreach ($sales_products as $category_id=>$products) : ?>
					<li><a href="#tab-checkout-sales-items" class="tsp_ajax_tab" tsp_category_id = "<?php echo $category_id; ?>"><i class="fa fa-cube"></i> <?php echo $categories[$category_id]; ?></a></li>
					<?php endforeach; ?>
				<?php endif; ?>
				
				<li><a href="#tab-members-change-password" class="tsp_ajax_tab"><i class="fa fa-lock"></i> Change Password</a></li>
				<li><a href="#tab-members-student" class="tsp_ajax_tab"><i class="fa fa-child"></i>  Add Related Player</a></li>
				<li><a href="#tab-gateway-finance" class="tsp_ajax_tab"><i class="fa fa-dollar"></i>  Finance</a></li>
				<li><a href="#tab-members-classes" class="tsp_ajax_tab"><i class="fa fa-list"></i> Programs</a></li>
				<?php if (get_option('tsp_private_lesson_section') == '1'): ?>
				<li><a href="#tab-members-private-lessons" class="tsp_ajax_tab"><i class="fa fa-user-circle"></i>  Private Lessons</a></li>
				<?php endif; ?>
				<li><a href="#tab-members-charges" class="tsp_ajax_tab"><i class="fa fa-dollar"></i> Charges</a></li>
				<li><a href="#tab-members-purchases" class="tsp_ajax_tab"><i class="fa fa-shopping-cart"></i> Purchases</a></li>
				<li><a href="#tab-members-gift-cards" class="tsp_ajax_tab"><i class="fa fa-gift"></i> Gift Cards</a></li>
				<li><a href="#" data-toggle="tab"  tsp_obj="auth" tsp_method="Logout"  tsp_reload="true" class="tsp_ajax_tab"><i class="fa fa-sign-out"></i> Logout</a></li>
			</ul>
	</li>
</ul>
<ul class="nav nav-pills visible-xs visible-sm">
	<?php if (TSP_OC_SHOPPING_CART_ENABLED  == '1') : ?>	
			<li>
				<a href="#tab-checkout-cart" data-toggle="tab" class="tsp_ajax_tab" ><i class="fa fa-shopping-cart fa-2x"></i></a>
			</li>
	<?php endif; ?>
	<?php if (TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items'): ?>
		<li><a href="#tab-checkout-sales-items" class="tsp_ajax_tab <?php if (($_SESSION['tsp_client_attrs']['default_tab']) == 'sales-items') echo 'default_tab'; ?>"><i class="fa fa-shopping-bag fa-2x"></i></a></li>
	<?php endif; ?>
	<li class="dropdown pull-right">
		<a href="#" data-toggle="dropdown"><i class="fa fa-bars fa-2x" aria-hidden="true"></i></a>
			<ul class="dropdown-menu">
				<li><a href="#tab-program-registration" data-toggle="tab" class="tsp_ajax_tab"><i class="fa fa-check-circle"></i> Register</a></li>
				<?php if (TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items'): ?>
					<li><a href="#tab-checkout-sales-items" class="tsp_ajax_tab <?php if (($_SESSION['tsp_client_attrs']['default_tab']) == 'sales-items') echo 'default_tab'; ?>"><i class="fa fa-shopping-bag"></i> <?php echo TSP_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
				<?php endif; ?>
				<li><a href="#tab-members-edit" class="tsp_ajax_tab"><i class="fa fa-users"></i> Profile</a></li>
				<li><a href="#tab-members-change-password" class="tsp_ajax_tab"><i class="fa fa-lock"></i> Change Password</a></li>
				<li><a href="#tab-members-student" class="tsp_ajax_tab"><i class="fa fa-child"></i>  Add Related Player</a></li>
				<li><a href="#tab-gateway-finance" class="tsp_ajax_tab"><i class="fa fa-dollar"></i>  Finance</a></li>
				<li><a href="#tab-members-classes" class="tsp_ajax_tab"><i class="fa fa-list"></i> Programs</a></li>
				<?php if (get_option('tsp_private_lesson_section') == '1'): ?>
				<li><a href="#tab-members-private-lessons" class="tsp_ajax_tab"><i class="fa fa-user-circle"></i>  Private Lessons</a></li>
				<?php endif; ?>
				<li><a href="#tab-members-charges" class="tsp_ajax_tab"><i class="fa fa-dollar"></i> Charges</a></li>
				<li><a href="#tab-members-purchases" class="tsp_ajax_tab"><i class="fa fa-shopping-cart"></i> Purchases</a></li>
				<li><a href="#tab-members-gift-cards" class="tsp_ajax_tab"><i class="fa fa-gift"></i> Gift Cards</a></li>
				<?php if (TSP_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
				<li><a href="#tab-members-payments" class="tsp_ajax_tab"><i class="fa fa-credit-card"></i> Payments</a></li>
				<?php endif; ?>
				<li><a href="#" data-toggle="tab"  tsp_obj="auth" tsp_method="Logout"  tsp_reload="true" class="tsp_ajax_tab"><i class="fa fa-sign-out"></i> Logout</a></li>
			</ul>
	</li>
</ul>
</div>
<div id="tsp-tab-content" class="tab-content">