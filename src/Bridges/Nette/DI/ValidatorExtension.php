<?php declare(strict_types = 1);

namespace AsisTeam\MVCR\DocumentValidator\Bridges\Nette\DI;

use AsisTeam\MVCR\DocumentValidator\Client\Requester;
use AsisTeam\MVCR\DocumentValidator\Client\Validator;
use Nette\DI\CompilerExtension;

final class ValidatorExtension extends CompilerExtension
{

	/** @var int[] */
	public $defaults = [
		'timeout' => 10,
	];

	/**
	 * @inheritDoc
	 */
	public function loadConfiguration(): void
	{
		$config = $this->validateConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('requester'))
			->setFactory(Requester::class, [$config['timeout']]);

		$builder->addDefinition($this->prefix('service'))
			->setFactory(Validator::class);
	}

}
