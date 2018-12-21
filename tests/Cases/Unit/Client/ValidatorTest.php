<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Tests\Cases\Unit\Client;

use AsisTeam\MVCR\DocumentValidator\Client\IRequester;
use AsisTeam\MVCR\DocumentValidator\Client\Validator;
use AsisTeam\MVCR\DocumentValidator\Entity\Document;
use AsisTeam\MVCR\DocumentValidator\Enum\DocumentType;
use Mockery;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../../bootstrap.php';

class ValidatorTest extends TestCase
{

	public function testValidateSuccessValid(): void
	{
		$validator = new Validator($this->createRequester('success_id_valid.xml'));
		$response = $validator->validate(new Document('123456AB', DocumentType::PERSONAL_ID_CARD));

		Assert::false($response->isInvalid());
		Assert::equal('20.12.2018', $response->getRegistryUpdated()->format('d.m.Y'));
		Assert::null($response->getInvalidSince());
	}

	public function testValidateSuccessInvalid(): void
	{
		$validator = new Validator($this->createRequester('success_id_invalid.xml'));
		$response = $validator->validate(new Document('123456AB', DocumentType::PERSONAL_ID_CARD));

		Assert::true($response->isInvalid());
		Assert::equal('20.12.2018', $response->getRegistryUpdated()->format('d.m.Y'));
		Assert::equal('24.07.2008', $response->getInvalidSince()->format('d.m.Y'));
	}

	/**
	 * @throws AsisTeam\MVCR\DocumentValidator\Exception\ResponseException Response marked as invalid. Error: text chyby
	 */
	public function testValidateError(): void
	{
		$validator = new Validator($this->createRequester('error.xml'));
		$validator->validate(new Document('123456AB', DocumentType::PERSONAL_ID_CARD));
	}

	private function createRequester(string $filename): IRequester
	{
		return Mockery::mock(IRequester::class)
			->shouldReceive('get')->once()
			->andReturn(file_get_contents(__DIR__ . '/responses/' . $filename))
			->getMock();
	}

}

(new ValidatorTest())->run();
