<script>
	if (typeof(jQuery.fn.modal) === "undefined") {
		document.write("<script src='https://clients.dancestudiomanager.com/libs/bootstrap-3.3.7/js/bootstrap.min.js'><\/script>");
	}
    if (!window.moment) {
        document.write('<script src="https://clients.dancestudiomanager.com/libs/moment/2.20.1/moment.min.js"><\/script>');
    }
    if (window.err1 === 1) {
        LoadLocalCSS("https://clients.dancestudiomanager.com/libs/bootstrap-3.3.7/css/bootstrap.min.css");
    }
    if (window.err2 === 1) {
        LoadLocalCSS("https://clients.dancestudiomanager.com/libs/font-awesome-4.3.0/css/font-awesome.min.css");
    }
	var dtp_date = 'MMM D, YYYY';
</script>
