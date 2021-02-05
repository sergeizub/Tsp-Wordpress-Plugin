<?php
namespace DanceStudioManager;
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
				<th style="text-align:right" width="90">Charged, <?=DSM_CURRENCY_SIGN?></th>
			</tr>
		</thead>
		<tbody>
		<? if (!empty($result->charges)) : ?>
			<? foreach ($result->charges as $charge) : ?>
			<tr>
				<td><?=$charge->DATE;?></td>
				<td><?=$charge->NAME;?></td>
				<td><?=$charge->CATEGORY_NAME;?></td>
				<td><?=$charge->CLASS;?></td>
				<td><?=$charge->CHARGE_NOTES;?></td>
				<td><?=$charge->AMOUNT_CHARGED;?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">Charges not added</td>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>