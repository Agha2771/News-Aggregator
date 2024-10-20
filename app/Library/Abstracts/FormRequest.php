<?php namespace News\Abstracts;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest as LaravelFormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

abstract class FormRequest extends LaravelFormRequest {

	abstract public function rules ();

	abstract public function authorize ();

	public function validator ( $factory ) {

		return $factory->make( $this->formatRequest(), $this->container->call( [ $this, 'rules' ] ), $this->messages() );
	}

	protected function formatRequest () {
		if ( method_exists( $this, 'formatter' ) ) {
			return $this->container->call( [ $this, 'formatter' ] );
		}
		return $this->all();
	}

	protected function failedValidation ( Validator $validator ) {
		$errors = ( new ValidationException( $validator ) )->errors();
		throw new HttpResponseException( response()->json( [
															   'code'    => JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
															   'success' => false,
															   'message' => 'The data is unable to be processed (for example, if an image uploaded is not valid or the JSON body of a request is badly-formed)',
															   'errors'  => $errors,
														   ], JsonResponse::HTTP_UNPROCESSABLE_ENTITY ) );
	}
}
