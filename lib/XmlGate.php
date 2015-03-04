<?php

namespace sletatru;

/**
 * Class for a cb RF service
 */
class XmlGate extends BaseServiceSoap
{
	/**
	 * @var string
	 */
	public $wsdl = 'http://module.sletat.ru/XmlGate.svc?wsdl';
	

	/**
	 * @param string|int $onDate
	 * @return array
	 */
	public function GetDepartCities($onDate = null, $currency = null)
	{
		$res = $this->doSoapCall('GetDepartCities');
	}
}