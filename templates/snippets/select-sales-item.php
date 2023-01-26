<?php
namespace TravelSportsPro;
?>
<div class="row mt10">
	<div class="col-xs-4 col-md-3 text-right" style="padding-top: 5px !important;font-weight:bold;"><?php echo $student['FIRSTNAME']; ?> <?php echo $student['LASTNAME']; ?></div>
	<div class="col-xs-4 col-md-4 text-left">
		<?php include plugin_dir_path( __FILE__ ) . 'sales-item-quantity.php'; ?>
	</div>
	<div class="col-xs-4 col-md-5">
		<a class="btn btn-success select-product tsp_ajax_tab" type="button" id="quant-<?php echo $sales_item['ID']; ?>-<?php echo $student['ID']; ?>"
                    href="#tab-checkout-sales-items-<?php echo $sales_item['ID']; ?>"
			tsp_obj="checkout" tsp_method="SubmitSalesItem" tsp_student_id="<?php echo $student['ID']; ?>" tsp_sales_item_id="<?php echo $sales_item['ID']; ?>" tsp_quantity="1"
	            	data-sales_item_id="<?php echo $sales_item['ID']; ?>"
	            	data-student_id="<?php echo $student['ID']; ?>">
	            	<i class="fa fa-shopping-cart"></i> <span>Add to Cart</span>
                
		</a>
	</div>
</div>
 