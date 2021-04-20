<?php

namespace sletatru;

/**
 * Class for a basic rest service utilits.
 */
class BaseServiceRest extends BaseService
{
	/**
	 * @param string $url
	 * @param array $params
	 * @param string $type
	 * @return mixed
	 */
	protected function doRequest($url, array $params = array(), $type = 'get', $context = null)
	{
		$result = null;
		try {
			switch ($type) {
				case 'post':
					$result = $this->doPostRequest($url, $params, $type, $context);
					break;
				case 'get':
				default:
					$result = $this->doGetRequest($url, $params, $type, $context);
					break;
			}
		} catch (Exception $e) {
			$this->addError($e->getMessage());
		}
		return $this->parseResult($result);
	}

	/**
	 * @param string $url
	 * @param array $params
	 * @param resource $context
	 * @return array
	 */
	protected function doPostRequest($url, array $params = array(), $context = null)
	{
		$options = array(
			'http' => array(
				'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
				'method'  => 'POST',
				'content' => http_build_query($params),
			),
		);
		$requestContext = $this->configContext($context, $options);
		$return = array(
			'content' => @file_get_contents($url, false, $requestContext),
			'headers' => !empty($http_response_header) ? $this->parseHeaders($http_response_header) : array(),
		);
		return $return;
	}

	/**
	 * @param string $url
	 * @param array $params
	 * @param resource $context
	 * @return array 
	 */
	protected function doGetRequest($url, array $params = array(), $context = null)
	{
		$return = null;
		$arUrl = $this->explodeUrl($url);
		$arUrl['query'] = array_merge($arUrl['query'], $params);
		$requestUrl = $this->implodeUrl($arUrl);
		$requestContext = $this->configContext($context);
		$return = array(
			'content' => @file_get_contents($requestUrl, false, $requestContext),
			'headers' => !empty($http_response_header) ? $this->parseHeaders($http_response_header) : array(),
		);
		return $return;
	}

	/**
	 * @param array $headers
	 * @return array
	 */
	protected function parseHeaders(array $headers)
	{
		$return = array();
		foreach ($headers as $key => $header) {
			if ($key == 0) {
				preg_match('/^HTTP\/\S+\s(\d+)\s\S+$/', $header, $matches);
				$return['Status'] = !empty($matches[1]) ? (int) $matches[1] : '';
			} else {
				$arExplode = explode(':', $header, 2);
				
				if (count($arExplode) > 1) {
					$return[trim($arExplode[0])] = trim($arExplode[1]);
				}
			}
		}
		return $return;
	}

	/**
	 * @param mixed $context
	 * @return mixed
	 */
	protected function configContext($context, array $config = null)
	{
		$return = null;
		if (is_array($return)) {
			$return = stream_context_create($context);
		} elseif (is_resource($context)) {
			$return = $context;
		}
		if ($return !== null && !empty($config)) {
			stream_context_set_option($return, $config);
		} elseif ($return === null && !empty($config)) {
			$return = stream_context_create($config);
		}
		return $return;
	}

	/**
	 * @param string $url
	 * @return array
	 */
	protected function explodeUrl($url)
	{
		$tmpUrl = $url;
		if (strpos($tmpUrl, 'http') !== 0) {
			$tmpUrl = 'http://' . $tmpUrl;
		}
		$result = parse_url($tmpUrl);
		$result['url'] = $tmpUrl;
		if (!empty($result['query'])) {
			parse_str($result['query'], $result['query']);
		}
		$result['query'] = isset($result['query']) && is_array($result['query']) ? $result['query'] : array();
		return $result;
	}

	/**
	 * @param array $arUrl
	 * @return string
	 */
	protected function implodeUrl(array $arUrl)
	{
		$return = $arUrl['scheme'] . '://';
		if (!empty($arUrl['user']) && !empty($arUrl['pass'])) {
			$return .= $arUrl['user'] . ':' . $arUrl['pass'] . '@';
		}
		$return .= $arUrl['host'];
		if (!empty($arUrl['path'])) {
			$return .= '/' . ltrim($arUrl['path'], '/');
		}
		if (!empty($arUrl['query'])) {
			$return .= '?' . http_build_query($arUrl['query']);
		}
		if (!empty($arUrl['fragment'])) {
			$return .= '#' . $arUrl['fragment'];
		}
		return $return;
	}
	
	/**
	 * @param string $result
	 * @return string
	 */
	protected function parseResult($result)
	{
		return $result;
	}
}