<?php
namespace TravelSportsPro;
$related_students =  App::GetClient()->GetController('members')->GetChildList();
$scheduled_payments =  App::GetClient()->GetController('members')->GetScheduledPayments(array("interval" => "future","status" => "1,3"));
$cart = App::GetClient()->GetController('checkout')->GetCart();

$payment_form = App::GetClient()->GetController('gateway')->PaymentForm();

if (!empty($payment_form->form) && !empty($payment_form->form->charges_list)) {
	$unpaid_charges_list = $payment_form->form->charges_list;

	//Compare unpaid charges with scheduled payments charges, if have same ids remove it from unpaid
	$scheduled_charges = array();
	if (isset($scheduled_payments->scheduled_payments) && is_array($scheduled_payments->scheduled_payments))
		foreach ($scheduled_payments->scheduled_payments as $key => $value)
			if (isset($value->CHARGE_ID) && !empty($value->CHARGE_ID))
				array_push($scheduled_charges, $value->CHARGE_ID);
	
	if (!empty($scheduled_charges) && is_array($unpaid_charges_list))
		foreach ($unpaid_charges_list as  $key => $unpaid) 
			if (in_array($unpaid->id, $scheduled_charges))
				unset($unpaid_charges_list[$key]);
		
}

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
			<?php if (!empty($related_students->family)) : ?>
				<?php foreach ($related_students->family as $student) : ?>
				<tr>
					<td><?php echo $student->FIRSTNAME.' '.$student->LASTNAME; ?></td>
					<td><?php echo implode(", ",(array)$student->CLASSES); ?></td>
					<td><?php echo implode(", ",(array)$student->TEAMS); ?></td>
					<?php if ($displayed_balance != true): ?>
					<td rowspan="<?php echo count($related_students->family); ?>" style="vertical-align:middle;"><?php echo TSP_CURRENCY_SIGN; ?><?php echo $related_students->finance->balance; ?></td>
					<?php $displayed_balance = true; ?>
					<?php endif; ?>
				</tr>
				<?php endforeach; ?>
			<?php else: ?>
				<td colspan="3">Players Not Added</td>
                <?php if ($displayed_balance != true): ?>
					<td style="vertical-align:middle;"><?php echo TSP_CURRENCY_SIGN; ?><?php echo $related_students->finance->balance; ?></td>
				<?php $displayed_balance = true; ?>
                <?php endif; ?>
			<?php endif; ?>
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
			<?php if (!empty($cart) && isset($cart['list']) && is_array($cart['list'])): ?>
				<?php foreach($cart['list'] as $student): ?>
					<?php if (isset($student['items']) && is_array($student['items'])): ?>
						<?php foreach($student['items'] as $item): ?>
					<tr>
						<td><?php echo date(TSP_PHPDATE); ?></td>
						<td><?php echo TSP_CURRENCY_SIGN; ?><?php echo $item['subtotal']; ?></td>
						<td>&nbsp;</td>
						<td>In Cart</td>
						<td><a href="#tab-checkout-cart" class="tsp_ajax_tab geturl btn btn-danger" title="Pay Now">Pay Now</a></td>
					</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (isset($unpaid_charges_list) && !empty($unpaid_charges_list)): ?>
				<?php foreach($unpaid_charges_list as $charge): ?>
					<?php if ($charge->id == 'convenience_fee') continue; ?>
					<tr>
						<td><?php echo date(TSP_PHPDATE); ?></td>
						<td><?php echo TSP_CURRENCY_SIGN; ?><?php echo $charge->amount; ?></td>
						<td>&nbsp;</td>
						<td>Not Paid</td>
						<td><a href="#tab-gateway-finance" tsp_sub_tab="#tab-pay" class="tsp_ajax_tab geturl btn btn-danger" title="Pay Now">Pay Now</a></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (!empty($scheduled_payments->scheduled_payments)) : ?>
				<?php foreach ($scheduled_payments->scheduled_payments as $payment) : ?>
				<tr>
					<td><?php echo $payment->PAYMENT_DATE; ?></td>
					<td><?php echo TSP_CURRENCY_SIGN; ?><?php echo $payment->AMOUNT; ?></td>
					<td><?php echo $payment->PAYMENT_METHOD; ?></td>
                    <td><?php echo App::GetClient()->GetController('members')->GetScheduledPaymentsStatusLabel($payment->SCHEDULED_PAYMENT_STATUS); ?></td>
                    <td>
                        <?php if (in_array($payment->SCHEDULED_PAYMENT_STATUS, array("1","3"))): ?>
                        <a href="#tab-dashboard"  tsp_obj="checkout" tsp_method="PayScheduledPayment" tsp_id="<?php echo $payment->ID; ?>"  title="Pay Now" class="btn btn-danger"
                           onclick="if (confirm('Are you sure you want to process payment?')) { tsp_ajax_click(this); } return false;" >Pay Now</a>
                        <?php endif; ?>
                    </td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if (!empty($scheduled_payments->scheduled_payments) && (empty($cart) || empty($cart['list']))): ?>
				<td colspan="6">Payments Not Scheduled</td>
			<?php endif; ?>
			</tbody>
            </table>
            </div>
		</div>
	</div>
</div>