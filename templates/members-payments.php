<?php
namespace TravelSportsPro;
$result = App::GetClient()->GetController('members')->GetPayments();
?>
<div id="tab-members-payments" class="tab-pane">
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/members-payments.php'; ?>
</div>
<?php if (!empty(TSP_OC_CHECKOUT_DISCLAIMER)): ?>
<div style="padding-top:20px"><?php echo TSP_OC_CHECKOUT_DISCLAIMER; ?></div>
<?php endif; ?>