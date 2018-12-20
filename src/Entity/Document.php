<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Entity;

use AsisTeam\MVCR\DocumentValidator\Enum\DocumentType;
use AsisTeam\MVCR\DocumentValidator\Exception\InvalidDocumentTypeException;

final class Document
{

	/** @var string */
	private $documentNumber;

	/** @var int */
	private $type;

	public function __construct(string $documentNumber, int $type)
	{
		if (!in_array($type, DocumentType::VALID_TYPES, true)) {
			throw new InvalidDocumentTypeException(
				sprintf(
					'Invalid document type %d given. Please use one of: [%s]',
					$type,
					implode(array_values(DocumentType::VALID_TYPES), ',')
				)
			);
		}

		$this->documentNumber = $documentNumber;
		$this->type           = $type;
	}

	public function getDocumentNumber(): string
	{
		return $this->documentNumber;
	}

	public function getType(): int
	{
		return $this->type;
	}

}
