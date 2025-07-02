<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Event;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class EventGeneralMakingProcessor implements MakingProcessorCase
{
    public const string TPL_BASE = __DIR__ . '/templates/BaseEvent.tpl.php';
    public const string TPL_WAS_CREATED = __DIR__ . '/templates/WasCreated.tpl.php';

    private const string SUFFIX_PATH = 'Event';

    public function __construct(
        private string $pathTplBaseEvent = self::TPL_BASE,
        private string $pathTplWasCreated = self::TPL_WAS_CREATED,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        $classNameWithoutSuffix = $config->namespaceConfig->domain() . '\\' . self::SUFFIX_PATH . '\\' . $config->classShortName;

        $baseEventDetails = $generator->createClassNameDetails($classNameWithoutSuffix . 'Event', '');

        $generator->generateClass($baseEventDetails->getFullName(), $this->pathTplBaseEvent, [
            'is_simple' => $config->mainValueConfig->isSimple(),
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
        ]);

        $wasCreatedDetails = $generator->createClassNameDetails($classNameWithoutSuffix . 'WasCreated', '');

        $generator->generateClass($wasCreatedDetails->getFullName(), $this->pathTplWasCreated, [
            'is_simple' => $config->mainValueConfig->isSimple(),
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
            'entity_fqn' => $config->generateFqn($generator),
            'entity_short_name' => $config->classShortName,
            'entity_machine_name' => $config->machineName,
        ]);
    }
}
