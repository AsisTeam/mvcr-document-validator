<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Client;

use AsisTeam\MVCR\DocumentValidator\Entity\Document;
use AsisTeam\MVCR\DocumentValidator\Exception\ResponseException;
use AsisTeam\MVCR\DocumentValidator\Result\ValidityResult;
use DateTimeImmutable;
use SimpleXMLElement;
use Throwable;

final class Validator
{

	private const URL = 'https://aplikace.mvcr.cz/neplatne-doklady/doklady.aspx';

	private const XML_START = '<?xml version="1.0" encoding="utf-8"?>';

	/** @var string */
	private $agent;

	public function __construct(string $agent = '')
	{
		$this->agent = $agent;
	}

	public function validate(Document $document): ValidityResult
	{
		$params = http_build_query(['dotaz' => $document->getDocumentNumber(), 'doklad' => $document->getType()]);

		$curl = curl_init();
		curl_setopt_array($curl, [
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => self::URL . '?' . $params,
			CURLOPT_USERAGENT => $this->agent,
		]);

		$resp = curl_exec($curl);
		if ($resp === false) {
			throw new ResponseException(sprintf('Curl error: %s', curl_error($curl)));
		}

		curl_close($curl);

		/** @var string $resp */
		return $this->parseResponse($document, $this->parseXml($resp));
	}

	private function parseXml(string $data): SimpleXMLElement
	{
		$begin = substr($data, 0, strlen(self::XML_START));

		if ($begin !== self::XML_START) {
			throw new ResponseException(sprintf('Response does not contain valid xml: %s ...', $begin));
		}

		$xml = simplexml_load_string($data);

		if ($xml === false) {
			foreach (libxml_get_errors() as $error) {
				throw new ResponseException(sprintf('Response does not contain valid xml string. Error: %s', $error));
			}
		}

		return $xml;
	}

	private function parseResponse(Document $document, SimpleXMLElement $xml): ValidityResult
	{
		if (isset($xml->chyba)) {
			throw new ResponseException(sprintf('Response marked as invalid. Error: %s', $xml->chyba));
		}

		if (!isset($xml->odpoved)) {
			throw new ResponseException('Response does not contain "odpoved" field');
		}

		if ($xml->odpoved->attributes()->evidovano === null) {
			throw new ResponseException('Response field "odpoved "does not contain "evidovano" attribute');
		}

		$isKnownAsInvalid = false;
		if ((string) $xml->odpoved->attributes()->evidovano === 'ano') {
			$isKnownAsInvalid = true;
		}

		return new ValidityResult(
			$document,
			$isKnownAsInvalid,
			$xml->odpoved->attributes()->aktualizovano !== null ? $this->toDateTime((string) $xml->odpoved->attributes()->aktualizovano) : null,
			$xml->odpoved->attributes()->evidovano_od !== null ? $this->toDateTime((string) $xml->odpoved->attributes()->evidovano_od) : null
		);
	}

	private function toDateTime(string $val): DateTimeImmutable
	{
		try {
			return new DateTimeImmutable(str_replace(' ', '', $val));
		} catch (Throwable $e) {
			throw new ResponseException(sprintf('Could not convert "%s" to DateTime object', $val));
		}
	}

}
