<?php
namespace DanceStudioManager;

$sales_item_id = App::GetApi()->GetIdParam();
$sales_item_full_info = App::GetClient()->GetController('checkout')->GetSalesItemInfo($sales_item_id);

?>
<?php if (!empty($sales_item_id) && !empty($sales_item_full_info['sales_items'])) : ?>
<?php foreach ($sales_item_full_info['sales_items']['item'] as $category) : ?>

<?php foreach ($category as $sales_item) : ?>
<div>
		<h5><?php echo $sales_item['NAME']; ?></h5>
		<p><?php echo $sales_item['DESCRIPTION']; ?></p>
		<p>Price: <b><?php echo DSM_CURRENCY_SIGN; ?><?php echo $sales_item['PRICE']; ?></b></p>
		<br>
		<?php foreach ($sales_item_full_info['students'] as $student): ?>
			<?php include plugin_dir_path( __FILE__ ) . 'select-sales-item.php'; ?>
		<?php endforeach; ?>
		<br>
</div>
<?php endforeach; ?>
<?php endforeach; ?>
<?php endif; ?>