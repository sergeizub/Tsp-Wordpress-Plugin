<?php
namespace DanceStudioManager;
class GroupclassesWidget extends \WP_Widget
{

	public function __construct()
	{
		parent::__construct( "dsm_classes_list", 'DSM GroupClasses' );
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
