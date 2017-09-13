<?php

namespace BiNet\Core\Support;

use BiNet\App\Support\Contracts\ICollection;

class Collection extends Illuminate\Support\Collection implements ICollection {

	public function containKey(mixed $key) {
		return parent::has($key);
	}

	public function removeKeys($keys) {
		return parent::forget($keys);
	}
}