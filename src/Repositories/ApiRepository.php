<?php

namespace BiNet\Core\Repositories;

use BiNet\App\Repositories\Repository;

class ApiRepository extends Repository {
	protected $client;
	protected $baseUri;

	public function __construct() {
		$this->client = new Client([
			'base_uri' => $this->baseUri,
			'timeout'  => 2.0
		]);
	}
}