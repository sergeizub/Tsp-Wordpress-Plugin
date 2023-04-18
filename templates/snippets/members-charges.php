<?php
namespace TravelSportsPro;
$result = App::GetClient()->GetController('members')->GetCharges();
?>
<h2 class="page-header">Charges</h2>
<div class="table-responsive">
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="150">Date</th>
				<th>Program</th>
				<th>Notes</th>
				<th width="90" style="text-align:right">Charged</th>
				<th width="90" style="text-align:right">Paid</th>
				<th width="90" style="text-align:right">Balance</th>
			</tr>
		</thead>
		<tbody>
			
		<?php if (!empty($result->charges)) : ?>
			<?php foreach ($result->charges as $charge) : ?>
			<tr>
				<td><?php echo $charge->DATE; ?></td>
				<td><?php echo (!empty($charge->CLASS) ? $charge->CLASS : $charge->SEASON); ?></td>
				<td>
				<?php if ($charge->CHARGE_CATEGORY == TSP_CONVENIENCE_FEE_CATEGORY): ?>
				<?php echo $charge->CATEGORY_NAME; ?>
				<?php else: ?>
				<?php echo $charge->CHARGE_NOTES; ?>
				<?php endif; ?>
				</td>
				<td style="text-align:right"><?php echo TSP_CURRENCY_SIGN; ?><?php echo number_format($charge->AMOUNT_CHARGED,2); ?></td>
				<td style="text-align:right"><?php echo TSP_CURRENCY_SIGN; ?><?php echo number_format($charge->AMOUNT_PAID,2); ?></td>
				<td style="text-align:right"><?php echo TSP_CURRENCY_SIGN; ?><?php echo number_format((float)$charge->AMOUNT_CHARGED-(float)$charge->AMOUNT_PAID,2); ?></td>
			</tr>
			<?php endforeach; ?>
		<?php else: ?>
			<td colspan="6">Charges not added</td>
		<?php endif; ?>
		</tbody>
	</table>
</div>