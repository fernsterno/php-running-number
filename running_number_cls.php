<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Running_number_cls{
	public $Input;
	public $Format;
	public $Status_format;
	public $Length;
	public $Running_count;
	public $Running;
	
	public function __construct()
    {
		// Do something with $params
    }
	
	public function check_format()
	{
		if( substr_count($this->Format,'{') != substr_count($this->Format,'}') ){return false;}
		return true;
	}
	
	public function create($input,$format)
	{
		$this->Input = $input;
		$this->Output = $input;
		$this->Format = $format;
		$this->Status_format = $this->check_format();
		$this->Length = strlen($input);
		
		$this->find_position_running();
		$this->find_type_running();
		
		if($this->Length == 0){
			$input_empty = $this->Format;
			$input_empty = str_replace('{','',$input_empty);
			$this->Output = str_replace('}','',$input_empty);
			$this->Length = strlen($this->Output );
		}
		
		for($i=0;$i<$this->Running_count;$i++)
		{
			$type = $this->Running[$i]['type'];
			$this->Output = $this->$type($this->Running[$i]);
		}
		
		return $this->Output;
	}
	
	private function find_position_running()
	{
		$this->Running_count = 0;
		$format_length = strlen($this->Format);
		$increase = 0;
		for($i=0;$i<$format_length;$i++)
		{
			if( substr($this->Format,$i,1) != '{'){continue;}
			
			$this->Running_count++;
			
			$this->Running[$this->Running_count-1]['start'] = ($i - $increase);
			$this->Running[$this->Running_count-1]['start_fillter'] = ($i);
			$this->Running[$this->Running_count-1]['digit'] = 0;
			$increase += 2;
			
			for($ii=$i;$ii<$format_length;$ii++)
			{
				if( substr($this->Format,$ii,1) == '}'){break;}
				if( substr($this->Format,$ii,1) == '{'){continue;}
				$this->Running[$this->Running_count-1]['digit']++;
			}
		}
	}
	
	private function find_type_running()
	{
		$text = '';
		$format_length = strlen($this->Format);
		for($i=0;$i<$this->Running_count;$i++)
		{
			$text = '';
			for($ii=$this->Running[$i]['start_fillter'];$ii<$format_length;$ii++)
			{
				if( substr($this->Format,$ii,1) == '}'){break;}
				if( substr($this->Format,$ii,1) == '{'){continue;}
				$text .= substr($this->Format,$ii,1);
			}
			
			if($text == 'yy')
			{
				$this->Running[$i]['type'] = 'running_year';
			}elseif(substr($text,0,1) == 'x') {
				$this->Running[$i]['type'] = 'running_number';
			}else{
				$this->Running[$i]['type'] = '';
			}
		}
	}
	
	public function get_property()
	{
		$output['input'] = $this->Input;
		$output['output'] = $this->Output;
		$output['format'] = $this->Format;
		$output['status_format'] = $this->Status_format;
		$output['lenght'] = $this->Length;
		$output['running_count'] = $this->Running_count;
		$output['running'] = $this->Running;
		return $output;
	}
	
	private function running_number($input=0)
	{
		$running_number = substr($this->Output, $input['start'], $input['digit']);
		settype($running_number, "integer");
		$running_number++;
		settype($running_number, "string");
		if(strlen($running_number) < $input['digit'])
		{
			$zero = '';
			while(strlen($running_number) < $input['digit'])
			{
				$running_number = '0'.$running_number;
				//break;
			}
			
		}
		$output = substr_replace($this->Output, $running_number, $input['start'], $input['digit']);
		return $output;
	}
	
	private function running_year($input=0)
	{
		$year = date('Y');
		settype($year, "integer");
		$thai_year = $year+543;
		$output = substr_replace($this->Output, substr($thai_year,2,2), $input['start'], 2);
		return $output;
	}
}
