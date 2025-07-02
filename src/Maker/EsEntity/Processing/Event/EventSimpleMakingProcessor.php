<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Event;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class EventSimpleMakingProcessor implements MakingProcessorCase
{
    public const string TPL_WAS_ACTIVATED = __DIR__ . '/templates/WasActivated.tpl.php';
    public const string TPL_WAS_DEACTIVATED = __DIR__ . '/templates/WasDeactivated.tpl.php';

    private const string SUFFIX_PATH = 'Event';

    public function __construct(
        private string $pathTplWasActivated = self::TPL_WAS_ACTIVATED,
        private string $pathTplWasDeactivated = self::TPL_WAS_DEACTIVATED,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        if (false === $config->mainValueConfig->isSimple()) {
            return;
        }

        $activationVars = [
            'entity_fqn' => $config->generateFqn($generator),
            'entity_short_name' => $config->classShortName,
            'entity_machine_name' => $config->machineName,
        ];

        $ns = $config->namespaceConfig;
        $classNameWithoutSuffix = $ns->domain() . '\\' . self::SUFFIX_PATH . '\\' . $config->classShortName;

        $wasActivatedDetails = $generator->createClassNameDetails($classNameWithoutSuffix . 'WasActivated', '');
        $generator->generateClass($wasActivatedDetails->getFullName(), $this->pathTplWasActivated, $activationVars);

        $wasDeactivatedDetails = $generator->createClassNameDetails($classNameWithoutSuffix . 'WasDeactivated', '');
        $generator->generateClass($wasDeactivatedDetails->getFullName(), $this->pathTplWasDeactivated, $activationVars);
    }
}
