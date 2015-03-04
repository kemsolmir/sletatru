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
	 * @var string
	 */
	public $login = '';
	/**
	 * @var string
	 */
	public $password = '';
	

	/**
	 * @return array
	 */
	public function GetDepartCities()
	{
		return $this->parseDictionary('GetDepartCities', 'City');
	}

	/**
	 * @return array
	 */
	public function GetCountries($townFromId = null)
	{
		$params = array();
		if (!empty($townFromId)) {
			$params[0]['townFromId'] = $townFromId;
		}
		return $this->parseDictionary('GetCountries', 'Country', $params);
	}

	/**
	 * @return array
	 */
	public function GetCities($countryId)
	{
		$params = array();
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		return $this->parseDictionary('GetCities', 'City', $params);
	}

	/**
	 * @return array
	 */
	public function GetHotelStars($countryId, array $towns = null)
	{
		$params = array();
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		if (!empty($towns)) {
			$params[0]['towns'] = $towns;
		}
		return $this->parseDictionary('GetHotelStars', 'HotelStar', $params);
	}


	/**
	 * @param string $method
	 * @param string $item
	 */
	protected function parseDictionary($method, $item, array $params = array())
	{
		$return = array();
		$res = $this->doSoapCall($method, $params);
		$resName = $method . 'Result';
		if (!empty($res->$resName->$item)) {
			foreach ($res->$resName->$item as $li) {
				$return[] = (array) $li;
			}
		}
		return $return;
	}

	/**
	 * @param string $wsdl
	 * @param array $soapOptions
	 * @return \SoapClient
	 */
	protected function createSoapClient($wsdl, array $soapOptions = array())
	{
		$client = parent::createSoapClient($wsdl, $soapOptions);
		$client->__setSoapHeaders(new \SoapHeader('urn:SletatRu:DataTypes:AuthData:v1', 'AuthInfo', array(
			'Login' => $this->login,
			'Password' => $this->password,
		)));
		return $client;
	}
}