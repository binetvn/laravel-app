<?php

namespace BiNet\Core\Support;

use \Illuminate\Support\Collection as LaravelCollection;

use BiNet\App\Support\Contracts\ICollection;

class Collection implements ICollection {
	const ITERATIVE_UNLIMITED = -1;

	private $collection;

	private function __construct() {
		// do nothing
	}

	/**
	 * create a collection from array of $item
	 * recursive wrap (create collection for) any child $item in $items if $item is array until $iterative reached (-1 for unlimited)
	 */
	public static function createFromArray(array $items, $iterative = 0) {
		// wrap children
		if ($iterative > 0 || $iterative == static::ITERATIVE_UNLIMITED) {
			if ($iterative != static::ITERATIVE_UNLIMITED) {
				$iterative--;
			}
			foreach ($items as $key=>$value) {
				if (is_array($value)) {
					$items[$key] = static::createFromArray($value, $iterative);
				}
			}
		}

		$instance = new self();
		$instance->collection = new LaravelCollection($items);

		return $instance;
	}

	public static function createFromLaravelCollection(LaravelCollection $collection) {
		$instance = new self();
		$instance->collection = $collection;
		return $instance;
	}

	# ICollection
	public function isEmpty() {
		return $this->collection->isEmpty();
	}

	public function size() {
		return $this->count();
	}

	public function keys() {
		return $this->collection->keys()->toArray();
	}

	public function containKey($key) {
		return $this->collection->has($key);
	}

	// /**
	//  * add [$key=>$value] into this
	//  * if $key already exists
	//  * 	if existing associated value $old is an array 
	//  * 		append $old with $value
	//  * 	else 
	//  * 		turn $old into array (if $asArray else Collection) with $old & $value
	//  */
	// public function add($key, $value, $asArray = true) {
	// 	$old = $this->get($key);

	// 	if ($old == null) {
	// 		$this->collection->put($key, $value);
	// 	} else {
	// 		if (is_array($old)) {
	// 			$old[] = $value;
	// 		} else if ($old instanceof ICollection) {
	// 			$old->push($value);
	// 		} else { 
	// 			// transform into array
	// 			$new = [$old, $value];
	// 			if ($asArray) {
	// 				$old = $new;
	// 			} else {
	// 				$old = static::createFromArray($new);
	// 			}
	// 		}

	// 		$this->collection->put($key,$old);
	// 	}
	// }

	public function put($key, $value) {
		return $this->collection->put($key, $value);
	}

	public function push($value) {
		return $this->collection->push($value);
	}

	public function pull($key, $default = null) {
		return $this->collection->pull($key, $default);
	}

	public function removeKeys($keys) {
		$this->collection->forget($keys);
		return $this;
	}

	public function get($key, $default = null) {
		return $this->collection->get($key, $default);
	}

	// /**
	//  * return value for $key, $default if not exist
	//  * @param  mixed  $key     
	//  * @param  mixed  $default 
	//  * @return mixed          
	//  */
	// public function first($key = null, $default = null) {
	// 	// key not specified
	// 	if (!$key) {
	// 		return $this->collection->first(null, $default);
	// 	}

	// 	// key specified
	// 	$value = $this->get($key);

	// 	if (is_array($value) && count($value) > 0) {
	// 		return $value[0];
	// 	}

	// 	if ($value instanceof ICollection) {
	// 		return $value->first(null, $default);
	// 	}

	// 	return $default;
	// }

	# ArrayAccess
	public function offsetExists($offset) {
		return $this->collection->offsetExists($offset);
	}

	public function offsetGet($offset) {
		return $this->collection->offsetGet($offset);
	}

	public function offsetSet($offset, $value) {
		return $this->collection->offsetSet($offset);
	}

	public function offsetUnset($offset) {
		return $this->collection->offsetUnset($offset);
	}

	# Countable 
	public function count()
    {
        return count($this->collection);
    } 
}