<?php
namespace TravelSportsPro;
use \DateTime;
$result = App::GetClient()->GetController('members')->GetPayments();
?>
<h2 class="page-header">Payments</h2>
<div class="table-responsive">
<table class="table table-striped">
	<thead>
		<tr>
			<th width="150">Date</th>
			<th>Type</th>
			<th width="120">Check #</th>
			<th>Notes</th>
			<th width="90" style="text-align:right">Paid</th>
			<th class="text-right">Action</th>
		</tr>
	</thead>
	<tbody>
	<?php if (!empty($result->payments)) : ?>
		<?php foreach ($result->payments as $payment) : ?>
		<?php $payment_day = new DateTime($payment->DATE); ?>
		<tr>
			<td><?php echo $payment_day->format(TSP_PHPDATE); ?></td>
			<td><?php echo $payment->TYPE_NAME; ?></td>
			<td><?php echo $payment->RECEIPT_NUMBER; ?></td>
			<td><?php echo $payment->PAYMENT_NOTES; ?></td>
			<td style="text-align: right; padding-right: 10px;"><?php echo TSP_CURRENCY_SIGN; ?><?php echo number_format($payment->AMOUNT_PAID,2); ?></td>
			<td class="text-right"><a href="<?php echo get_option('tsp_api_url'); ?>client/index.php?obj=payment-receipt&controller=GetPaymentReceipt&payment_id=<?php echo $payment->ID; ?>" title="Payment Receipt" target="_blank"><i class="fa fa-file-pdf-o fa-lg"></i> Payment Receipt</a></td>
		</tr>
		<?php endforeach; ?>
		<?php else: ?>
			<td colspan="6">Payments not added</td>
		<?php endif; ?>
	</tbody>
</table>
</div>