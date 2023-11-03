<?php
namespace TravelSportsPro;

$items = App::GetClient()->GetController('checkout')->GetSalesItems();

$sales_item_id = App::GetApi()->GetIdParam();
if (defined('TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE') && TSP_OC_BUY_ITEM_PAGE_VIEW_TYPE == '1')
	$sales_products = $items['sales_items']['item'];
else
	$sales_products = $items['sales_items'];
$categories = $items['categories'];

if (!empty($_POST['category_id']))
	$selected_category_id = $_POST['category_id'];
else
	$selected_category_id = 0;

?>
<div id="tab-sales-items" class="tab-pane">
<?php if (empty($sales_item_id)) : ?>
	<?php if (empty($selected_category_id)) : ?>
	<h2 class="page-header">
		<?php echo (TSP_OC_SALES_ITEMS_SECTION_TITLE); ?>
	</h2>
	<?php endif; ?>
	<?php ?>
<?php if (!empty($sales_products)) : ?>
	<?php foreach ($sales_products as $category_id=>$products) : ?>
	<?php if((!empty($_SESSION['tsp_client_attrs']['si_category_id']) && $_SESSION['tsp_client_attrs']['si_category_id'] != $category_id) ||
				(!empty($selected_category_id) && $selected_category_id != $category_id)) continue;?>
	<h3><?php echo $categories[$category_id]; ?></h3>
	<?php foreach ($products as $product) {
			if (!empty($product['CATEGORY_DESCRIPTION'])) {
				echo '<h4>'. $product['CATEGORY_DESCRIPTION'] . '</h4>';
				break;
			}
			reset($products);
	} ?>
	<table class="table table-striped table-condensed table-hover">
		<thead>
			<tr>
				<th width="25%">Name</th>
				<th>Description</th>
				<th class="text-right">Price</th>
				<th width="10%"></th>
			</tr>
		</thead>
		<tbody>
		<?php foreach ($products as $product) : ?>
			<tr>
			<td><?php echo $product['NAME']; ?></td>
			<td>
				<?php echo $product['DESCRIPTION']; ?>
				<?php echo ((TSP_IGNORE_ITEMS_AVAILABLE_QUANTITY === '0' && $product['AVAILABLE_QUANTITY'] && $product['AVAILABLE_QUANTITY'] > 0) ? '<div class="label label-warning">Only '.$product['AVAILABLE_QUANTITY'].' items available</div>' : ''); ?>
			</td>
			<td class="text-right"><?php echo TSP_CURRENCY_SIGN;?><?php echo $product['PRICE'];?></td>
			<td class="text-right">
			<?php if (TSP_OC_SHOPPING_CART_ENABLED == '1') : ?>
				<?php if ($product['SALE_STARTED']) : ?>
				
					<?php if ($product['SALE_STARTED'] > 0 || TSP_IGNORE_ITEMS_AVAILABLE_QUANTITY == '1') : ?>	
						<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
							<a href="#tab-checkout-sales-items-<?php echo $product['ID']; ?>" title="Buy" class="btn btn-success tsp_ajax_tab"><i class="fa fa-shopping-cart"></i> Buy</a>
						<?php else: ?>
							<button tsp_sales-item_id="<?php echo $product['ID']; ?>" type="button" class="btn btn-success btn-login-alert"><i class="fa fa-shopping-cart"></i> Buy</button>
						<?php endif; ?>
					<?php else: ?>
						<div class="label label-default">Sold</div>
					<?php endif; ?>
				<?php else: ?>
					<div class="label label-warning">Sale starts <?php echo $product['SALE_START_DATE']; ?></div>
				<?php endif; ?>
			<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<?php endforeach; ?>
<?php else: ?>
	<div class="alert alert-warning">No records found</div>
<?php endif; ?>
<?php else: ?>
	<h3 class="page-header">Select item for student</h3>
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/sales-item-details.php'; ?>
	
	<br/><br/>
	<a type="button" class="btn btn-primary geturl checkout tsp_ajax_tab" href="#tab-checkout-cart"><i class="fa fa-shopping-cart"></i> Checkout</a>
<?php endif; ?>
</div>
<?php if (strpos($_SESSION['tsp_redirect']['boot_tab'],'checkout-sales-items-') !== false) unset($_SESSION['tsp_redirect']['boot_tab']); ?>