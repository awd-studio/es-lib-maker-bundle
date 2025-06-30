<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\Processing\Ns;

use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\SelfRootNamespaceConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;

use function PHPUnit\Framework\assertSame;

/**
 * @internal
 */
#[CoversClass(SelfRootNamespaceConfig::class)]
#[CoversMethod(SelfRootNamespaceConfig::class, 'fromRawEntityName')]
final class SelfRootNamespaceConfigTest extends AppTestCase
{
    public function testMustReturnProperConfigForSingleLeveledNames(): void
    {
        $instance = SelfRootNamespaceConfig::fromRawEntityName('Foo', 'App');

        assertSame('App\Foo\Application', $instance->application);
        assertSame('App\Foo\Domain', $instance->domain);
        assertSame('App\Foo\Infrastructure', $instance->infrastructure);
        assertSame('App\Foo\Ui', $instance->ui);
    }

    public function testMustReturnProperConfigForTwoLeveledNames(): void
    {
        $instance = SelfRootNamespaceConfig::fromRawEntityName('Foo\Bar', 'App');

        assertSame('App\Foo\Application', $instance->application);
        assertSame('App\Foo\Domain', $instance->domain);
        assertSame('App\Foo\Infrastructure', $instance->infrastructure);
        assertSame('App\Foo\Ui', $instance->ui);
    }
}
