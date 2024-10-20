<?php namespace News\Traits;

trait ApiResponseTrait {

	protected function successResponse ( $data, $message = 'OK', $code = 200 ) {
		return response()->json( [
			'success' => true,
			'message' => $message,
			'data'    => $data,
								 ], $code );
	}

	protected function failureResponse ( $message , $code = 400 ) {
		return response()->json( [
			'code'    => $code,
			'success' => false,
			'message' => $message,
								 ], $code );
	}
}
