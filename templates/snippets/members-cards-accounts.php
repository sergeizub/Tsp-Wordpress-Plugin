<?php
namespace TravelSportsPro;
$payment_sources = App::GetClient()->GetController('members')->GetCardsAccounts();
?>
<div id="tab-members-cards-accounts" class="tab-pane">
	<div class="tsp-header"></div>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th class="text-center">Default</th>
				<th>Card <?php echo ((defined('TSP_OC_ALLOW_ACH_PAYMENTS') && TSP_OC_ALLOW_ACH_PAYMENTS == 1) ? '/ ACH' : ''); ?></th>
				<th>Description</th>
				<th>Auto Payment</th>
				<?php if (TSP_OC_REMOVE_PAYMENT_SOURCE == '1') : ?>
				<th  class="text-center">Action</th>
				<?php endif; ?>
			</tr>
		</thead>
		<tbody>
		<?php if (!empty($payment_sources)) : ?>
			<?php foreach ($payment_sources as $payment_source) : ?>
			<tr>
				<td class="text-center">
						<input type="radio" name="default" value="<?php echo $payment_source['id']; ?>" class="tsp_ajax_tab"
						tsp_obj="gateway" tsp_method="SubmitDefault" tsp_id="<?php echo $payment_source['id']; ?>"
						<?php echo (($payment_source['default'] == '1') ? 'checked="checked"' : ''); ?>  tsp_boot_tab = "tab-gateway-finance"/>
				</td>
				<td><?php echo ucfirst($payment_source['card_type']); ?> **** **** <?php echo $payment_source['last4']; ?></td>
				<td><?php echo $payment_source['description']; ?></td>
				<td>
				<select name="auto_payment"  class="form-control" name="auto_payment"
						tsp_obj="gateway" tsp_method="SubmitAutopay" tsp_id="<?php echo $payment_source['id']; ?>" tsp_boot_tab = "tab-gateway-finance" tsp_auto_payment = "<?php echo $payment_source['auto_payment']; ?>"
						onchange="jQuery(this).attr('tsp_auto_payment',jQuery(this).val());tsp_ajax_click(this);">
					<option value="1" <?php echo (($payment_source['auto_payment'] == '1') ? 'selected="selected"' : ''); ?>>On</option>
					<option value="0" <?php echo (($payment_source['auto_payment'] == '0') ? 'selected="selected"' : ''); ?>>Off</option>
				</select>
				<?php if (TSP_OC_REMOVE_PAYMENT_SOURCE == '1') : ?>
				<td class="text-center">
					<a href="#tab-gateway-finance" tsp_obj="gateway" tsp_method="Delete"  tsp_id="<?php echo $payment_source['id']; ?>" onclick="if (confirm('Are you sure you want to delete Card/ACH?')) { tsp_ajax_click(this) };return false;" title="Delete Card/ACH" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i> Remove</a>
				</td>
				<?php endif; ?>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<td colspan="6">Accounts not added</td>
		<?php endif; ?>
		</tbody>
	</table>
	</div>
</div>