<?php
namespace DanceStudioManager;
class CalendarWidget extends \WP_Widget
{
	public function __construct()
	{
		parent::__construct( "dsm_calendar", 'DSM Calendar' );
	}

	public function form( $instance )
	{
		
	}

	public function update( $new_instance, $old_instance )
	{
		
	}

	public function widget( $args, $instance )
	{
		echo '<div class="tab-content">';
		App::GetTemplate()->Load(  'classes-calendar.php' );
		echo '</div>';
	}
}