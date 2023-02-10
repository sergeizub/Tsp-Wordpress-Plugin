<?php
namespace TravelSportsPro;
$result = App::GetClient()->GetController('members')->GetPayments();
?>
<div id="tab-members-payments" class="tab-pane">
	<h2 class="page-header">Payments</h2>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="150">Date</th>
				<th>Name</th>
				<th>Type</th>
				<th>Check #</th>
				<th>Notes</th>
				<th style="text-align:right" width="90">Paid, <?php echo TSP_CURRENCY_SIGN; ?></th>
			</tr>
		</thead>
		<tbody>
		<? if (!empty($result->payments)) : ?>
			<? foreach ($result->payments as $payment) : ?>
			<tr>
				<td><?php echo $payment->DATE; ?></td>
				<td><?php echo $payment->NAME; ?></td>
				<td><?php echo $payment->TYPE_NAME; ?></td>
				<td><?php echo $payment->RECEIPT_NUMBER; ?></td>
				<td><?php echo $payment->PAYMENT_NOTES; ?></td>
				<td><?php echo $payment->AMOUNT_PAID; ?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">Payments not added</td>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>
<?php if (!empty(TSP_OC_CHECKOUT_DISCLAIMER)): ?>
<div style="padding-top:20px"><? echo TSP_OC_CHECKOUT_DISCLAIMER; ?></div>
<?php endif; ?>