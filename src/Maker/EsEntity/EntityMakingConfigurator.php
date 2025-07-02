<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\MainValueConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\ChildEntityNamespaceConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\SelfRootNamespaceConfig;

final readonly class EntityMakingConfigurator
{
    public const string SELF_ROOT = '-';

    public function __construct(
        private string $namespacePrefix = '',
    ) {}

    public function configure(
        string $entityName,
        string $aggregateRoot,
        string $machineName,
        string $mainValueType,
        string $mainValueName,
        bool $isDeletable,
        bool $isRestorable,
    ): EntityMakingConfig {
        $mainValueConfig = MainValueConfig::fromRawType($mainValueType, $mainValueName);

        if (self::SELF_ROOT === $aggregateRoot) {
            $nsConfig = SelfRootNamespaceConfig::fromRawEntityName($entityName, $this->namespacePrefix);
        } else {
            $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot($entityName, $aggregateRoot, $this->namespacePrefix);
        }

        return new EntityMakingConfig($entityName, $aggregateRoot, $mainValueConfig, $nsConfig, $machineName, $isDeletable, $isRestorable);
    }
}
