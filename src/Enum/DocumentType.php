<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Enum;

final class DocumentType
{

	public const PERSONAL_ID_CARD  = 0;
	public const PASSPORT_CENTRAL  = 4;
	public const PASSPORT_DISTRICT = 5;
	public const GUN_LICENSE       = 6;

	public const VALID_TYPES = [
		self::PERSONAL_ID_CARD,
		self::PASSPORT_CENTRAL,
		self::PASSPORT_DISTRICT,
		self::GUN_LICENSE,
	];

}
