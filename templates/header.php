<div id="tsp_content">
<script>
	var dtp_date = 'MMM D, YYYY';
	<?php if (get_option('tsp_random_url_parameter') == '1'): ?>
	const tsp_urlParams = new URLSearchParams(window.location.search);
	if (!tsp_urlParams.has('qrnd')) {
		const tsp_qrnd = 'qrnd=' + Math.random().toString(36).substring(2,18);
		if(window.location.href.indexOf('?') != -1) {
			window.location.href = window.location.href + '&' + tsp_qrnd;
		}
		else {
			window.location.href = window.location.href + '?' + tsp_qrnd;
		}
	}
	<?php endif; ?>
</script>
