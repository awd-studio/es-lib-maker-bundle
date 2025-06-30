<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\EsEntity\Processing;

use Awd\ValueObject\IDateTime;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\MainValueConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use AwdEs\ValueObject\Id;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\DataProvider;

use function PHPUnit\Framework\assertSame;

/**
 * @internal
 */
#[CoversClass(MainValueConfig::class)]
#[CoversMethod(MainValueConfig::class, 'fromRawType')]
final class MainValueConfigTest extends AppTestCase
{
    public function testMustCreateWithDefaultValues(): void
    {
        $config = new MainValueConfig();

        assertSame('value', $config->name);
        assertSame('bool', $config->type);
    }

    public function testMustCreateWithCustomValues(): void
    {
        $config = new MainValueConfig('customName', MainValueConfig::TYPE_STRING);

        assertSame('customName', $config->name);
        assertSame('string', $config->type);
    }

    #[DataProvider('provideRawTypeData')]
    public function testMustCreateFromRawTypeAndProcessValues(
        string $rawType,
        string $valueName,
        string $expectedType,
        string $expectedName,
    ): void {
        $config = MainValueConfig::fromRawType($rawType, $valueName);

        assertSame($expectedType, $config->type);
        assertSame($expectedName, $config->name);
    }

    /**
     * @return array<string, array{string, string, string, string}>
     */
    public static function provideRawTypeData(): iterable
    {
        return [
            'boolean type with is prefix' => [
                'bool',
                'isActive',
                MainValueConfig::TYPE_BOOL,
                'isActive',
            ],
            'boolean type without is prefix' => [
                'bool',
                'active',
                MainValueConfig::TYPE_BOOL,
                'isActive',
            ],
            'string type' => [
                'string',
                'name',
                MainValueConfig::TYPE_STRING,
                'name',
            ],
            'int type with alias' => [
                'numeric',
                'count',
                MainValueConfig::TYPE_INT,
                'count',
            ],
            'float type' => [
                'decimal',
                'price',
                MainValueConfig::TYPE_FLOAT,
                'price',
            ],
            'datetime type' => [
                'datetime',
                'createdAt',
                MainValueConfig::TYPE_DATETIME,
                'createdAt',
            ],
            'reference type' => [
                'ref',
                'parentId',
                MainValueConfig::TYPE_REFERENCE,
                'parentId',
            ],
            'has prefix name' => [
                'bool',
                'hasChildren',
                MainValueConfig::TYPE_BOOL,
                'hasChildren',
            ],
            'unknown type defaults to bool' => [
                'unknown',
                'value',
                MainValueConfig::TYPE_BOOL,
                'isValue',
            ],
        ];
    }

    public function testMustRecognizeIDateTimeType(): void
    {
        $config = MainValueConfig::fromRawType(IDateTime::class);
        assertSame(MainValueConfig::TYPE_DATETIME, $config->type);
    }

    public function testMustRecognizeIdType(): void
    {
        $config = MainValueConfig::fromRawType(Id::class);
        assertSame(MainValueConfig::TYPE_REFERENCE, $config->type);
    }
}
