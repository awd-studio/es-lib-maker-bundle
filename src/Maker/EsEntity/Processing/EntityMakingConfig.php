<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing;

use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\NamespaceConfig;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class EntityMakingConfig implements MakingConfig
{
    public const string SELF_ROOT = '-';

    public string $classShortName;
    public string $unprocessedFqn;
    public string $aggregateRootFqn;
    public string $machineName;

    public function __construct(
        string $rawEntityName,
        string $aggregateRootFqn,
        public MainValueConfig $mainValueConfig,
        public NamespaceConfig $namespaceConfig,
        string $machineName = '',
    ) {
        $cleanedEntityName = trim($rawEntityName, '\\');

        $p = strrpos($cleanedEntityName, '\\');
        $this->classShortName = substr($cleanedEntityName, false === $p ? 0 : $p + 1);
        $this->unprocessedFqn = $this->namespaceConfig->domain() . '\\' . $this->classShortName;
        $this->aggregateRootFqn = self::SELF_ROOT === $aggregateRootFqn ? $this->unprocessedFqn : $aggregateRootFqn;

        $this->machineName = '' === $machineName ? $this->processMachineName($rawEntityName) : $machineName;
    }

    #[\Override]
    public function namespaceConfig(): NamespaceConfig
    {
        return $this->namespaceConfig;
    }

    public function isSelfRoot(): bool
    {
        return $this->unprocessedFqn === $this->aggregateRootFqn;
    }

    public function generateFqn(Generator $generator): string
    {
        $details = $generator->createClassNameDetails($this->unprocessedFqn, '', '');

        return $details->getFullName();
    }

    private function processMachineName(string $rawEntityName): string
    {
        $nameWithoutDelimiters = str_replace('\\', '', $rawEntityName);

        if ('' === str_replace($this->classShortName, '', $nameWithoutDelimiters)) {
            $value = $this->classShortName;
        } else {
            $value = $nameWithoutDelimiters;
        }

        $value .= true === $this->isSelfRoot() && false === str_ends_with('Root', $value) ? 'Root' : '';

        return $this->toUpperCamelCase($value);
    }

    private function toUpperCamelCase(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/[^a-zA-Z0-9_]/u', '_', $value);
        $value = preg_replace('/(?<=\w)([A-Z])/', '_$1', (string) $value);
        $value = preg_replace('/_{2,}/', '_', (string) $value);

        return mb_strtoupper((string) $value);
    }
}
