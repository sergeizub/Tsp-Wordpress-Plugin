<?php
	namespace TravelSportsPro;
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
<div>
<ul class="nav nav-pills">
	<?php echo '<li><a href="#tab-classes-calendar" data-toggle="tab" class="tsp_ajax_tab default_tab"><i class="fa fa-users"></i> Programs</a></li>'; ?>
	<?php echo '<li><a href="#tab-program-registration" data-toggle="tab" class="tsp_ajax_tab"><i class="fa fa-check-circle"></i> Register</a></li>'; ?>
	<?php if (TSP_OC_SHOW_SALES_ITEMS == "1" || $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items'): ?>
		<li><a href="#tab-checkout-sales-items" class="tsp_ajax_tab <?php if (($_SESSION['tsp_client_attrs']['default_tab']) == 'sales-items') echo 'default_tab'; ?>"><i class="fa fa-cube"></i> <?php echo TSP_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
	<?php endif; ?>
	<?php if (TSP_OC_SHOPPING_CART_ENABLED  == '1') : ?>	
			<li>
				<a href="#tab-checkout-cart" data-toggle="tab" class="tsp_ajax_tab" ><i class="fa fa-shopping-cart"></i> Cart</a>
			</li>
	<?php endif; ?>
	
	<li class="dropdown pull-right" id="m-dd">
		<a href="#" data-toggle="dropdown"><i class="fa fa-users"></i> My Account<span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#tab-members-edit" class="tsp_ajax_tab"><i class="fa fa-users"></i> Profile</a></li>
				<li><a href="#tab-members-change-password" class="tsp_ajax_tab"><i class="fa fa-lock"></i> Change Password</a></li>
				<li><a href="#tab-members-student" class="tsp_ajax_tab"><i class="fa fa-child"></i>  Add Related Player</a></li>
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
				<li><a href="#tab-members-cards-accounts" class="tsp_ajax_tab"><i class="fa fa-credit-card"></i> Stored Cards</a></li>
				<li><a href="#" data-toggle="tab"  tsp_obj="auth" tsp_method="Logout"  tsp_reload="true" class="tsp_ajax_tab"><i class="fa fa-sign-out"></i> Logout</a></li>
			</ul>
	</li>
</ul>
</div>
<div id="tsp-tab-content" class="tab-content">