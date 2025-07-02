<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Event;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class EventDeletableRestorableMakingProcessor implements MakingProcessorCase
{
    public const string TPL_WAS_DELETED = __DIR__ . '/templates/WasDeleted.tpl.php';
    public const string TPL_WAS_RESTORED = __DIR__ . '/templates/WasRestored.tpl.php';

    private const string SUFFIX_PATH = 'Event';

    public function __construct(
        private string $pathTplWasDeleted = self::TPL_WAS_DELETED,
        private string $pathTplWasRestored = self::TPL_WAS_RESTORED,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        if (false === $config->isDeletable) {
            return;
        }

        $vars = [
            'entity_fqn' => $config->generateFqn($generator),
            'entity_short_name' => $config->classShortName,
            'entity_machine_name' => $config->machineName,
        ];

        $ns = $config->namespaceConfig;
        $classNameWithoutSuffix = $ns->domain() . '\\' . self::SUFFIX_PATH . '\\' . $config->classShortName;

        $wasDeletedDetails = $generator->createClassNameDetails($classNameWithoutSuffix, '', 'WasDeleted');
        $generator->generateClass($wasDeletedDetails->getFullName(), $this->pathTplWasDeleted, $vars);

        if (false === $config->isRestorable) {
            return;
        }

        $wasRestoredDetails = $generator->createClassNameDetails($classNameWithoutSuffix, '', 'WasRestored');
        $generator->generateClass($wasRestoredDetails->getFullName(), $this->pathTplWasRestored, $vars);
    }
}
