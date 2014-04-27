<?php namespace Aaronbullard\Api;

use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;

abstract class ApiController extends Controller{

	/**
	 * @var integer
	 */
	protected $statusCode = 200;

	/**
	 * Set the status code for the response
	 * @param int $statusCode
	 * @return  Aaronbullard\Api\ApiController
	 */
	public function setStatusCode($statusCode)
	{
		if( !is_integer($statusCode) )
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
	public function getStatusCode()
	{
		return (int)$this->statusCode;
	}

	/**
	 * Respond with json data
	 * @param  mixed $data    Requested data
	 * @param  array $headers Response headers
	 * @return json
	 */
	public function respond($data, $headers = [])
	{
		return Response::json($data, $this->getStatusCode(), $headers);
	}

	/**
	 * Respond with error message
	 * @param  string $message Error message
	 * @return json 
	 */
	public function respondWithError($message)
	{
		return $this->respond([
			'error'	=> [
				'message' => $message,
				'status_code' => $this->getStatusCode()
			]
		]);
	}

	/****** HELPERS ********/

	public function respondOK($data)
	{
		return $this->setStatusCode(200)->respond($data);
	}

	public function respondCreated($data)
	{
		return $this->setStatusCode(201)->respond($data);
	}

	public function respondBadRequest($message = 'Bad Request!')
	{
		return $this->setStatusCode(400)->respondWithError($message);
	}

	public function respondUnauthorized($message = 'Unauthorized Request!')
	{
		return $this->setStatusCode(401)->respondWithError($message);
	}

	public function respondNotFound($message = 'Not Found!')
	{
		return $this->setStatusCode(404)->respondWithError($message);
	}

	public function respondNotAcceptable($message = 'Not Acceptable!')
	{
		return $this->setStatusCode(406)->respondWithError($message);
	}

	public function respondInternalError($message = 'Internal Error!')
	{
		return $this->setStatusCode(500)->respondWithError($message);
	}

}