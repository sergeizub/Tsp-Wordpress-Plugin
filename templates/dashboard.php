<?php
namespace TravelSportsPro;
$related_students =  App::GetClient()->GetController('members')->GetChildList();
$scheduled_payments =  App::GetClient()->GetController('members')->GetScheduledPayments(array("interval" => "future","status" => "1,3"));
$statuses = ['1'=>'Scheduled', '2'=>'Complete', '3'=>'Processing Error', '4'=>'On Hold', '5'=>'Terminated', '6'=>'Processing'];
?>
<div id="tab-dashboard" class="tab-pane">
    <div class="page-header">
        <h2>Dashboard</h2>
    </div>
	<div class="row">
		<div class="col-md-6">
            <h3>Your Player (s)</h3>
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
				<td colspan="3">Players Not Added</td>
                <?php if ($displayed_balance != true): ?>
					<td style="vertical-align:middle;"><?php echo TSP_CURRENCY_SIGN; ?><?php echo $related_students->finance->balance; ?></td>
				<?php $displayed_balance = true; ?>
                <? endif; ?>
			<? endif; ?>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="4">
                        <?php $next_scheduled_payment = $related_students->finance->next_scheduled_payment; ?>
                        <?php if (!empty($next_scheduled_payment) && !empty($next_scheduled_payment->PAYMENT_DATE)) :?>
						Your next auto payment of <?php echo TSP_CURRENCY_SIGN.$next_scheduled_payment->AMOUNT; ?> is schedule for
						<?php echo $next_scheduled_payment->PAYMENT_DATE; ?>
                        <a href="#tab-dashboard"  tsp_obj="checkout" tsp_method="PayScheduledPayment" tsp_id="<?php echo $next_scheduled_payment->ID; ?>"  title="Pay Now" class="btn btn-danger pull-right"
                           onclick="if (confirm('Are you sure you want to process payment?')) { tsp_ajax_click(this); } return false;" >Pay Now</a>
						<?php endif; ?>
					</td>
				</tr>
			</tfoot>
            </table>
            </div>
		</div>
        <div class="col-md-6">
            <h3>Billing / Dues</h3>
			<div class="table-responsive">
			<table class="table table-striped">
			<thead>
				<tr>
					<th width="150">Due Date</th>
					<th>Amount</th>
					<th>Method</th>
                    <th>Status</th>
                    <th>&nbsp;</th>
				</tr>
			</thead>
			<tbody>
			<? if (!empty($scheduled_payments->scheduled_payments)) : ?>
				<? foreach ($scheduled_payments->scheduled_payments as $payment) : ?>
				<tr>
					<td><?php echo $payment->PAYMENT_DATE; ?></td>
					<td><?php echo TSP_CURRENCY_SIGN; ?><?php echo $payment->AMOUNT; ?></td>
					<td><?php echo $payment->PAYMENT_METHOD; ?></td>
                    <td><?php echo $statuses[$payment->SCHEDULED_PAYMENT_STATUS]; ?></td>
                    <td>
                        <?php if (in_array($payment->SCHEDULED_PAYMENT_STATUS, array("1","3"))): ?>
                        <a href="#tab-dashboard"  tsp_obj="checkout" tsp_method="PayScheduledPayment" tsp_id="<?php echo $payment->ID; ?>"  title="Pay Now" class="btn btn-danger"
                           onclick="if (confirm('Are you sure you want to process payment?')) { tsp_ajax_click(this); } return false;" >Pay Now</a>
                        <?php endif; ?>
                    </td>
				</tr>
				<? endforeach; ?>
			<? else: ?>
				<td colspan="6">Payments Not Scheduled</td>
			<? endif; ?>
			</tbody>
            </table>
            </div>
		</div>
	</div>
</div>