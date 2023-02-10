<?php
namespace TravelSportsPro;
$result = App::GetClient()->GetController('members')->GetCharges();
?>

<div id="tab-members-charges" class="tab-pane">
	<h2 class="page-header">Charges</h2>
	<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="150">Date</th>
				<th>Name</th>
				<th>Category</th>
				<th>Class</th>
				<th>Notes</th>
				<th style="text-align:right" width="90">Charged, <?php echo TSP_CURRENCY_SIGN; ?></th>
			</tr>
		</thead>
		<tbody>
		<? if (!empty($result->charges)) : ?>
			<? foreach ($result->charges as $charge) : ?>
			<tr>
				<td><?php echo $charge->DATE; ?></td>
				<td><?php echo $charge->NAME; ?></td>
				<td><?php echo $charge->CATEGORY_NAME; ?></td>
				<td><?php echo $charge->CLASS; ?></td>
				<td><?php echo $charge->CHARGE_NOTES; ?></td>
				<td><?php echo $charge->AMOUNT_CHARGED; ?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">Charges not added</td>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>
<?php if (!empty(TSP_OC_CHECKOUT_DISCLAIMER)): ?>
<div style="padding-top:20px"><? echo TSP_OC_CHECKOUT_DISCLAIMER; ?></div>
<?php endif; ?>