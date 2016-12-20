<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Running_number_cls{
	
	function create($input,$format)
	{
		$this->Length = strlen($input);
		$this->Running_count = substr_count($format,'{');
		
		return $this->Running_count;
	}
	
	function find_position_running()
	{
		
	}
	
	function running_number()
	{
		
	}
	
	function running_year()
	{
		
	}
}
