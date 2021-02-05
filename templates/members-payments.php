<?php
namespace DanceStudioManager;
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
				<th style="text-align:right" width="90">Paid, <?=DSM_CURRENCY_SIGN?></th>
			</tr>
		</thead>
		<tbody>
		<? if (!empty($result->payments)) : ?>
			<? foreach ($result->payments as $payment) : ?>
			<tr>
				<td><?=$payment->DATE;?></td>
				<td><?=$payment->NAME;?></td>
				<td><?=$payment->TYPE_NAME;?></td>
				<td><?=$payment->RECEIPT_NUMBER;?></td>
				<td><?=$payment->PAYMENT_NOTES;?></td>
				<td><?=$payment->AMOUNT_PAID;?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">Payments not added</td>
		<? endif; ?>
		</tbody>
	</table>
	
	</div>
</div>