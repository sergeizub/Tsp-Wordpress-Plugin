<?php
	namespace DanceStudioManager;
?>
<script>
jQuery(function() {
    jQuery('.dsm_ajax_tab').click(function() {
		return dsm_ajax_click(this);
    });
	<?=App::GetClient()->NavRedirect();?>
});
</script>
<div>
<ul class="nav nav-pills">
		<?php if (DSM_OC_USE_CLASSES_LIST_VIEW == "1" || $_SESSION['dsm_client_attrs']['view'] == "List") : ?>
			<?php if (DSM_OC_CLASS_LIST_TYPE == 'list_by_program' || DSM_OC_CLASS_LIST_TYPE == 'list_by_program_table' || $_SESSION['dsm_client_attrs']['view'] == "List" ) : ?>
				<li><a href="#tab-classes-list" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
			<?php else : ?>
				 <li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
			
			<?php endif; ?>
		<?php else : ?>
			<li><a href="#tab-classes" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>
			
		 <?php //echo '<li><a href="#tab-classes-calendar" data-toggle="tab" class="dsm_ajax_tab default_tab"><i class="fa fa-users"></i> Classes</a></li>'; ?>
	<?php endif; ?>
	<?php if (DSM_OC_SHOPPING_CART_ENABLED  == '1') : ?>	
			<li>
				<a href="#tab-checkout-cart" data-toggle="tab" class="dsm_ajax_tab" ><i class="fa fa-shopping-cart"></i> Cart</a>
			</li>
	<?php endif; ?>
	<li class="dropdown" id="m-dd">
			<a href="#" data-toggle="dropdown"><i class="fa fa-users"></i> <?=App::GetClient()->GetController('members')->GetName();?><span class="caret"></span></a>
			<ul class="dropdown-menu">
				<li><a href="#tab-members-edit" class="dsm_ajax_tab"><i class="fa fa-users"></i> Profile</a></li>
				<li><a href="#tab-members-student" class="dsm_ajax_tab"><i class="fa fa-child"></i>  Add Related Student</a></li>
				<li><a href="#tab-members-charges" class="dsm_ajax_tab"><i class="fa fa-dollar"></i> Charges</a></li>
				<li><a href="#tab-members-purchases" class="dsm_ajax_tab"><i class="fa fa-shopping-cart"></i> Purchases</a></li>
				<?php if (DSM_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
				<li><a href="#tab-members-payments" class="dsm_ajax_tab"><i class="fa fa-credit-card"></i> Payments</a></li>
				<?php endif; ?>
				<li><a href="#tab-members-cards-accounts" class="dsm_ajax_tab"><i class="fa fa-credit-card"></i> Stored Cards</a></li>
			</ul>
	</li>
	<li><a href="#" data-toggle="tab"  dsm_obj="auth" dsm_method="Logout"  dsm_reload="true" class="dsm_ajax_tab"><i class="fa fa-sign-out"></i> Logout</a></li>
</ul>
</div>
<div id="dsm-tab-content" class="tab-content">