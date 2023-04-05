<?php
	namespace TravelSportsPro;
	$tab = App::GetClient()->GetTab();
?>
<script>
jQuery(function() {
    jQuery('.tsp_ajax_tab').click(function() {
		return tsp_ajax_click(this);
    });
	tsp_ajax_click(jQuery('.default_tab'));
});

var show_login_alert = '<?php echo TSP_OC_SHOW_LOGIN_ALERT; ?>';
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
<ul class="nav nav-pills">
	    <?php if (isset($_SESSION['tsp_client_attrs']['default_tab']) && $_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items') : ?>
	    <li><a href="#tab-checkout-sales-items" data-toggle="tab"  class="tsp_ajax_tab default_tab"><i class="fa fa-cube"></i> <?php echo TSP_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
		<?php else: ?>
		<li><a href="#tab-auth-login" data-toggle="tab" class="tsp_ajax_tab default_tab"><i class="fa fa-users"></i> Programs</a></li>
		<?php endif; ?>
	<li class="pull-right"><a href="#tab-auth-register" data-toggle="tab" class="tsp_ajax_tab"><i class="fa fa-user"></i> Create Account</a></li>
</ul>
<div id="tsp-tab-content" class="tab-content">