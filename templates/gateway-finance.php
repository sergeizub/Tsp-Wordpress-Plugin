<?php
namespace TravelSportsPro;
?>
<div id="tab-gateway-finance" class="tab-pane">
	<div class="page-header">
        <h2>Finance</h2>
    </div>
<div id="tabs">
	<ul class="nav nav-tabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#tab-ledger" role="tab" data-toggle="tab" aria-controls="Ledger">Ledger</a>
		</li>
		<?php if (defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") : ?>
		<li role="presentation">
			<a href="#tab-gateway-card" role="tab" data-toggle="tab" aria-controls="Add Card">Add Card</a>
		</li>
		<?php endif; ?>
		<?php if (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1") : ?>
		<li role="presentation">
			<a href="#tab-gateway-account" role="tab" data-toggle="tab" aria-controls="Add Bank Account (ACH)">Add Bank Account (ACH)</a>
		</li>
		<?php endif; ?>
		<?php if ((defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") || (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1")) : ?>
		<li role="presentation">
			<a href="#tab-members-cards-accounts" role="tab" data-toggle="tab" aria-controls="Stored Cards">
				Stored <?php if (defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") echo 'Cards '; ?>
				<?php if ((defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") || (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1")) echo 'and '; ?>
				<?php if (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1") echo 'Bank Accounts (ACH)'; ?>
			</a>
		</li>
		<?php endif; ?>
		<?php if (defined('TSP_OC_DISPLAY_SCHEDULED_PAYMENTS') && TSP_OC_DISPLAY_SCHEDULED_PAYMENTS == "1") : ?>
		<li role="presentation">
			<a href="#tab-scheduled-payments" aria-controls="Scheduled Payments" role="tab" data-toggle="tab">Scheduled Payments</a>
		</li>
		<?php endif; ?>
	</ul>
</div>
<div class="tab-content finance-tabs">
	<div role="tabpanel" class="tab-pane active" id="tab-ledger">
		<?php include plugin_dir_path( __FILE__ ) . 'snippets/ledger.php'; ?>
	</div>
	<?php if (defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") : ?>
	<div class="tab-pane" id="tab-gateway-card">
		<?php include plugin_dir_path( __FILE__ ) . 'snippets/gateway-card.php'; ?>
	</div>
	<?php endif; ?>
	<?php if (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1") : ?>
	<div role="tabpanel" class="tab-pane" id="tab-gateway-account">
		<?php include plugin_dir_path( __FILE__ ) . 'snippets/gateway-account.php'; ?>
	</div>
	<?php endif; ?>
	<?php if ((defined('TSP_OC_ALLOW_CARD_PAYMENTS') && TSP_OC_ALLOW_CARD_PAYMENTS == "1") || (defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == "1")) : ?>
	<div role="tabpanel" class="tab-pane" id="tab-members-cards-accounts">
		<?php include plugin_dir_path( __FILE__ ) . 'snippets/members-cards-accounts.php'; ?>
	</div>
	<?php endif; ?>
	<?php if (defined('TSP_OC_DISPLAY_SCHEDULED_PAYMENTS') && TSP_OC_DISPLAY_SCHEDULED_PAYMENTS == "1") : ?>
	<div role="tabpanel" class="tab-pane" id="tab-scheduled-payments">
		<?php include plugin_dir_path( __FILE__ ) . 'snippets/members-scheduled-payments.php'; ?>
	</div>
	<?php endif; ?>
</div>

<?php if (!empty(TSP_OC_CHECKOUT_DISCLAIMER)): ?>
<div style="padding-top:20px"><?php echo TSP_OC_CHECKOUT_DISCLAIMER; ?></div>
<?php endif; ?>
</div>