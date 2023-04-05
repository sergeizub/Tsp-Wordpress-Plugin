<?php if (TSP_ENABLE_DISCOUNT_COUPONS == "1") : ?>
	<?php if ($cart['discount_coupon']) : ?>
	        <div class="form-group">
		        <label class="col-sm-5 control-label">Applied Discount Coupon</label>
		        <label class="col-sm-4 control-label">
		            <i><?php echo $cart['discount_coupon']['COUPON_CODE']; ?>
		            <?php echo $cart['discount_coupon']['DISCOUNT_VALUE']; ?> (<?php echo (($cart['discount_coupon']['DISCOUNT_TYPE'] == 'percentage') ? '%' : TSP_CURRENCY_SIGN); ?>)</i>
		        </label>
		        <div class="col-sm-3">		            
		            <a href="#tab-checkout-cart" class="btn btn-warning tsp_ajax_tab" id="remove-discount" tsp_obj="checkout" tsp_method="RemoveDiscount"><i class="fa fa-minus-circle"></i> Remove</a>
		        </div>
	        </div>
			<?php else: ?>
		    <div class="form-group">
		        <label class="col-sm-5 control-label">Discount Coupon</label>
		        <div class="col-sm-4">
		            <input class="form-control" type="text" id="discount_coupon" name="discount_coupon" style="width:100px;">
		        </div>
		        <div class="col-sm-3">
		            <a href="#tab-checkout-cart" class="btn btn-success tsp_ajax_tab" id="apply-discount" tsp_obj="checkout" tsp_method="AddDiscount" tsp_discount_coupon = ""
					   onclick="this.setAttribute('tsp_discount_coupon', document.getElementById('discount_coupon').value);" >Apply Discount</a>
		        </div>
		    </div>
	<?php endif; ?>
<?php endif; ?>