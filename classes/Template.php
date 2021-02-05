<?php
namespace DanceStudioManager;

class Template
{
    public function __construct()
    {
		
    }
	
	public function Load($file)
	{
		 if (file_exists(plugin_dir_path( __FILE__ ) . '../templates/'. $file )) {
			$tab = App::GetClient()->GetTab();
			?>
			<? if ($_REQUEST['type'] != 'json'): ?>
			<script>
				jQuery(function() {
				<? if ($tab): ?>
					jQuery('.nav-pills a[href="#<?=$tab?>"]').tab('show');
				<? else: ?>
					jQuery('.nav-pills a:first').tab('show');
				<? endif; ?>
				});
			</script>
			<div id="dsm_loading"><i class="fa fa-refresh fa-spin fa-3x"></i></div>
			<? endif; ?>
			<?
            load_template(plugin_dir_path( __FILE__ ) . '../templates/'. $file);
		 }
	}
}