<?php
	namespace TravelSportsPro;
	$class_id = App::GetApi()->GetIdParam();
?>
<div id="tab-classes-calendar" class="tab-pane">
<?php
if (!empty($class_id)) :
	App::GetTemplate()->Load('class-registration.php');
else :
?>
<script>
jQuery(function() {
	if (!localStorage.getItem('cal_offset'))
		localStorage.setItem('cal_offset', moment().format('YYYY-MM-DD'));
		
	jQuery('#tsp_calendar').fullCalendar({
		header: {
			left: 'prev next', 
			center: 'title',
			right: 'month, agendaWeek, listWeek, agendaDay'
		},
		defaultView: (function () { if (jQuery(window).width() <= 768) { return defaultView = 'listWeek'; } else { return defaultView = 'month'; } })(),
		firstDay: 1,
        height: 'auto',
		allDaySlot: false,
		slotEventOverlap: false,
		slotDuration: '00:15:00',
		timeFormat: '<?php echo TSP_CALENDARTIME; ?>',
		views: { listWeek: { buttonText: 'list week' } },
        minTime: '<?php echo ((TSP_CALENDAR_START_TIME) ? TSP_CALENDAR_START_TIME  : '6:00'); ?>',
        maxTime: '<?php echo ((TSP_CALENDAR_END_TIME) ? TSP_CALENDAR_END_TIME  : '24:00'); ?>',
		editable: false,
		defaultDate: localStorage.getItem('cal_offset'),
		displayEventEnd: true,
		loading: function(bool) {
			if (bool) jQuery('#tsp_loading').show();
			else jQuery('#tsp_loading').hide();
		},
		eventSources: [
            {
                url: tspajax.url,
                type: 'POST',
                data: {
                    action : 'tspclient',
                    boot_tab: 'classes-calendar',
					type: 'json',
                },
                error: function() {
                    alert('There was an error while fetching schedules!');
                }
            }
	    ],
		eventRender: function(event, element, calEvent) {
			element.attr('tsp_schedule_id',event.schedule_id);
			element.attr('tsp_class_id',event.class_id);
			element.attr('tsp_obj','classes');
			element.attr('tsp_method','GetInfo');
			element.attr('tsp_classes_view','classes-calendar');
		},
        eventAfterAllRender: function () {
	        var d = jQuery('#tsp_calendar').fullCalendar('getDate');
            localStorage.setItem('cal_offset', d.format('YYYY-MM-DD'));
        },
		eventClick: function(calEv, jsEv) {
			jsEv.preventDefault();
			var tsp_data = { 
				action: 'tspclient',
				boot_tab: 'event-details',
				schedule_id: calEv.schedule_id,
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
					jQuery("#eventModal .modal-content").html(response);
					jQuery("#eventModal").modal('show');
       			 },
       			complete: function (response) {
           			tsp_ajax = false;
            		jQuery('#tsp_loading').hide();
        		}
			/*if (navigator.onLine) {
				tsp_ajax_click(this);
			}*/
		})
		}
		
	});
});
</script>

<div id="tsp_calendar" ></div>

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="myWaiver" aria-hidden="true">
<div class="modal-dialog">
	    <div class="modal-content">
		</div>
</div>
<?php endif; ?>
</div>