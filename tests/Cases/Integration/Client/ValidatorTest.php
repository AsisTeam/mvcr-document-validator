<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Tests\Cases\Integration\Client;

use AsisTeam\MVCR\DocumentValidator\Client\Validator;
use AsisTeam\MVCR\DocumentValidator\Entity\Document;
use AsisTeam\MVCR\DocumentValidator\Enum\DocumentType;
use Tester\Assert;
use Tester\Environment;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class ValidatorTest extends TestCase
{

	public function setUp(): void
	{
		Environment::skip('this test should be run manually');
	}

	public function testIsValidPersonalIdCard(): void
	{
		$validator = new Validator('AsisTeam checker');
		$response = $validator->validate(new Document('123456AB', DocumentType::PERSONAL_ID_CARD));

		Assert::false($response->isInvalid());
	}

}

(new ValidatorTest())->run();
