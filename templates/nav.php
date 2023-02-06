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
<ul class="nav nav-pills">
	    <?php if ($_SESSION['tsp_client_attrs']['default_tab'] == 'sales-items') : ?>
	    <li><a href="#tab-checkout-sales-items" data-toggle="tab"  class="tsp_ajax_tab default_tab"><i class="fa fa-cube"></i> <?php echo TSP_OC_SALES_ITEMS_SECTION_TITLE; ?></a></li>
		<?php else: ?>
		<li><a href="#tab-auth-login" data-toggle="tab" class="tsp_ajax_tab default_tab"><i class="fa fa-users"></i> Programs</a></li>
		<?php endif; ?>
	<li class="pull-right"><a href="#tab-auth-register" data-toggle="tab" class="tsp_ajax_tab"><i class="fa fa-user"></i> Create Account</a></li>
</ul>
<div id="tsp-tab-content" class="tab-content">