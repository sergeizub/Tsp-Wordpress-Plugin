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
				<th class="text-center">Total Lessons</th>
				<th class="text-center">Used Lessons</th>            
				<th class="text-center">Expiration Date</th>
				<th class="text-right">Charged, <?php echo TSP_CURRENCY_SIGN; ?></th>
			</tr>
		</thead>
		<tbody>
		
		<? if (!empty($result->data)) : ?>
			<? foreach ($result->data as $p) : ?>
			<tr>
				<td><?php echo $p->DATE_ADDEDF; ?></td>
				<td><?php echo $p->NAME; ?></td>
				<td class="text-center"><?php if($p->TYPE == 'item') { if($p->CLASS_TYPE == 'private') { echo $p->HOURS.' (hours)'; } else { if($p->LESSONS !== TSP_UNLIMITED_LESSONS) { echo $p->LESSONS; } else { echo 'unlimited';} } } ?></td>
				<td class="text-center"><?php if($p->TYPE == 'item') { if($p->CLASS_TYPE == 'private') { echo $p->USED_HOURS.' (hours)'; } else { echo $p->USED_LESSONS; } }?></td>
				<td class="text-center"><?php if($p->TYPE == 'item') { if($p->DATE_EXPIRE != TSP_NEVER_EXPIRE_DATE) { echo $p->DATE_EXPIREF; } else { echo 'never expire'; } }?></td>
				<td class="text-right"><?php echo $p->AMOUNT_CHARGED; ?></td>
			</tr>
			<? endforeach; ?>
		<? else: ?>
			<td colspan="6">No records found</td>
		<? endif; ?>
		</tbody>
	</table>
	</div>
</div>