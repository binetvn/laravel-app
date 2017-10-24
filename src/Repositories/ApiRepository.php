<?php

namespace BiNet\Core\Repositories;

use BiNet\App\Repositories\Repository;
use BiNet\Core\Exceptions\ApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

abstract class ApiRepository extends Repository {
	protected $client;

	protected $baseUri;
	protected $timeout = 5.0;
	protected $verify = false; // bypass ssl
	protected $httpErrors = false;

	public function __construct() {
		$this->client = new Client([
			'base_uri' => $this->baseUri,
			'timeout'  => $this->timeout,
			'verify' => $this->verify,
			'http_errors'=> $this->httpErrors
		]);
	}

	/**
	 * options = [url, type, paramType, data
	 * throws ApiException
	 */
	public function request($options) {
		$options = array_merge([
			'url' => '',
			'type' => 'GET',
			'paramType' => 'query', // form_params/ multipart/ json/ body
			'data' => []
		], $options);

		// convert data array into json if paramType = body
		if ($options['paramType'] == 'body' && is_array($options['data'])) {
			$options['data'] = json_encode($options['data']);
		}

		// debug
		// dd($options['data']);

		try {
			return $this->client->request($options['type'], $options['url'], [$options['paramType'] => $options['data']]);
		} catch (RequestException $e) {
			// dd($e);
			throw new ApiException('Please contact dev team!');
		}
	}
}