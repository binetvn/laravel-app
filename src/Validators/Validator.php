<?php

namespace BiNet\Core\Validators;

use BiNet\App\Exceptions\NotPossibleException;
use BiNet\App\Validators\Validator as BaseValidator;
use BiNet\Core\Support\Collection;
use Illuminate\Contracts\Support\MessageBag;
use \Illuminate\Support\Facades\Validator as LaravelValidator;

class Validator extends BaseValidator {
	private $validator;
	protected $messages = [];

	public function validate(array $data, array $rules, $messages = []) {
		$rules = $this->toLaravelRules($rules);
		$messages = $this->toLaravelMessages($rules, $messages);
		$messages = array_merge($this->messages, $messages);
		$this->validator = LaravelValidator::make($data, $rules, $messages);

		return $this->validator->passes();
	}

	public function getErrors() {
		if ($this->validator) {
			return $this->validator->errors()->toArray();
		}

		throw new NotPossibleException('Validator.errors(): this must be invoked after #validate() or #validateOrFail()');
	}

	/**
	 * convert array of rules into Laravel representation
	 * i.e.
	 * $rules = [
	 * 	'title'=>['required', 'max'=>5, 'between'=>[1,5], IRule],
	 * ]
	 * => [
	 * 	'title'=>['required', 'max:5', 'between:1,5', IRule],
	 * ]
	 */
	public function toLaravelRules($rules) {
		foreach ($rules as $att => $constraints) {
			$results = [];	
			foreach ($constraints as $key => $value) {
				if (!is_int($key)) {
					if (is_array($value)) {
						$value = "$key:".implode(',', $value);
					} else {
						$value = "$key:$value";
					}
				}
				$results[] = $value;
			}
			$rules[$att] = $results;
		}

		return $rules;
	}


	/**
	 * convert array of $messages for $rules into Laravel representation
	 * i.e.
	 * $rules = [
	 * 	'username' => 'required', 'max:50', // or 'max'=>50
	 * 	'password' => 'required', 'max:72'
	 * ]
	 * 
	 * $messages = [
	 * 	'max' => 'Dữ liệu vượt quá độ dài cho phép :max.',
	 * 	'username' => [
	 * 		'required' => 'Bạn chưa nhập tài khoản.', 
	 * 		'max' => 'Tài khoản vượt quá độ dài cho phép :max'
	 * 	],
	 * 	'password' => [
	 * 		'required' => 'Bạn chưa nhập mật khẩu.'
	 * 	]
	 * ]
	 *
	 * => [
	 * 	'max' => 'Dữ liệu vượt quá độ dài cho phép :max.',
	 * 	'username.required' => 'Bạn chưa nhập tài khoản.',
	 * 	'password.required' => 'Bạn chưa nhập mật khẩu.',
	 * ]
	 */
	public function toLaravelMessages($rules, $messages) {
		// TODO:
		return $messages;
	}
}