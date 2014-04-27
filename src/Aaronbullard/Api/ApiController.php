<?php namespace Aaronbullard\Api;

use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller{

	/**
	 * @var integer
	 */
	protected $statusCode = 200;

	protected $redirect_to = NULL;

	protected $status = 'success';

	/**
	 * Set the status code for the response
	 * @param int $statusCode
	 * @return  Viimed\Api\ApiController
	 */
	protected function setStatusCode($statusCode)
	{
		if( ! is_integer($statusCode) )
		{
			throw new ApiControllerException("Status Code must be an integer");
		}

		$this->statusCode = $statusCode;

		return $this;
	}

	/**
	 * Get the status code for the response
	 * @return int status code
	 */
	protected function getStatusCode()
	{
		return (int)$this->statusCode;
	}

	public function setRedirection($url)
	{
		if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
		{
			throw new ApiControllerException("Redirect must be a valid url!");
		}

		$this->redirect_to = $url;

		return $this;
	}

	public function getRedirection()
	{
		return $this->redirect_to;
	}

	public function setStatus($status = 'success')
	{
		if( ! in_array($status, ['success', 'error']))
		{
			throw new ApiControllerException("Status must be either 'success' or 'error'!");
		}

		$this->status = $status;

		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	/**
	 * Respond with json data
	 * @param  mixed $data    Requested data
	 * @param  array $headers Response headers
	 * @return json
	 */
	protected function respond($data, $headers = [])
	{
		$data['status'] 		= $this->getStatus();
		$data['redirect_to'] 	= $this->getRedirection();

		return Response::json($data, $this->getStatusCode(), $headers);
	}

	/**
	 * Respond with error message
	 * @param  string $message Error message
	 * @return json 
	 */
	protected function respondWithError($message)
	{
		return $this->setStatus('error')->respond([
			'data'	 => NULL,
			'error'	 => [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	public function respondWithSuccess($data)
	{
		return $this->setStatus('success')->respond($data);
	}

	public function respondOK($data)
	{
		return $this->setStatusCode(200)->respondWithSuccess($data);
	}

	public function respondCreated($data)
	{
		return $this->setStatusCode(201)->respondWithSuccess($data);
	}

	public function respondBadRequest($message = 'Bad Request!')
	{
		return $this->setStatusCode(400)->respondWithError($message);
	}

	public function respondUnauthorized($message = 'Unauthorized Request!')
	{
		return $this->setStatusCode(401)->respondWithError($message);
	}

	public function respondForbidden($message = 'Forbidden!')
	{
		return $this->setStatusCode(403)->respondWithError($message);
	}

	public function respondNotFound($message = 'Not Found!')
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondFormValidation($data = NULL, $message = 'Unprocessable Entity!')
	{
		return $this->setStatusCode(422)->respond([
				'status' => 'error',
				'data'	 => $data,
				'error'	 => [
					'message' => $message,
					'status_code'	=> $this->getStatusCode()
				]
			]);
	}

	public function respondInternalError($message = 'Internal Error!')
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

}
