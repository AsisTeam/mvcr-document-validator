<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Client;

interface IRequester
{

	/**
	 * Makes HTTP GET request and returns response body
	 * Or throws ResponseException on any error or non 200 response StatusCode
	 */
	public function get(string $url): string;

}
