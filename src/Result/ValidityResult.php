<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Result;

use AsisTeam\MVCR\DocumentValidator\Entity\Document;
use DateTimeImmutable;

final class ValidityResult
{

	/** @var Document */
	private $document;

	/** @var bool */
	private $isInvalid;

	/** @var DateTimeImmutable|null */
	private $registryUpdated;

	/** @var DateTimeImmutable|null */
	private $invalidSince;

	public function __construct(
		Document $document,
		bool $isInvalid,
		?DateTimeImmutable $registryUpdated = null,
		?DateTimeImmutable $invalidSince = null
	)
	{
		$this->document        = $document;
		$this->isInvalid       = $isInvalid;
		$this->registryUpdated = $registryUpdated;
		$this->invalidSince    = $invalidSince;
	}

	public function getDocument(): Document
	{
		return $this->document;
	}

	public function isInvalid(): bool
	{
		return $this->isInvalid;
	}

	public function getRegistryUpdated(): ?DateTimeImmutable
	{
		return $this->registryUpdated;
	}

	public function getInvalidSince(): ?DateTimeImmutable
	{
		return $this->invalidSince;
	}

}
