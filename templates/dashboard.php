<?php
namespace TravelSportsPro;
$related_students =  App::GetClient()->GetController('members')->GetChildList();
?>
<div id="tab-dashboard" class="tab-pane">
    <div class="page-header">
        <h2>Dashboard</h2>
    </div>
	<h3>Your Players</h3>
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
			<table class="table table-striped">
			<thead>
				<tr>
					<th width="150">Player</th>
					<th>Program</th>
					<th>Team</th>
					<th width="90">Balance</th>
				</tr>
			</thead>
			<tbody>
			<?php $displayed_balance = false; ?>
			<? if (!empty($related_students->family)) : ?>
				<? foreach ($related_students->family as $student) : ?>
				<tr>
					<td><?php echo $student->FIRSTNAME.' '.$student->LASTNAME; ?></td>
					<td><?php echo implode(", ",(array)$student->CLASSES); ?></td>
					<td><?php echo implode(", ",(array)$student->TEAMS); ?></td>
					<?php if ($displayed_balance != true): ?>
					<td rowspan="<?php echo count($related_students->family); ?>" style="vertical-align:middle;"><?php echo TSP_CURRENCY_SIGN; ?><?php echo $related_students->finance->balance; ?></td>
					<?php $displayed_balance = true; ?>
					<?php endif; ?>
				</tr>
				<? endforeach; ?>
			<? else: ?>
				<td colspan="6">Players not added</td>
			<? endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="3">
						<?php if (!empty($related_students->finance->next_scheduled_payment) && !empty($related_students->finance->next_scheduled_payment->PAYMENT_DATE)) :?>
						Your next auto payment of <?php echo TSP_CURRENCY_SIGN.$related_students->finance->next_scheduled_payment->AMOUNT; ?> is schedule for
						<?php echo $related_students->finance->next_scheduled_payment->PAYMENT_DATE; ?>
						<?php endif; ?>
					</td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>


	</table>
	</div>
		</div>
	</div>
	
</div>