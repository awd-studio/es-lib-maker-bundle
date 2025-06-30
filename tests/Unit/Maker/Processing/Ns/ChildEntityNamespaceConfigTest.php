<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\Processing\Ns;

use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\ChildEntityNamespaceConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

use function PHPUnit\Framework\assertSame;

/**
 * @internal
 */
#[CoversClass(ChildEntityNamespaceConfig::class)]
final class ChildEntityNamespaceConfigTest extends AppTestCase
{
    public function testMustReturnProperConfigForTwoLeveledNames(): void
    {
        $instance = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');

        assertSame('App\Foo\Application\Entity\Baz', $instance->application);
        assertSame('App\Foo\Domain\Entity\Baz', $instance->domain);
        assertSame('App\Foo\Infrastructure\Entity\Baz', $instance->infrastructure);
        assertSame('App\Foo\Ui\Entity\Baz', $instance->ui);
    }

    public function testMustReturnProperConfigForSameNamesWithNs(): void
    {
        $instance = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz\FooBarBaz', 'App\Foo\Domain\Bar', 'App');

        assertSame('App\Foo\Application\Entity\Baz', $instance->application);
        assertSame('App\Foo\Domain\Entity\Baz', $instance->domain);
        assertSame('App\Foo\Infrastructure\Entity\Baz', $instance->infrastructure);
        assertSame('App\Foo\Ui\Entity\Baz', $instance->ui);
    }
}
