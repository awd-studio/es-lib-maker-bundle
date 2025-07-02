<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Event;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class EventNotSimpleMakingProcessor implements MakingProcessorCase
{
    public const string TPL_WAS_CHANGED = __DIR__ . '/templates/WasChanged.tpl.php';

    private const string SUFFIX_PATH = 'Event';

    public function __construct(
        private string $pathTplWasActivated = self::TPL_WAS_CHANGED,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        if (true === $config->mainValueConfig->isSimple()) {
            return;
        }

        $ns = $config->namespaceConfig;
        $classNameWithoutSuffix = $ns->domain() . '\\' . self::SUFFIX_PATH . '\\' . $config->classShortName;

        $wasChangedDetails = $generator->createClassNameDetails($classNameWithoutSuffix, '', 'WasChanged');
        $generator->generateClass($wasChangedDetails->getFullName(), $this->pathTplWasActivated, [
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
            'entity_fqn' => $config->generateFqn($generator),
            'entity_short_name' => $config->classShortName,
            'entity_machine_name' => $config->machineName,
        ]);
    }
}
