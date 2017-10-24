<?php

namespace BiNet\Core\Support\Traits;

use BiNet\App\Support\AjaxResult;

trait AjaxResponse {
	public function data($data, $meta=null) {
    	return AjaxResult::data($data, $meta);
    }

    public function error($code, $message=null) {
    	return response()->json(AjaxResult::error($message),$code);
    }

    public function success($message=null) {
    	return AjaxResult::success($message);
    }

    public function view($view, $params=[]) {
        $data = view($view, $params)->render();
        $success = !session()->has('errors');

    	return AjaxResult::data($data, null, $success);
    }
}