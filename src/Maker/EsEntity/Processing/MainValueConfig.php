<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing;

use Awd\ValueObject\IDateTime;
use AwdEs\ValueObject\Id;

final readonly class MainValueConfig
{
    private const string PREFIX_IS = 'is';
    private const string PREFIX_HAS = 'has';

    public const string TYPE_BOOL = 'bool';
    public const string TYPE_STRING = 'string';
    public const string TYPE_INT = 'int';
    public const string TYPE_FLOAT = 'float';
    public const string TYPE_DATETIME = 'IDateTime';
    public const string TYPE_REFERENCE = Id::class;

    private const array TYPES = [
        self::TYPE_BOOL => [
            'bool',
            'boolean',
            'logical',
        ],
        self::TYPE_STRING => [
            'string',
            'str',
            'text',
        ],
        self::TYPE_INT => [
            'int',
            'integer',
            'number',
            'numeric',
            'digit',
            'digits',
        ],
        self::TYPE_FLOAT => [
            'float',
            'double',
            'decimal',
        ],
        self::TYPE_DATETIME => [
            IDateTime::class,
            'IDateTime',
            'datetime',
            'date',
            'time',
        ],
        self::TYPE_REFERENCE => [
            Id::class,
            'reference',
            'ref',
            'link',
            'assignment',
        ],
    ];

    public function __construct(
        public string $name = 'value',
        public string $type = self::TYPE_BOOL,
    ) {}

    public static function fromRawType(string $rawType, string $valueName = 'value'): self
    {
        $valueNameProcessed = $valueName;

        $rawTypeLowered = mb_strtolower($rawType);

        $valueTypeProcessed = self::TYPE_BOOL;
        foreach (self::TYPES as $typeName => $aliases) {
            foreach ($aliases as $alias) {
                if ($rawTypeLowered === mb_strtolower($alias)) {
                    $valueTypeProcessed = $typeName;
                    break 2;
                }
            }
        }

        if (
            self::TYPE_BOOL === $valueTypeProcessed
            && false === str_starts_with($valueName, self::PREFIX_IS)
            && false === str_starts_with($valueName, self::PREFIX_HAS)
        ) {
            $valueNameProcessed = self::PREFIX_IS . mb_ucfirst($valueNameProcessed);
        }

        return new self($valueNameProcessed, $valueTypeProcessed);
    }

    public function isSimple(): bool
    {
        return self::TYPE_BOOL === $this->type;
    }
}
