<?php
namespace DanceStudioManager;
class RegisterWidget extends \WP_Widget
{
	public function __construct()
	{
		parent::__construct( "dsm_register", 'DSM Register' );
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