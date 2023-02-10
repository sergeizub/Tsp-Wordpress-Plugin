<?php
namespace TravelSportsPro;
$result = App::GetClient()->GetController('members')->GetPurchases();
?>
<div id="tab-members-purchases" class="tab-pane">
	<h2 class="page-header">Purchases</h2>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th>Date</th>
				<th>Item</th>
				<th class="text-right">Charged</th>
			</tr>
		</thead>
		<tbody>
		
		<? if (!empty($result->data)) : ?>
			<? foreach ($result->data as $p) : ?>
			<tr>
				<td><?php echo $p->DATE_ADDEDF; ?></td>
				<td><?php echo $p->NAME; ?></td>
				<td class="text-right"><?php echo TSP_CURRENCY_SIGN.$p->AMOUNT_CHARGED; ?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">No records found</td>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>
<?php if (!empty(TSP_OC_CHECKOUT_DISCLAIMER)): ?>
<div style="padding-top:20px"><? echo TSP_OC_CHECKOUT_DISCLAIMER; ?></div>
<?php endif; ?>