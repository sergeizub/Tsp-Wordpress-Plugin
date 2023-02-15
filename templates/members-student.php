<?php
namespace TravelSportsPro;
use \DateTime;
$student = array();

$student_id = App::GetApi()->GetIdParam();

if (!empty($student_id)) {
	$student = App::GetClient()->GetController('members')->GetUser($student_id);
	if(!empty($student))
			$student_data = json_decode(json_encode($student->data),true);
			
}
else {
	$student =  App::GetClient()->GetController('members')->GetStudentForm();
}
?>
<script>
	jQuery(function() {
		InputDateInit();
		<?php if (!empty($student_id) && TSP_ALLOW_MEMBER_PHOTO == "1" && TSP_OC_ALLOW_MEMBER_PHOTO == "1"): ?>
		PhotoUploaderInit();
		<?php endif; ?>
	});
</script>
<? if (is_array($student->form)) :
	?>
	<div id="<?php echo ((!empty($student_id) ? 'members_edit_'.$student_id : 'tab-members-student')); ?>" class="<?php echo (!empty($student_id) ? '' : 'tab-pane'); ?>">
	<div class="tsp-header"><h2><?php echo (!empty($student_id) ? 'Edit' : 'Add Related'); ?> Player</h2></div>
	<?php if (TSP_REGISTRATION_FEE_ENABLED && TSP_REGISTRATION_MAX) : ?>
        <div class="alert alert-warning text-center">
            Registering first <?php echo TSP_REGISTRATION_MAX; ?> student(s) will cost <?php echo TSP_CURRENCY_SIGN; ?><?php echo TSP_REGISTRATION_FEE; ?> each. <?php echo ((TSP_REGISTRATION_FEE_OVER_MAX == 0 && TSP_REGISTRATION_FAMILY_FEE > 0) ? 'Max family registration fee '.TSP_CURRENCY_SIGN.TSP_REGISTRATION_FAMILY_FEE : 'All other students will cost '.TSP_CURRENCY_SIGN.TSP_REGISTRATION_FEE_OVER_MAX.' each.'); ?>
        </div>
	<?php elseif (TSP_REGISTRATION_FEE_ENABLED && TSP_REGISTRATION_MAX == "0") : ?>
		<div class="alert alert-warning text-center">
            Registering students will cost <?php echo TSP_CURRENCY_SIGN; ?><?php echo TSP_REGISTRATION_FEE; ?>.
        </div>
    <? endif; ?>
	<form class="form-horizontal" role="form" id="student-form" action="" method="post">
	<?php if (!empty($student_id) && TSP_ALLOW_MEMBER_PHOTO == "1" && TSP_OC_ALLOW_MEMBER_PHOTO): ?>
	<div class="form-group row">
		<div class="col-sm-3">&nbsp;</div>
		<div class="col-sm-3">
			<?php if (!empty($student_data['photo'])): ?>
				<img width="160" id="photo-image-<?php echo $student_id;?>" src="<?php echo get_option('tsp_api_url').'clients/'.$student_data['photo'];?>?t=<?php echo time(); ?>" alt="" class="img-thumbnail" />
			<?php else: ?>
				<img width="160" id="photo-image-<?php echo $student_id;?>" src="" alt="" class="img-thumbnail d-none" />
			<?php endif; ?>
		</div>
	</div>
	<div class="form-group">
		<label class="col-sm-3 control-label">Photo</label>
		<div class="col-sm-6">
			<input type="file" name="photo" class="photo-input d-none" tsp_student_id="<?php echo $student_id; ?>" tsp_method="SubmitPhoto">
			<button class="btn btn-info browse-photo" type="button" tsp_student_id="<?php echo $student_id; ?>" ><i class="fa fa-search"></i> Browse</button>
			<button class="btn btn-danger delete-photo <?php if (empty($student_data['photo'])): ?>d-none<?php endif; ?>" type="button" tsp_obj="members" tsp_method="DeletePhoto" tsp_student_id="<?php echo $student_id; ?>"
			href="#tab-members-edit-<?php echo $student_id;?>"
			onclick="if (confirm('Are you sure you want to delete photo?')) { tsp_ajax_click(this) };return false;">
				<i class="fa fa-trash"></i> Delete Photo
			</button>
			
		</div>
	</div>
	<? endif; ?>
	<?
		foreach ($student->form as $field) {
			echo '<div class="form-group">
					<label class="col-sm-3 control-label">
					'.((isset($field->required) && $field->required == true) ? '<span style="color: red;">*</span>' : '').' 
					'.$field->label.'</label>
						<div class="col-sm-6">';
					
			switch ($field->type) {
				case "select":
					echo '<select name="'.$field->name.'" class="form-control">';
					if (is_array($field->values))
						foreach ($field->values as $v)
							echo '<option value="'.$v->value.'"
										class="form-control option"
										'.((isset($student_data[$field->name]) && $student_data[$field->name] == $v->value) ? 'selected="selected"' : '').'
										>'.$v->option.'</option>';
					echo '</select>';
				break;
				case "text-area";
					echo '<textarea class="form-control" rows="4" name="'.$field->name.'">'.(isset($student_data[$field->name]) ? $student_data[$field->name] : '').'</textarea>';
				break;
				case "date";
					$tsp_day = new DateTime($student_data[$field->name]);
					echo
						'<div class="input-group date">
							<input type="text" class="form-control" name="'.$field->name.'"
								value="'.((isset($student_data[$field->name]) && $student_data[$field->name] != '0000-00-00') ?  $tsp_day->format(TSP_PHPDATE) : '').'"
								'.((isset($field->required) && $field->required == true) ? 'required' : '').'
								placeholder="'.$field->label.'" readonly="readonly">
								<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						</div>';
						unset($tsp_day);
				break;
				default :
				case "input":
					if (mb_strpos($field->name,'PASSWORD') !== false)
						echo '<input type="password" ';
					elseif  (mb_strpos($field->name,'EMAIL') !== false)
						echo '<input type="email" ';
					else
						echo '<input type="text" ';
							
					echo 'class="form-control" maxlength="32"
										name="'.$field->name.'"
										value="'.(isset($student_data[$field->name]) ? $student_data[$field->name] : '').'"
										'.((isset($field->required) && $field->required == true) ? 'required' : '').'
										placeholder="'.$field->label.'">';
			}
			echo '</div></div>';
		}
		if (!empty($student_id))
			echo '<input type="hidden" name="student_id" value="'.$student_id.'"/>';

		echo '<input type="hidden" name="action" value="tspclient"/>';
		echo '<input type="hidden" name="obj" value="members"/>';
		echo '<input type="hidden" name="method" value="SubmitStudent"/>';
		echo '<input type="hidden" name="boot_tab" value="tab-members-edit"/>';
		echo '<div class="form-group"><div class="col-sm-offset-3 col-sm-6">
		<button type="submit" class="btn btn-default">Save</button></div></div>';
	?> </form>
	</div>
<? else:
App::GetError()->Show("Unable Send Api Reqest");
endif; 