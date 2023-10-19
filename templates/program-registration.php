<?php
namespace TravelSportsPro;

$related_students =  App::GetClient()->GetController('members')->GetChildList();
$program_reg_init = json_decode(json_encode(App::GetClient()->GetController('programs')->RegisterInit()),true);

?>

<script>
var step = 1,
	total_steps = 4,
	select_program_first = '<?php echo TSP_OC_PROGRAM_REGISTRATION_SELECT_PROGRAM_FIRST ?>';
	selected_values = {};

jQuery(function() {	
	jQuery('.step').hide();
	jQuery('#step' + step).show();
	
	jQuery(document).on('change', '#TEAM', function() {
		if (select_program_first != '1')
			loadPrograms(jQuery(this).val(),'team');
	});

	jQuery(document).on('change', '#DIVISION', function() {
		if (select_program_first != '1')
			loadPrograms(jQuery(this).val(), 'division');	
	});

	jQuery(document).on('change', '#PROGRAM', function() {
	<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
		if (jQuery('#PROGRAM option:selected').data('use-divisions') == "1") {
			jQuery("#division_container").show();
			jQuery("#team_container").hide();
		} else {
			jQuery("#division_container").hide();
			jQuery("#team_container").show();
		}
	<?php endif; ?>
		if (select_program_first == '1') {
			<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
			loadDivisions(jQuery(this).val());
			<?php endif; ?>
			loadTeams(jQuery(this).val());
		}
	});
	
	jQuery('#next-step').click(function() {
		var msg = '', step_title = '';
			
		if (step == 1) {
			step_title = 'Player: ' + jQuery('#step1 input[type=radio]:checked').data('title');
			selected_values.player_id = jQuery('#step1 input[type=radio]:checked').val();
			if (selected_values.player_id == '')
				msg = 'Please select Player';
		}
		else if (step == 2) {

			if (jQuery('#TEAM').is(':visible')) {
				step_title += 'Team: ' + jQuery('#TEAM option:selected').text() + '<br>';
				selected_values.team_id = jQuery('#TEAM').val();
			}

			<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
			if (jQuery('#DIVISION').is(':visible'))
				step_title += 'Division: ' + jQuery('#DIVISION option:selected').text() + '<br>';
			<?php endif; ?>

			step_title += 'Program: ' + jQuery('#PROGRAM option:selected').text() + '<br>';

			selected_values.class_id = jQuery('#PROGRAM').val();


			<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
				if (jQuery('#DIVISION').is(':visible')) {
					selected_values.division_id = jQuery('#DIVISION').val();
					if (selected_values.division_id == 0) {
						msg += 'Please select Division\n';
					}
				}
			<?php endif; ?>
			
			if (jQuery('#TEAM').is(':visible') &&  selected_values.team_id == 0) 
				msg += 'Please select Team\n';
				
			if (selected_values.class_id == 0)
				msg += 'Please select Program\n';

			let hide_uniform = jQuery('#PROGRAM option:selected').data('hide-uniform');
			if (msg == '' && hide_uniform != undefined && hide_uniform == "1") {
				jQuery('#step' + step).hide();
				step = 3;
				loadProgramPaymentPlans(jQuery("#PROGRAM").val());
				jQuery(this).html('Add to Cart and Checkout');
			}
		}
		else if (step == 3) {
			if (jQuery('#jersey_size').val() > 0) {
				step_title += 'Jersey Size: ' + jQuery('#jersey_size option:selected').text() + '<br>';
				selected_values.jersey_size = jQuery('#jersey_size').val();
			}
			if (jQuery('#jersey_number_1').val() > 0) {
				step_title += 'Jersey Number 1: ' + jQuery('#jersey_number_1').val() + '<br>';
				selected_values.jersey_number_1 = parseInt(jQuery('#jersey_number_1').val());
			}
			if (jQuery('#jersey_number_2').val() > 0) {
				step_title += 'Jersey Number 2: ' + jQuery('#jersey_number_2').val() + '<br>';
				selected_values.jersey_number_2 = parseInt(jQuery('#jersey_number_2').val());
			}
			if (jQuery('#pant_size').val() > 0) {
				step_title += 'Pant Size: ' + jQuery('#pant_size option:selected').text() + '<br>';
				selected_values.pant_size = jQuery('#pant_size').val();
			}
			if (jQuery('#pant_style').val() > 0) {
				step_title += 'Pant Style: ' + jQuery('#pant_style option:selected').text() + '<br>';
				selected_values.pant_style = jQuery('#pant_style').val();
			}
			if (jQuery('#cap_size').val() > 0) {
				step_title += 'Cap Size: ' + jQuery('#cap_size option:selected').text() + '<br>';
				selected_values.cap_size = jQuery('#cap_size').val();
			}
			if (jQuery('#bag_style').val() > 0) {
				step_title += 'Bag Style: ' + jQuery('#bag_style option:selected').text() + '<br>';
				selected_values.bag_style = jQuery('#bag_style').val();
			}
			
			if (selected_values.jersey_number_1 != '' && !isNaN(selected_values.jersey_number_1) && !Number.isInteger(selected_values.jersey_number_1))
				msg += 'Please enter valid Jersey Number 1\n';
			
			if (msg == '') {
				loadProgramPaymentPlans(jQuery("#PROGRAM").val());
				jQuery(this).html('Add to Cart and Checkout'); 
			}
		}
		else if (step == 4) {
			AddToCart();
		}
		else
			jQuery(this).html('Next &raquo;');

		if (msg == '') {
			jQuery('#step' + step).hide();
			if (step <= total_steps - 1)
				step ++;
		
			if (step_title != undefined)
				jQuery('#step' + (step - 1) + '-val').html('<h4><div>' + step_title + '</div></h4>');		
		
			jQuery('#step' + step).show();
		}
		else {
			alert(msg);
			return false;
		}
	});
	
	jQuery('#prev-step').click(function() {
		jQuery('#next-step').html('Next &raquo;');
		jQuery('#step' + step).hide();
		if (step >= 2)
			step --;
		jQuery('#step' + step).show();
		//selected_values[step] = 0;
	});
});

function loadPrograms(item_id, item_type)
{
	var division_id = 0;
	var team_id = 0;
	if (item_type == 'division')
		division_id = item_id;
	else
		team_id = item_id;

	jQuery.post(tspajax.url, { action : 'tspclient', boot_tab: 'program-registration' , type: 'json', load: 'programs', team_id: team_id, division_id: division_id}, function(data) {
		var s = '<option value="0">Please Select...</option>';
		if (data != undefined && data.length > 0) {
			jQuery.each(data, function(k, c) {
				var add_data = "";
				if (c.HIDE_UNIFORM_FIELDS != undefined && c.HIDE_UNIFORM_FIELDS == "1")
					add_data += ' data-hide-uniform="1"';
				else
					add_data += ' data-hide-uniform="0"';
				<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
				if (c.USE_DIVISIONS != undefined && c.USE_DIVISIONS == "1")
					add_data += ' data-use-divisions="1"';
				else
					add_data += ' data-use-divisions="0"';
				<?php endif; ?>
				s += '<option value="' + c.ID + '" ' + add_data + ' > ' + c.CODE + '</option>';
			});
		}
		jQuery('#PROGRAM').html(s);
	}, "json");
}

function loadTeams(program_id)
{
	jQuery.post(tspajax.url, { action : 'tspclient', boot_tab: 'program-registration' , type: 'json', load: 'teams', program_id: program_id}, function(data) {
		var s = '<option value="0">Please Select...</option>';
		if (data != undefined && data.length > 0) {
			jQuery.each(data, function(k, t) {
				s += '<option value="' + t.ID + '" > ' + t.NAME + '</option>';
			});
		}
		jQuery('#TEAM').html(s);
	}, "json");
}

function loadDivisions(program_id)
{	
	jQuery.post(tspajax.url, { action : 'tspclient', boot_tab: 'program-registration' , type: 'json', load: 'divisions', program_id: program_id}, function(data) {
		var s = '<option value="0">Please Select...</option>';
		if (!Array.isArray(data)) data = JSON.parse(data);
		if (data != undefined && data.length > 0) {
			jQuery.each(data, function(k, t) {
				s += '<option value="' + t.ID + '"> ' + t.NAME + '</option>';
			});
		}
		jQuery('#DIVISION').html(s);
	});
}

function loadProgramPaymentPlans(program_id)
{
	let html = '';
	let price = 0;
	let sales_item_id = 0;
	jQuery('#next-step').prop("disabled",true);
	jQuery.post(tspajax.url, { action : 'tspclient', boot_tab: 'program-registration' , type: 'json', load: 'payment-plans', program_id: program_id}, function(data) {
		if (data != undefined && data != '' && data.length > 0) {
			jQuery.each(data, function(k, item) {
				html += displayPaymentPlan(item.ID, item.PAYMENT_PLAN_FEE, item.FIRST_PAYMENT_AMOUNT, item.REPEATS, item.RECURRING_AMOUNT, item.SCHEDULE_NAME, item.SALES_ITEM_ID);
				price = item.FULL_PRICE;
				sales_item_id = item.SALES_ITEM_ID;
			});
			if (html != '') {
				html = '<h4 class="mt-3">Payment Plan</h4><li class="list-group-item"><label><input type="radio" name="payment_plan" value="0" checked="checked" data-sales_item_id="' + 
					sales_item_id + '"> <span id="pp-description-0">Pay in full $' +
					price + '</span><label></li>' + html;
			}
			jQuery('#selected-payment-plans').html(html);
			jQuery('#next-step').prop("disabled",false);
		}
    }, "json");		
}

function displayPaymentPlan(uid, fee, first_payment, repeats, recur_amount, schedule, sales_item_id)
{		
	return '<li class="list-group-item">'+
		'<label><input type="radio" name="payment_plan" value="'+uid+'" data-sales_item_id="' + sales_item_id + '"> <span id="pp-description-'+uid+'">' +
		'Fee: $' + fee + ', First Payment $' + first_payment + ' and ' + repeats + ' x $' + recur_amount + ' payments <b>' + schedule + '</b></span></label>' +
		'</li>';		
}

function AddToCart()
{
	if (tsp_ajax == true) {
        return false;
    }
	
	var tsp_boot_tab = 'checkout-cart';
	
	selected_values.sales_item_id = jQuery('input[name=payment_plan]:checked').data('sales_item_id');
	selected_values.payment_plan_id = jQuery('input[name=payment_plan]:checked').val();

	var tsp_data = { 
		action: 'tspclient',
		obj: 'checkout',
		method: 'SubmitCartItem',
		boot_tab: tsp_boot_tab,
		class_id: selected_values.class_id,
		sales_item_id: selected_values.sales_item_id,		
		payment_plan_id: selected_values.payment_plan_id,		
		student_id: selected_values.player_id,
		schedule_id: 0,
		related_item_id: 0,
		selected_values: selected_values,
		sales_item_type: 'item',
		class_type: 'program'
	};
	
    jQuery.ajax({
        type: "POST",
        url: tspajax.url,
        data: tsp_data,
        beforeSend: function () {
             tsp_ajax = true;
             jQuery('#tsp_loading').show();
        },
        success: function (response) {
				jQuery('#tsp-tab-content').html(response);
				jQuery('a[href="'+tsp_boot_tab+'"]').tab('show');
				jQuery('a[href="'+tsp_boot_tab+'"]').show();
                jQuery("#tsp-tab-content").show();
                jQuery(".tab-pane").show();
				tsp_connect_ajax('a[href="'+tsp_boot_tab+'"]');
				
        },
        complete: function (response) {
            tsp_ajax = false;
            jQuery('#tsp_loading').hide();
        }
    });
    return false;
}
</script>
<style>
	.step input[type=radio] {
		margin-right: 10px;
	}
	#selected-values div {
		font-size: medium;
	}
</style>
<div class="page-header">
    <h3>Program Registration</h3>
</div>
<?php if (App::GetClient()->GetController('auth')->isLogged()): ?>
<div id="selected-values" class="mb-4">
	<div id="step1-val"></div>
	<div id="step2-val"></div>
	<div id="step3-val"></div>
	<div id="step4-val"></div>
</div>

<div id="step1" data-step="1" class="step">
	<h4>Player Profile</h4>
	<ul class="list-group">
	<?php foreach ($related_students->family as $student): ?>
		<li class="list-group-item"><label><input type="radio" name="member_id" value="<?php echo $student->ID; ?>" checked="checked" data-title="<?php echo $student->FIRSTNAME; ?> <?php echo $student->LASTNAME; ?>">
		<i class="fa fa-user"></i> <?php echo $student->FIRSTNAME; ?> <?php echo $student->LASTNAME; ?></label></li>
	<?php endforeach; ?>
	</ul>	
</div>

<div id="step2" data-step="2" class="step">
	<?php if (TSP_OC_PROGRAM_REGISTRATION_SELECT_PROGRAM_FIRST == "1"): ?>
	<h4>Select Program</h4>
	<select name="PROGRAM" id="PROGRAM" class="form-control">
	<?php foreach ($program_reg_init['data']['programs'] as $k_program => $program): ?>
	<option value="<?php echo $k_program;?>"
	<?php if (isset($program_reg_init['data']['programs_hide_uniform']) 
				&& is_array($program_reg_init['data']['programs_hide_uniform']) 
				&& isset($program_reg_init['data']['programs_hide_uniform'][$k_program]))  echo ' data-hide-uniform="'.$program_reg_init['data']['programs_hide_uniform'][$k_program].'"' ?>
	<?php if (isset($program_reg_init['data']['programs_use_divisions']) 
				&& is_array($program_reg_init['data']['programs_use_divisions']) 
				&& isset($program_reg_init['data']['programs_use_divisions'][$k_program]))  echo ' data-use-divisions="'.$program_reg_init['data']['programs_use_divisions'][$k_program].'"' ?>
	data-use-divisions="1"><?php echo $program;?></option>
	<?php endforeach; ?>
	</select>
	<div>
		<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
		<div id="division_container">
			<label for="DIVISION" class="col-form-label mt-3"><h4>Division</h4></label>
				<select name="DIVISION" id="DIVISION" class="form-control">
					<option value="0">Please Select...</option>
			</select>
		</div>
		<?php endif; ?>
		<div id="team_container" style="<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1") echo 'display:none;'; ?>">
			<label for="TEAM" class="col-form-label mt-3"><h4>Team</h4></label>
			<select name="TEAM" id="TEAM" class="form-control">
				<option value="0">Please Select...</option>
			</select>
		</div>	
	</div>	
	<?php else: ?>
	<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1"): ?>
	<div id="division_container">
		<h4>Select your Division</h4>
		<select name="DIVISION" id="DIVISION" class="form-control">
		<?php foreach ($program_reg_init['data']['divisions'] as $k_division => $division): ?>
			<option value="<?php echo $k_division;?>"><?php echo $division;?></option>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
	<div id="team_container" style="<?php if (defined('TSP_DIVISIONS_ENABLED') && TSP_DIVISIONS_ENABLED == "1") echo 'display:none;'; ?>">
		<h4>Select your Team</h4>
		<select name="TEAM" id="TEAM" class="form-control">
		<?php foreach ($program_reg_init['data']['teams'] as $k_team => $team): ?>
		<option value="<?php echo $k_team;?>"><?php echo $team;?></option>
		<?php endforeach; ?>
		</select>
	</div>
	<div id="program_container">
		<label for="PROGRAM" class="col-form-label mt-3"><h4>Program</h4></label>
		<select name="PROGRAM" id="PROGRAM" class="form-control">
			<option value="0">Please Select...</option>
		</select>		
	</div>
	<?php endif; ?>
</div>

<div id="step3" data-step="3" class="step">
	<h4>Player Information</h4>
	<?php if (TSP_REG_JERSEY_SIZE == "1"): ?>
	<label for="jersey_size" class="col-form-label">Jersey Size</label>
	<select id="jersey_size" name="jersey_size" class="form-control">
	<?php foreach ($program_reg_init['data']['jersey_sizes'] as $k_jersey_size => $jersey_size): ?>
	<option value="<?php echo $k_jersey_size;?>"><?php echo $jersey_size;?></option>
	<?php endforeach; ?>
	</select>
	<?php endif; ?>
	<?php if (TSP_REG_JERSEY_NUMBER1 == "1"): ?>
	<label for="jersey_number_1" class="col-form-label">Jersey Number 1 (Not Guaranteed)</label>
	<input type="text" class="form-control" id="jersey_number_1" placeholder="Numbers only">
	<?php endif; ?>
	<?php if (TSP_REG_JERSEY_NUMBER2 == "1"): ?>
	<label for="jersey_number_2" class="col-form-label">Jersey Number 2 (Not Guaranteed)</label>
	<input type="text" class="form-control" id="jersey_number_2" placeholder="Numbers only">
	<?php endif; ?>
	<?php if (TSP_REG_PANT_SIZE == "1"): ?>
	<label for="pant_size" class="col-form-label">Pant Size</label>
	<select id="pant_size" name="pant_size" class="form-control">
	<?php foreach ($program_reg_init['data']['pant_sizes'] as $k_pant_size => $pant_size): ?>
	<option value="<?php echo $k_pant_size;?>"><?php echo $pant_size;?></option>
	<?php endforeach; ?>
	</select>
	<?php endif; ?>
	<?php if (TSP_REG_PANT_STYLE == "1"): ?>
	<label for="pant_style" class="col-form-label">Pant Style</label>
	<select id="pant_style" name="pant_style" class="form-control">
	<?php foreach ($program_reg_init['data']['pant_styles'] as $k_pant_style => $pant_style): ?>
	<option value="<?php echo $k_pant_style;?>"><?php echo $pant_style;?></option>
	<?php endforeach; ?>
	</select>
	<?php endif; ?>
	<?php if (TSP_REG_CAP_SIZE == "1"): ?>
	<label for="cap_size" class="col-form-label">Cap Size</label>
	<select id="cap_size" name="cap_size" class="form-control">
	<?php foreach ($program_reg_init['data']['cap_sizes'] as $k_cap_size => $cap_size): ?>
	<option value="<?php echo $k_cap_size;?>"><?php echo $cap_size;?></option>
	<?php endforeach; ?>
	</select>
	<?php endif; ?>
	<?php if (TSP_REG_BAG_STYLE == "1"): ?>
	<label for="bag_selection" class="col-form-label">Bag Style</label>
	<select id="bag_style" name="bag_style" class="form-control">
	<?php foreach ($program_reg_init['data']['bag_styles'] as $k_bag_style => $bag_style): ?>
	<option value="<?php echo $k_bag_style;?>"><?php echo $bag_style;?></option>
	<?php endforeach; ?>
	<?php endif; ?>
	</select>
</div>

<div id="step4" data-step="4" class="step">
<ul id="selected-payment-plans" class="list-group mb-4">
</ul>	
</div>

<br>
<button class="btn btn-primary" type="button" id="prev-step">&laquo; Previous</button>
<button class="btn btn-primary" type="button" id="next-step">Next &raquo;</button>
<?php else: ?>
	<a href="#tab-sign-in" data-toggle="tab"><b>Sign In to continue registration.</b></a>
	<?php if (TSP_OC_MEMBERS_REGISTRATION == "1"): ?>
	<div class="login-create-account">
		<a href="#tab-create-account" data-toggle="tab">Don't have an account? Register Here</a>
	</div>
	<?php endif; ?>
<?php endif; ?>