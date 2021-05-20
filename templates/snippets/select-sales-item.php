<?php
namespace DanceStudioManager;
?>
<div class="row mt10">
			<div class="col-xs-4 col-md-2 text-right" style="padding-top: 5px !important;font-weight:bold;"><?=$student['FIRSTNAME']?> <?=$student['LASTNAME']?></div>
			<div class="col-xs-4 col-md-2 text-left">
                <?php include plugin_dir_path( __FILE__ ) . 'sales-item-quantity.php'; ?>
            </div>
			<div class="col-xs-4 col-md-8">
		        <a class="btn btn-success select-product dsm_ajax_tab" type="button" id="quant-<?=$sales_item['ID']?>-<?=$student['ID']?>"
                    href="#tab-checkout-sales-items-<?=$sales_item['ID']?>"
	            	dsm_obj="checkout" dsm_method="SubmitSalesItem" dsm_student_id="<?=$student['ID']?>" dsm_sales_item_id="<?=$sales_item['ID']?>" dsm_quantity="1"
	            	data-sales_item_id="<?=$sales_item['ID']?>"
	            	data-student_id="<?=$student['ID']?>">
	            	<i class="fa fa-shopping-cart"></i> <span>Add to Cart</span>
                
	            </a>
			</div>
		</div>
 