<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\EsEntity\Processing;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\MainValueConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\SelfRootNamespaceConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;

use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * @internal
 */
#[CoversClass(EntityMakingConfig::class)]
#[CoversMethod(EntityMakingConfig::class, 'isSelfRoot')]
final class EntityMakingConfigSelfRootTest extends AppTestCase
{
    public function testShouldHandleProperlySingleNames(): void
    {
        $nsConfig = SelfRootNamespaceConfig::fromRawEntityName('Foo', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo', '-', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Domain\Foo', $entityConfig->unprocessedFqn);
        assertSame('Foo', $entityConfig->classShortName);
        assertSame('FOO_ROOT', $entityConfig->machineName);
        assertSame('App\Foo\Domain\Foo', $entityConfig->aggregateRootFqn);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertTrue($entityConfig->isSelfRoot());
    }

    public function testShouldHandleProperlyTwoLevelNames(): void
    {
        $nsConfig = SelfRootNamespaceConfig::fromRawEntityName('Foo\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar', '-', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Domain\Bar', $entityConfig->unprocessedFqn);
        assertSame('Bar', $entityConfig->classShortName);
        assertSame('FOO_BAR_ROOT', $entityConfig->machineName);
        assertSame('App\Foo\Domain\Bar', $entityConfig->aggregateRootFqn);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertTrue($entityConfig->isSelfRoot());
    }

    public function testShouldHandleProperlyMultiLevelNames(): void
    {
        $nsConfig = SelfRootNamespaceConfig::fromRawEntityName('Foo\Bar\Baz', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\Baz', '-', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Bar\Domain\Baz', $entityConfig->unprocessedFqn);
        assertSame('Baz', $entityConfig->classShortName);
        assertSame('FOO_BAR_BAZ_ROOT', $entityConfig->machineName);
        assertSame('App\Foo\Bar\Domain\Baz', $entityConfig->aggregateRootFqn);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertTrue($entityConfig->isSelfRoot());
    }

    public function testShouldHandleProperlyCombinedNames(): void
    {
        $nsConfig = SelfRootNamespaceConfig::fromRawEntityName('Foo\Bar\BazQoo', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\BazQoo', '-', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Bar\Domain\BazQoo', $entityConfig->unprocessedFqn);
        assertSame('BazQoo', $entityConfig->classShortName);
        assertSame('FOO_BAR_BAZ_QOO_ROOT', $entityConfig->machineName);
        assertSame('App\Foo\Bar\Domain\BazQoo', $entityConfig->aggregateRootFqn);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertTrue($entityConfig->isSelfRoot());
    }

    public function testShouldHandleProperlyRepeatableNames(): void
    {
        $nsConfig = SelfRootNamespaceConfig::fromRawEntityName('Foo\Bar\FooBar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\FooBar', '-', $mainValueConfig, $nsConfig);

        assertSame('App\Foo\Bar\Domain\FooBar', $entityConfig->unprocessedFqn);
        assertSame('FooBar', $entityConfig->classShortName);
        assertSame('FOO_BAR_ROOT', $entityConfig->machineName);
        assertSame('App\Foo\Bar\Domain\FooBar', $entityConfig->aggregateRootFqn);
        assertSame($mainValueConfig, $entityConfig->mainValueConfig);
        assertSame($nsConfig, $entityConfig->namespaceConfig);
        assertTrue($entityConfig->isSelfRoot());
    }
}
