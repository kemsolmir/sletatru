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
		return $this->parseDictionary('GetHotelStars', 'HotelStars', $params);
	}

	/**
	 * @return array
	 */
	public function GetMeals()
	{
		return $this->parseDictionary('GetMeals', 'Meal');
	}

	/**
	 * @return array
	 */
	public function GetTourOperators($townFromId = null, $countryId = null)
	{
		$params = array();
		if (!empty($townFromId)) {
			$params[0]['townFromId'] = $townFromId;
		}
		if (!empty($countryId)) {
			$params[0]['countryId'] = $countryId;
		}
		return $this->parseDictionary('GetTourOperators', 'TourOperator', $params);
	}

	/**
	 * @return array
	 */
	public function GetHotels($countryId, $towns = null, $stars = null, $filter = null, $count = -1)
	{
		$params = array();
		$params[0]['countryId'] = $countryId;
		$params[0]['count'] = (int) $count;
		if (!empty($towns) && is_array($towns)) {
			$params[0]['towns'] = $towns;
		}
		if (!empty($stars) && is_array($stars)) {
			$params[0]['stars'] = $stars;
		}
		if ($filter !== null) {
			$params[0]['stars'] = trim($filter);
		}
		return $this->parseDictionary('GetHotels', 'Hotel', $params);
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
			if (is_array($res->$resName->$item)) {
				foreach ($res->$resName->$item as $li) {
					$return[] = $this->serviceResponceToArray($li);
				}
			} else {
				$return[] = $this->serviceResponceToArray($res->$resName->$item);
			}	
		}
		return $return;
	}

	/**
	 * @param mixed $array
	 * @return array
	 */
	protected function serviceResponceToArray($array)
	{
		$return = array();
		$toArray = (array) $array;
		foreach ($toArray as $key => $value) {
			if ($value === 'NIL') {
				$return[$key] = null;
			} else {
				$return[$key] = $value;
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