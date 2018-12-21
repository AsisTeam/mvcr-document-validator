<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Client;

use AsisTeam\MVCR\DocumentValidator\Exception\ResponseException;

interface IRequester
{

	/**
	 * Makes HTTP GET request and returns response body
	 * Or throws ResponseException on any error or non 200 response StatusCode
	 * @param string $url
	 * @return string
	 * @throws ResponseException
	 */
	public function get(string $url): string;

	/**
	 * Set query timeout
	 * @param int|null $timeout
	 * @return IRequester
	 */
	public function setTimeout(?int $timeout);

}
