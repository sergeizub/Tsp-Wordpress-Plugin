<?php
namespace DanceStudioManager;

if(!empty($_SESSION['dsm_client_attrs']))
	$classes_list = App::GetClient()->GetController('classes')->GetClassesData((array)$_SESSION['dsm_client_attrs'] + (array)$_REQUEST['filter']);
else
	$classes_list = App::GetClient()->GetController('classes')->GetClassesData($_REQUEST['filter']);

foreach ($classes_list->groupclasses as $class) {
	if ($_SESSION['dsm_client_attrs']['class_code']) {
		$classes_tabs['class_code'][] =  $class;
		if (is_array($_SESSION['dsm_client_attrs']['class_code']))
			$programs['class_code'] = implode(", ",$_SESSION['dsm_client_attrs']['class_code']);
		else
			$programs['class_code'] = $_SESSION['dsm_client_attrs']['class_code'];
	}
	else {
		$classes_tabs[$class->PROGRAM_ID][] =  $class;
		$programs[$class->PROGRAM_ID] = $class->PROGRAM;
	}
}

//Sort By Location Asc
foreach ($classes_tabs as $k => $v_array) {
	usort($classes_tabs[$k], 'dsm_location_sort');
}
?>
<div id="tab-classes-list" class="tab-pane">
	<?php $filters = (!empty($classes_list->filters) ? $classes_list->filters : false); ?>
	<?php include plugin_dir_path( __FILE__ ) . 'snippets/class-filters.php'; ?>
<?php if (!empty($classes_tabs)): ?>
<div id="tabs">
	<ul class="nav nav-tabs" role="tablist">
        <?php foreach ($classes_tabs as $key => $item): ?>
		<?php reset($classes_tabs);?>
		<li role="presentation" class="<?=($key === key($classes_tabs) ? 'active' : '')?>">
			<a href="#tab<?=$key?>" aria-controls="<?=$programs[$key]?>"
				role="tab" data-toggle="tab" data-program_id="<?=$key?>"><?=$programs[$key]?></a>
		</li>
		<?php endforeach; ?>
	</ul>
</div>
<div class="tab-content classes-list">
		<?php foreach ($classes_tabs as $key => $item): ?>
			<?php reset($classes_tabs);?>
			<div role="tabpanel" class="tab-pane <?=($key === key($classes_tabs) ? 'active' : '')?>" id="tab<?=$key?>">
			<div class="tab-content">
			<div class="get-page" data-relation="categories" data-relation_id="<?=$key?>"></div>
			<?php
				include plugin_dir_path( __FILE__ ) . 'snippets/select-class-table.php';
			?>
			</div>
			</div>
		<?php endforeach; ?>
</div>
</div>
<?php else: ?>
	<h4>No classes scheduled at this time</h4>
<?php endif; ?>