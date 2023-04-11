<?php
namespace TravelSportsPro;
$scheduled_payments =  App::GetClient()->GetController('members')->GetScheduledPayments();
//var_dump($scheduled_payments);
?>
<script>
	jQuery(function() {
		jQuery("#scheduled-payments").dataTable({
			"bFilter":false,
			"stateSave":false,
			"lengthMenu":[20,40,60],
			"columnDefs":[{target:0,visible: false,searchable: false},{target:2,className:"text-right"}],
			"order":[0,'asc'],
			"dom":"<i><t><lp>"
		});
	});
</script>
<div class="table-responsive-xl">
	<div class="tsp-header"></div>
	<table id="scheduled-payments" class="table table-striped display autorefresh" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>&nbsp;</th>
			    <th>Date</th>
			    <th>Purchase</th>
			    <th>Amount</th>
			    <th>Payment Method</th>
			    <th>Status</th>
			    <th>&nbsp;</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!empty($scheduled_payments->scheduled_payments)) : ?>
			<?php foreach($scheduled_payments->scheduled_payments as $sp) :?>
			<tr>
				<td><?php echo strtotime($sp->PAYMENT_DATE); ?></td>
				<td><?php echo $sp->PAYMENT_DATE;?></td>
				<td>
				<?php
				if ($sp->PURCHASE_ID > 0 || $sp->ORDER_ID > 0 || $row->SALES_ITEM_ID > 0)
					echo $sp->PURCHASE;
				else if(!empty($sp->CHARGE))
					echo 'Charge '.$sp->CHARGE;
				else
					echo 'Account Balance';
				?>
				</td>
				<td><?php echo TSP_CURRENCY_SIGN.number_format($sp->AMOUNT,2); ?></td>
				<td><?php echo $sp->PAYMENT_METHOD; ?></td>
				<td><?php echo App::GetClient()->GetController('members')->GetScheduledPaymentsStatusLabel($sp->SCHEDULED_PAYMENT_STATUS); ?></td>
				<td>
                    <?php if (in_array($sp->SCHEDULED_PAYMENT_STATUS, array("1","3"))): ?>
                    <a href="#tab-gateway-finance"  tsp_obj="checkout" tsp_method="PayScheduledPayment" tsp_id="<?php echo $sp->ID; ?>"  title="Pay Now" class="btn btn-danger"
                        onclick="if (confirm('Are you sure you want to process payment?')) { tsp_ajax_click(this); } return false;" >Pay Now</a>
                    <?php endif; ?>
                </td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
		</tbody>	
		<tfoot>
			<tr>
				<th>&nbsp;</th>
			    <th>Date</th>
			    <th>Purchase</th>
			    <th>Amount</th>
			    <th>Payment Method</th>
			    <th>Status</th>
			    <th>&nbsp;</th>
			</tr>
		</tfoot>
	</table>
</div>