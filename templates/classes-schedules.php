#tab-classes<?php
namespace DanceStudioManager;

$class_id = App::GetApi()->GetIdParam();
$schedules_list = App::GetClient()->GetController('classes')->GetClasses();	
	?>
	<button class="btn btn-default dsm_ajax_tab"  dsm_boot_tab="classes" >Classes</button>
	<?
foreach ($schedules_list->schedules as $k => $v) {
	foreach ($v->data as $data)
		if ($data->CLASS_ID == $class_id) {
			if ($class_header != 1) :
			?>
                <div class="schedule" style="border-left: 30px solid silver;">
					<h3>
						<?=$data->CODE;?>
						<?=$data->NAME;?>
						<?=$data->LEVEL;?>
					</h3>
					<p>Age: <?=$data->MIN_AGE;?> - <?=$data->MAX_AGE;?></p>
					<h4><?=$data->LOCATION;?></h4>
				</div>
			<?
				$class_header = '1';
			endif;
			if ($schedules_show == 'only_future' && strtotime($data->END) < time())
				continue;
			$start_date = date("M j, Y",strtotime($data->START));
			$end_date = date("M j, Y",strtotime($data->END));
			$start_time = date("g:i A",strtotime($data->START));
			$end_time = date("g:i A",strtotime($data->START));
		?>
		<div class="schedule" style="border-left: 30px solid silver;">
			<h4><?=$start_date;?> <?= (!empty($end_date) && $start_date != $end_date) ? '- '.$end_date : ''; ?>
				<small><?=($start_time.' - '.$end_time);?></small>
			</h4>
		</div>
		<?php
	}
}