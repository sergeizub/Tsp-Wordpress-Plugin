<?php
namespace TravelSportsPro;

$user_data = json_decode(json_encode(App::GetClient()->GetController('members')->GetUserData()),true);
$related_students =  json_decode(json_encode(App::GetClient()->GetController('members')->GetChildList()),true);
$finance = $related_students['finance'];
?>
<div class="table-responsive">
	<table class="table table-striped">
	<thead>
	<tr>
		<th colspan="2">Name</th>
		<th>Address</th>		
		<th colspan="3" style="text-align:right">Amount</th>
	</tr>
	</thead>	
	<tbody>
	<tr>
		<td width="50" valign="top" colspan="2">
			<?php echo $user_data['FIRSTNAME']; ?> <?php echo $user_data['LASTNAME']; ?><br>
		</td>
		<td>
			<?php echo $user_data['ADDRESS']; ?>
			<br /><?php echo $user_data['CITY']; ?>, <?php echo $user_data['STATE']; ?> <?php echo $user_data['ZIP']; ?>
		</td>
		<td>
			&nbsp;
		</td>		
		<td width="130" class="text-right">
			<?php if ((!defined('TSP_OC_LEDGER_UNPAID_CHARGES_ONLY') || empty(TSP_OC_LEDGER_UNPAID_CHARGES_ONLY)) && (!defined('TSP_OC_SHOW_TOTAL_CHARGED') || TSP_OC_SHOW_TOTAL_CHARGED == '1')) : ?>
			Total&nbsp;Charged:&nbsp;<br />
			<?php endif; ?>
			<?php if ((!defined('TSP_OC_LEDGER_SHOW_PAYMENTS') || TSP_OC_LEDGER_SHOW_PAYMENTS == '1') && (!defined('TSP_OC_SHOW_TOTAL_PAID') || TSP_OC_SHOW_TOTAL_PAID == '1')) : ?>
			Total&nbsp;Paid:&nbsp;<br />
			<?php endif; ?>
			<?php if (!defined('TSP_OC_SHOW_BALANCE') || TSP_OC_SHOW_BALANCE == '1') : ?>
			<b>Balance:&nbsp;</b>
			<?php endif; ?>
			<br />
		</td> 
		<td style="width: 55px;" class="text-right">
			<?php if ((!defined('TSP_OC_LEDGER_UNPAID_CHARGES_ONLY') || empty(TSP_OC_LEDGER_UNPAID_CHARGES_ONLY)) && (!defined('TSP_OC_SHOW_TOTAL_CHARGED') || TSP_OC_SHOW_TOTAL_CHARGED == '1')) : ?>
			<?php echo TSP_CURRENCY_SIGN.number_format($finance['total_charged'],2);?><br />
			<?php endif; ?>
			<?php if ((!defined('TSP_OC_LEDGER_SHOW_PAYMENTS') || TSP_OC_LEDGER_SHOW_PAYMENTS == '1') && (!defined('TSP_OC_SHOW_TOTAL_PAID') || TSP_OC_SHOW_TOTAL_PAID == '1')) : ?>
			<?php echo TSP_CURRENCY_SIGN.number_format($finance['total_paid'],2);?><br />
			<?php endif; ?>
			<?php if (!defined('TSP_OC_SHOW_BALANCE') || TSP_OC_SHOW_BALANCE == '1') : ?>
			<b><span style="color:<?php if ($finance['balance']) echo 'red;'; else echo '#000;';?>"><?php echo TSP_CURRENCY_SIGN.number_format($finance['balance'],2);?></span></b>
			<?php endif; ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<br/><br/>
<?php include plugin_dir_path( __FILE__ ) . '../snippets/members-charges.php'; ?>
<?php if (!defined('TSP_OC_LEDGER_SHOW_PAYMENTS') || TSP_OC_LEDGER_SHOW_PAYMENTS == "1"): ?>
<br/><br/>
<?php include plugin_dir_path( __FILE__ ) . '../snippets/members-payments.php'; ?>
<?php endif; ?>
