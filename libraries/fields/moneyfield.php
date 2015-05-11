<?php

/**
*
* @author  Antonio de la Rosa <webmaster@web-t-sys.com>
* @file
* @package ExtraFields
*
*/
 
//Special field PercentField for discount, taxes_for_group, transport_for_group

class PercentField extends IntegerField{


	function check($value)
	{
		
		
		settype($value, "integer");

		//Reload related model if not exists, if exists, only check cache models...

		if($value>100 || $value<0)
		{
			
			$this->std_error=I18n::lang('common', 'the_value_can_not_be_greater_than_100', 'The value cannot be greater than 100');

			return 0;

		}

		return $value;
		

	}


}

class BasicMoneyField extends DoubleField{


	function show_formatted($value)
	{

		return $this->currency_format($value);

	}

	
	static function currency_format($value, $symbol_currency='&euro;')
	{


		return number_format($value, 2).' '.$symbol_currency;

	}

}

?>