<?php
namespace TravelSportsPro;

$sales_item_id = App::GetApi()->GetIdParam();
$sales_item_full_info = App::GetClient()->GetController('checkout')->GetSalesItemInfo($sales_item_id);

if (defined('TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE') && TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE == '1')
	$sales_products = $sales_item_full_info['sales_items']['item'];
else
	$sales_products = $sales_item_full_info['sales_items'];
?>
<?php if (!empty($sales_item_id) && !empty($sales_products)) : ?>
<?php foreach ($sales_products as $category) : ?>

<?php foreach ($category as $sales_item) : ?>
<div>
		<h5><?php echo $sales_item['NAME']; ?></h5>
		<p><?php echo $sales_item['DESCRIPTION']; ?></p>
		<p>Price: <b><?php echo TSP_CURRENCY_SIGN; ?><?php echo $sales_item['PRICE']; ?></b></p>
		<br>
		<?php if ($sales_item['TYPE'] != 'gift_card'): ?>
		<?php foreach ($sales_item_full_info['students'] as $student): ?>
			<?php include plugin_dir_path( __FILE__ ) . 'select-sales-item.php'; ?>
		<?php endforeach; ?>
		<?php else: ?>
        <button class="btn btn-success select-product tsp_ajax_tab" type="button"
			id="quant-<?php echo $sales_item['ID']; ?>"
			tsp_obj="checkout" tsp_method="SubmitSalesItem"
			tsp_sales_item_id="<?php echo $sales_item['ID']; ?>" tsp_quantity="1"
        	tsp_activation_date="<?php echo date("M j, Y"); ?>">
        	<i class="fa fa-shopping-cart"></i> <span>Add to Cart</span>
		</button>
		<? endif; ?>
		<br>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>