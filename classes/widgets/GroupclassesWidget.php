<?php
namespace TravelSportsPro;
class GroupclassesWidget extends \WP_Widget
{
	public function __construct()
	{
		parent::__construct( "tsp_classes_list", 'TSP GroupClasses' );
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
		App::GetTemplate()->Load(  'classes-list.php' );
		echo '</div>';
	}
}