<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Entity;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;

final readonly class EntityMakingProcessor implements MakingProcessorCase
{
    public const string TPL = __DIR__ . '/templates/Entity.tpl.php';

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        $entityDetails = $generator->createClassNameDetails($config->unprocessedFqn, '', '');

        $generator->generateClass($entityDetails->getFullName(), self::TPL, [
            'is_self_root' => $config->isSelfRoot(),
            'machine_name' => $config->machineName,
            'agg_root_full' => $config->aggregateRootFqn,
            'agg_root_short' => Str::getShortClassName($config->aggregateRootFqn),
            'is_simple' => $config->mainValueConfig->isSimple(),
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
            'is_deletable' => false,
            'is_restorable' => false,
        ]);
    }
}
