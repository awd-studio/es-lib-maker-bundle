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
use function PHPUnit\Framework\assertTrue;

/**
 * @internal
 */
#[CoversClass(EntityMakingConfig::class)]
#[CoversMethod(EntityMakingConfig::class, '__construct')]
final class EntityMakingConfigDeletableRestorableTest extends AppTestCase
{
    public function testShouldHaveDefaultValuesForDeletableAndRestorable(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\Baz', 'App\Foo\Domain\Bar', $mainValueConfig, $nsConfig);

        // By default, both properties should be false
        assertFalse($entityConfig->isDeletable);
        assertFalse($entityConfig->isRestorable);
    }

    public function testShouldAllowSettingDeletableAndRestorable(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig(
            'Foo\Bar\Baz',
            'App\Foo\Domain\Bar',
            $mainValueConfig,
            $nsConfig,
            '',
            true,
            true,
        );

        // Both properties should be true as set in the constructor
        assertTrue($entityConfig->isDeletable);
        assertTrue($entityConfig->isRestorable);
    }

    public function testShouldAllowSettingOnlyDeletable(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig(
            'Foo\Bar\Baz',
            'App\Foo\Domain\Bar',
            $mainValueConfig,
            $nsConfig,
            '',
            true,
            false,
        );

        // Only isDeletable should be true
        assertTrue($entityConfig->isDeletable);
        assertFalse($entityConfig->isRestorable);
    }

    public function testShouldAllowSettingOnlyRestorable(): void
    {
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig(
            'Foo\Bar\Baz',
            'App\Foo\Domain\Bar',
            $mainValueConfig,
            $nsConfig,
            '',
            false,
            true,
        );

        // Only isRestorable should be true
        assertFalse($entityConfig->isDeletable);
        assertTrue($entityConfig->isRestorable);
    }
}
