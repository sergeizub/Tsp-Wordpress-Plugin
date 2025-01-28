<?php
namespace TravelSportsPro;
?>
<script>
jQuery(function() {
    <?php if (TSP_OC_ALLOW_CLASS_REG_PURCH_ITEMS == "1") : ?>
	InitClassRegWithPurchesdItem();	
	<?php endif;?>
});
</script>
<div id="tab-classes-list" class="tab-pane">
    <div class="page-header">
        <h2>Class Registration</h2>
    </div>
<?php include plugin_dir_path( __FILE__ ) . 'snippets/class-details.php'; ?>
<br/><br/>
<a type="button" class="btn btn-primary geturl checkout tsp_ajax_tab" onclick="jQuery('.cart-checkout-tab').tab('show');" href="#tab-checkout-cart"><i class="fa fa-shopping-cart"></i> Checkout</a>
</div>