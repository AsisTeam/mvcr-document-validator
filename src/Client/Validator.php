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

	private const XML_START = '<?xml version="1.0"';

	/** @var Requester */
	private $requester;

	public function __construct(?IRequester $requester = null)
	{
		$this->requester = $requester ?? new Requester();
	}

	public function validate(Document $document): ValidityResult
	{
		$params = http_build_query([
			'dotaz' => $document->getDocumentNumber(),
			'doklad' => $document->getType(),
		]);

		$body = $this->requester->get(self::URL . '?' . $params);

		return $this->parseResponse($document, $this->parseXml($body));
	}

	public function getRequester(): IRequester
	{
		return $this->requester;
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

	private function toDateTime(string $val): DateTimeImmutable
	{
		try {
			return new DateTimeImmutable(str_replace(' ', '', $val));
		} catch (Throwable $e) {
			throw new ResponseException(sprintf('Could not convert "%s" to DateTime object', $val));
		}
	}

}
