<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\EsEntity\Processing;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\MainValueConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\ChildEntityNamespaceConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertSame;

/**
 * @internal
 */
#[CoversClass(EntityMakingConfig::class)]
#[CoversMethod(EntityMakingConfig::class, 'isSelfRoot')]
final class EntityMakingConfigChildrenEntitiesTest extends AppTestCase
{
    public function testShouldHandleProperlySinglePartName(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\Baz', 'App\Foo\Domain\Bar', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Domain\Entity\Baz\Baz', $entityConfig->unprocessedFqn);
        assertSame('App\Foo\Domain\Bar', $entityConfig->aggregateRootFqn);
        assertSame('Baz', $entityConfig->classShortName);
        assertSame('FOO_BAR_BAZ', $entityConfig->machineName);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertFalse($entityConfig->isSelfRoot());
    }

    public function testShouldHandleProperlySamePartsName(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz\FooBarBaz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\Baz\FooBarBaz', 'App\Foo\Domain\Bar', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Domain\Entity\Baz\FooBarBaz', $entityConfig->unprocessedFqn);
        assertSame('App\Foo\Domain\Bar', $entityConfig->aggregateRootFqn);
        assertSame('FooBarBaz', $entityConfig->classShortName);
        assertSame('FOO_BAR_BAZ', $entityConfig->machineName);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertFalse($entityConfig->isSelfRoot());
    }
}
