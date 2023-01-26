<?php
namespace TravelSportsPro;
class RegisterWidget extends \WP_Widget
{
	public function __construct()
	{
		parent::__construct( "tsp_register", 'TSP Register' );
	}

	public function form( $instance )
	{

          
	}

	public function update( $new_instance, $old_instance )
	{

	}

	public function widget( $args, $instance )
	{
		App::GetTemplate()->Load(  'auth-register.php' );
	}
}