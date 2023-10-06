<?php
namespace TravelSportsPro;

$cart = App::GetClient()->GetController('checkout')->GetCart();

if (!empty($cart['upsell_item_id'])) {
	$upsell_data = json_decode($cart['upsell_item_id'],true);
	if (!empty($upsell_data['STUDENT_ID']))
	{
		$upsell_student = App::GetClient()->GetController('members')->GetUserData($upsell_data['STUDENT_ID']);
	}
	if (isset($upsell_data['UP_SELL_ITEM_ID']) && !empty($upsell_data['UP_SELL_ITEM_ID'])) {
		$upsell_sales_item = App::GetClient()->GetController('checkout')->GetUpSell(array('id' => $upsell_data['UP_SELL_ITEM_ID']));
	}
}
$selected_account = $cart['selected_account'];
?>
<?php if(!empty($upsell_sales_item)): ?>
	<script>
	jQuery(function() {
		jQuery('#upsellModal').modal('show');
	});
	</script>
<?php endif; ?> 
<div id="tab-checkout-cart" class="tab-pane">
    <div class="page-header">
        <h2>Shopping Cart</h2>
    </div>
</div>
<?php if ($cart['list']) : ?>
<div id="cart-items-list">
	<div class="table-responsive">
        <table class="table table-striped">
            <?php foreach ($cart['list'] as $student ) : ?>
            <thead>		        
	        	<tr>
		        	<th colspan="8"><h4><?php echo $student['FIRSTNAME'];?> <?php echo $student['LASTNAME'];?></h4></th>
	        	</tr>
		        <tr>
		            <th>Item</th>
                    <th>&nbsp;</th>
		            <?php if (TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && false) : ?>
		            <th class="text-right">
			            Hours per  <?php if (TSP_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (TSP_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>
		            </th>
					<?php endif; ?>
					<th class="text-right" width="90">Price</th>
					<th class="text-center"></th>
                    <?php if (TSP_ENABLE_DISCOUNT_COUPONS == '1' || TSP_MAIN_DISCOUNT == 'MULTI_CLASS' || TSP_MULTI_STUDENT_ENABLED == '1') : ?>
		            <th class="text-right" width="90">Discount</th>
		            <th class="text-right" width="90">Subtotal</th>
		           <?php endif; ?>
		            <th class="text-right" width="90"><?php echo ((TSP_TAX_ENABLED == '1') ? 'Tax': ''); ?></th>
		            <th class="text-right" width="90"></th>
		        </tr>
		    </thead>
            <tbody>
            <?php foreach ($student['items'] as $k_item => $item) :?>
			<?php if (defined ('TSP_ENABLE_PAYMENT_ACCOUNT_2') && TSP_ENABLE_PAYMENT_ACCOUNT_2 == '1') $cart_locations[$item['location_id']] = $cart_locations[$item['location_id']]; ?>
                <?php if ($item['student_id']) : ?>
                    <tr id="tr-<?php echo $k_item; ?>">
			            <td colspan="2">
                            <?php if ($item['class']['CODE']) : ?>
                                <?php echo $item['class']['CODE']; ?><br><i class="text-muted"><?php echo $item['title']; ?><?php echo (($item['season']['NAME']) ? ' ('.$item['season']['NAME'].')' : ''); ?></i>
                            <?php else: ?>
                                <?php echo $item['title']; ?>
                            <?php endif; ?>
                            <?php echo (($item['wait_list'] == '1') ? '<div class="label label-warning">Wait List</div>' : ''); ?>
                            <?php if ($item['payment_plan']) : ?>
				            <p class="font-italic">
                                <i>
                                    <b>Payment Plan:</b><br>
                                    First Payment <?php echo TSP_CURRENCY_SIGN.$item['payment_plan']['FIRST_PAYMENT_AMOUNT'] ?>
                                    <?php if (!empty($item['payment_plan']['PAYMENT_PLAN_FEE'])): ?>
                                    &nbsp;plus <?php echo TSP_CURRENCY_SIGN.$item['payment_plan']['PAYMENT_PLAN_FEE'] ?> fee
                                    <?php endif; ?>
                                    <br>and <?php echo $item['payment_plan']['REPEATS'] ?> payment(s) <?php echo TSP_CURRENCY_SIGN.$item['payment_plan']['RECURRING_AMOUNT'] ?> <?php echo $item['payment_plan']['SCHEDULE_NAME'] ?>
                                <i>
                            </p>
				            <?php endif; ?>
                            <?php if ($item['selected_values']) : ?>
                                <?php if (!empty($item['selected_values']['jersey_size'])):?>
				            	Jersey Size: <?php echo $cart['special_categs']['jersey_sizes'][$item['selected_values']['jersey_size']]; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['jersey_number_1'])):?>
				            	Jersey Number 1 (Not Guaranteed): <?php echo $item['selected_values']['jersey_number_1']; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['jersey_number_2'])):?>
				            	Jersey Number 2 (Not Guaranteed): <?php echo $item['selected_values']['jersey_number_2']; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['pant_size'])):?>
				            	Pant Size: <?php echo $cart['special_categs']['pant_sizes'][$item['selected_values']['pant_size']]; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['pant_style'])):?>
				            	Pant Style: <?php echo $cart['special_categs']['pant_styles'][$item['selected_values']['pant_style']]; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['cap_size'])):?>
				            	Cap Size: <?php echo $cart['special_categs']['cap_sizes'][$item['selected_values']['cap_size']]; ?><br>
                                <?php endif; ?>
                                <?php if (!empty($item['selected_values']['bag_style'])):?>
				            	Bag Style: <?php echo $cart['special_categs']['bag_styles'][$item['selected_values']['bag_style']]; ?><br>
                                <?php endif; ?>
				            <?php endif; ?>
			            </td>
                        <?php if (TSP_MAIN_DISCOUNT == 'HOURLY_RATES'  && false) : ?>
                            <td class="text-right"><?php echo (($item['hours'] != 0) ? $item['hours'] : ''); ?></td>
                        <?php endif; ?>
                            <td class="text-right"><?= (!empty($item['price'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($item['price'],2); ?></td>
                            <td class="text-right" <?php echo (($item['discount_description'] != 0) ? 'width="270"' : ''); ?> ><?php echo $item['discount_description']; ?></td>
			            <?php if (TSP_ENABLE_DISCOUNT_COUPONS == '1' || TSP_MAIN_DISCOUNT == 'MULTI_CLASS' || TSP_MULTI_STUDENT_ENABLED == '1') : ?>
                            <td class="text-right"><?= (!empty($item['discount'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($item['discount'],2); ?></td>
                            <td class="text-right"><?= (!empty($item['subtotal'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($item['subtotal'],2); ?></td>
                        <?php endif; ?>
			            <td class="text-right"><?php echo ((TSP_TAX_ENABLED) ? TSP_CURRENCY_SIGN.$item['tax'] : ''); ?></td>
			            <td class="text-right">
				            <a class="btn btn-warning btn-sm select-class <?php echo (($item['remove'] == "1") ? 'tsp_ajax_tab' : ''); ?>" type="button"
                                href = '#tab-checkout-cart'
                                <?php echo (($item['remove'] != "1") ? 'disabled="disabled"' : ''); ?>
                                tsp_obj="checkout"
								tsp_method="DeleteCartItem"
				            	tsp_item_key="<?php echo $item['cart_item_key']; ?>"
				            	tsp_class_id="<?php echo $item['class_id']; ?>"
				            	tsp_student_id="<?php echo $item['student_id']; ?>"
				            	tsp_sales_item_id="<?php echo $item['sales_item_id']; ?>"
								>
				            	<span><i class="fa fa-minus-circle"></i> Remove</span>
				            </a> 
			            </td>
			        </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && TSP_CALCULATE_TOTALS_FOR == 'student' && false) : ?>
		        <tr>
		            <th class="text-right" colspan="2">Total  <?php echo $cart['total_hours'][$student['ID']]; ?> hour(s) per <?php if (TSP_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (TSP_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>, rate <?php echo TSP_CURRENCY_SIGN; ?> <?php echo $cart['hours_rate'][$student['ID']]; ?></th>
		            <th colspan="8"></th>
		        </tr>
                <?php elseif (TSP_MAIN_DISCOUNT == 'MULTI_CLASS_RATES' && TSP_CALCULATE_TOTALS_FOR == 'student' && $cart['class_rates'][$student['ID']]['classes'] != '') : ?>
		        <tr>
		            <th class="text-right" colspan="2"><?php echo $cart['class_rates'][$student['ID']]['classes']; ?> Regular Class(es) Rate <?php echo TSP_CURRENCY_SIGN; ?><?php echo $cart['class_rates'][$student['ID']]['rate']; ?></th>
		            <th colspan="8"></th>
		        </tr>
	        <?php endif; ?>
			<?php endforeach; ?>
			
			<?php if (TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && TSP_CALCULATE_TOTALS_FOR == 'family' && false) : ?>
				<tr>
		            <th class="text-right">Total <?php echo $cart['total_hours'][0]; ?> hour(s) per <?php if (TSP_HOURLY_TIME_RATES == 'WEEKLY') echo 'week'; elseif (TSP_HOURLY_TIME_RATES == 'MONTHLY') echo 'month'; ?>, rate <?php echo TSP_CURRENCY_SIGN; ?><?php echo $cart['hours_rate'][0]; ?></th>
		            <th colspan="8"></th>
		        </tr>
			<?php elseif (TSP_MAIN_DISCOUNT == 'MULTI_CLASS_RATES' && TSP_CALCULATE_TOTALS_FOR == 'family' && $cart['class_rates'][0]['classes'] != '') : ?>
		        <tr>
		            <th class="text-right"><?php echo $cart['class_rates'][0]['classes'];?> Regular Class(es) Rate <?php echo TSP_CURRENCY_SIGN; ?><?php echo $cart['class_rates'][0]['rate']; ?></th>
		            <th colspan="8"></th>
		        </tr>
			<?php endif; ?>
            </tbody>
			<tfoot>        
		        <tr class="no_bold">
		            <th class="text-right" colspan="2">Totals:</th>
					<?php echo ((TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && false) ? '<th class="text-right"></th>' : ''); ?>
		            <th class="text-right"><?= (!empty($cart['total_price'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($cart['total_price'],2); ?></th>
		            <th></th>
					<?php if (TSP_ENABLE_DISCOUNT_COUPONS == '1' || TSP_MAIN_DISCOUNT == 'MULTI_CLASS' || TSP_MULTI_STUDENT_ENABLED == '1') : ?>
		            <th class="text-right"><?= (!empty($cart['total_discount'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($cart['total_discount'],2); ?></th>
		            <th class="text-right"><?= (!empty($cart['subtotal'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($cart['subtotal'],2); ?></th>
		            <?php endif; ?>
		            <th class="text-right"><?php echo ((TSP_TAX_ENABLED == '1') ? number_format($cart['tax'],2) : ''); ?></th>
		            <th></th>
		        </tr>
                <?php if ($cart['convenience_fee'] > 0) : ?>
                <tr class="no_border_top">
                    <th class="text-right" colspan="2"></th>
                    <?php echo ((TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && false) ? '<th class="text-right"></th>' : ''); ?>
                    <th class="text-right" colspan="3"><?php echo $cart['convenience_fee_category']; ?></th>
		            <th class="text-right" ><?= (!empty($cart['convenience_fee'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($cart['convenience_fee'],2); ?></th>
                    <th></th>
                    <th></th>
                </tr>
                <tr class="no_border_top">
                    <th class="text-right" colspan="2"></th>
                    <?php echo ((TSP_MAIN_DISCOUNT == 'HOURLY_RATES' && false) ? '<th class="text-right"></th>' : ''); ?>
                    <th class="text-right" colspan="3">Total</th>
		            <th class="text-right" ><?= (!empty($cart['total'])) ? TSP_CURRENCY_SIGN : ''?><?php echo number_format($cart['total'],2); ?></th>
                    <th></th>
                    <th></th>
                </tr>
                <?php endif; ?>
		    </tfoot>
		</table>
	</div>
<?php if (TSP_TAX_ENABLED == '1' && $cart['tax'] > 0) : ?>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 text-right">Subtotal, <?php echo TSP_CURRENCY_SIGN; ?></label>
			    <label class="col-sm-4 text-right">
					<?php echo number_format($cart['subtotal'],2); ?>
			    </label>
		    </div>	
		</div>
	</div>
	<div class="row">
		<div class="col-md-offset-7 col-md-6 pt10">		
		    <div class="form-group">
			    <label class="col-sm-5 text-right">Tax <?php echo ((TSP_TAX_PERCENTAGE_VALUE > 0) ? TSP_TAX_PERCENTAGE_VALUE.'%' : ''); ?>, <?php echo TSP_CURRENCY_SIGN;?></label>
			    <label class="col-sm-4 text-right">
					<?php echo number_format($cart['tax'],2); ?>
			    </label>
		    </div>	
		</div>
	</div>
<?php endif; ?>
		 <?php include plugin_dir_path( __FILE__ ) . 'snippets/checkout.php'; ?>
<?php else: ?>
	<div class="alert alert-info">
		No items in cart
	</div>
<?php endif; ?>
<?php if(!empty($upsell_sales_item) && !empty($upsell_sales_item->data)): ?>
<!-- Modal -->
<div class="modal fade" id="upsellModal" tabindex="-1" role="dialog" aria-labelledby="myWaiver" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?php echo $upsell_sales_item->data->NAME; ?></h4>
			</div>
			<div class="modal-body">
				<?php echo $upsell_sales_item->data->DESCRIPTION; ?>
				<p><?php echo $upsell_student->FIRSTNAME ?> <?php echo $upsell_student->LASTNAME ?></p>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				<button class="btn btn-success select-product tsp_ajax_tab"  data-dismiss="modal" type="button"
						id="quant-<?php echo $upsell_sales_item->data->ID; ?>"
						tsp_obj="checkout" tsp_method="SubmitSalesItem"
						tsp_boot_tab="checkout-cart"
						tsp_student_id="<?php echo $upsell_data['STUDENT_ID']; ?>" 
						tsp_sales_item_id="<?php echo $upsell_sales_item->data->ID; ?>" tsp_quantity="1"
        				tsp_activation_date="<?php echo date("M j, Y"); ?>">
        				<i class="fa fa-shopping-cart"></i> <span>Add to Cart</span>
				</button>
			</div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<?php endif; ?> 