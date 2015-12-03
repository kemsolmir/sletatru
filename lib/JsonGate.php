<?php

namespace sletatru;

/**
 * Class for a sletat.ru rest service
 */
class JsonGate extends BaseServiceRest
{
	/**
	 * @var string
	 */
	public $url = 'http://module.sletat.ru/Main.svc';
	/**
	 * @var string
	 */
	public $login = null;
	/**
	 * @var string
	 */
	public $password = null;


	/**
	 * @param string $cityFromId
	 * @param string $countryId
	 * @param bool $s_showcase
	 * @param array|string $cities
	 * @param string $currencyAlias
	 * @param bool $fake
	 * @param string $groupBy
	 * @param array|string $hiddenOperators
	 * @param int $includeDescriptions
	 * @param int $includeOilTaxesAndVisa
	 * @param array|string $meals
	 * @param int $pageNumber
	 * @param int $pageSize
	 * @param int $s_nightsMax
	 * @param int $s_nightsMin
	 * @param array|string $stars
	 * @param array|string $visibleOperators
	 */
	public function GetTours(
		$cityFromId,
		$countryId,
		$s_showcase = true,
		$cities = null,
		$currencyAlias = 'RUB',
		$fake = false,
		$groupBy = 'so_price',
		$hiddenOperators = null,
		$includeDescriptions = 0,
		$includeOilTaxesAndVisa = 0,
		$meals = null,
		$pageNumber = 1,
		$pageSize = 30,
		$s_nightsMax = null,
		$s_nightsMin = null,
		$stars = null,
		$visibleOperators = null
	){
		$params = array(
			'cityFromId' => $cityFromId,
			'countryId' => $countryId,
			's_showcase' => 'true',
			'currencyAlias' => $currencyAlias,
			'fake' => (bool) $fake,
			'groupBy' => $groupBy,
			'includeDescriptions' => (int) $includeDescriptions,
			'includeOilTaxesAndVisa' => (int) $includeOilTaxesAndVisa,
			'pageNumber' => (int) $pageNumber,
			'pageSize' => (int) $pageSize,
		);
		if (is_array($cities)) {
			$params['cities'] = implode(',', $cities);
		} elseif (!empty($cities)) {
			$params['cities'] = trim($cities);
		}
		if (is_array($hiddenOperators)) {
			$params['hiddenOperators'] = implode(',', $hiddenOperators);
		} elseif (!empty($hiddenOperators)) {
			$params['hiddenOperators'] = trim($hiddenOperators);
		}
		if (is_array($meals)) {
			$params['meals'] = implode(',', $meals);
		} elseif (!empty($meals)) {
			$params['meals'] = trim($meals);
		}
		if (is_array($stars)) {
			$params['stars'] = implode(',', $stars);
		} elseif (!empty($stars)) {
			$params['stars'] = trim($stars);
		}
		if ($s_nightsMax !== null) {
			$params['s_nightsMax'] = (int) $s_nightsMax;
		}
		if ($s_nightsMin !== null) {
			$params['s_nightsMin'] = (int) $s_nightsMin;
		}
		if (is_array($visibleOperators)) {
			$params['visibleOperators'] = implode(',', $visibleOperators);
		} elseif (!empty($visibleOperators)) {
			$params['visibleOperators'] = trim($visibleOperators);
		}
		$res = $this->callMethod('GetTours', $params);
		$return = array();
		if (!empty($res['aaData'])) {
			foreach ($res['aaData'] as $hotel) {
				$return['Rows'][] = array(
					'OfferId' => $hotel[0],
					'SourceId' => $hotel[1],
					'HotelDescriptionUrl' => $hotel[2],
					'HotelId' => $hotel[3],
					'ResortId' => $hotel[5],
					'TourName' => $hotel[6],
					'HotelName' => $hotel[7],
					'OriginalStarName' => $hotel[8],
					'RoomName' => $hotel[9],
					'MealName' => $hotel[10],
					'HtPlaceName' => $hotel[11],
					'CheckInDate' => $hotel[12],
					'CheckOutDate' => $hotel[13],
					'Nights' => $hotel[14],
					'Adults' => $hotel[16],
					'Kids' => $hotel[17],
					'SourceName' => $hotel[18],
					'ResortName' => $hotel[19],
					'SourceSearchFormUrl' => $hotel[20],
					'TicketsIncluded' => $hotel[22],
					'CountryId' => $hotel[30],
					'CountryName' => $hotel[31],
					'CityFromId' => $hotel[32],
					'CityFromName' => $hotel[33],
					'SourceImageUrl' => $hotel[34],
					'HotelRating' => $hotel[35],
					'MealDescription' => $hotel[36],
					'HtPlaceDescription' => $hotel[37],
					'HotelDescription' => $hotel[38],
					'HtPlaceId' => $hotel[39],
					'IsDemo' => $hotel[40],
					'MealId' => $hotel[41],
					'Price' => $hotel[42],
					'Currency' => $hotel[43],
					'RoomId' => $hotel[44],
					'StarId' => $hotel[45],
					'HotelPhotosCount' => $hotel[46],
					'TourUrl' => $hotel[47],
					'OriginalHotelName' => $hotel[48],
					'OriginalStarName' => $hotel[49],
					'OriginalTownName' => $hotel[50],
					'OriginalMealName' => $hotel[51],
					'OriginalHtPlaceName' => $hotel[52],
					'OriginalRoomName' => $hotel[53],
				);
			}
		}
		if (!empty($res['requestId'])) {
			$return['RequestId'] = $res['requestId'];
		}
		return $return;
	}

	/**
	 * @param string $url
	 * @param array $params
	 * @param string $type
	 * @return mixed
	 */
	protected function callMethod($method, array $params = array())
	{
		$url = rtrim($this->url, '/\\') . '/' . str_replace(array('/', '\\', '#', ':', '@'), '', $method);
		$params['login'] = $this->login;
		$params['password'] = $this->password;
		return $this->doRequest($url, $params);
	}

	/**
	 * @param string $result
	 * @return string
	 */
	protected function parseResult($result)
	{
		$response = !empty($result['content']) ? reset(json_decode($result['content'], true)) : array();
		if (!empty($response['IsError'])) {
			$this->addError($response['ErrorMessage']);
		}
		return !empty($response['Data']) ? $response['Data'] : array();
	}
}
