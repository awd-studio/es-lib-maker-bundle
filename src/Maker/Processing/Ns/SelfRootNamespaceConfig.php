<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing\Ns;

final readonly class SelfRootNamespaceConfig implements NamespaceConfig
{
    public function __construct(
        public string $application,
        public string $domain,
        public string $infrastructure,
        public string $ui,
    ) {}

    public static function fromRawEntityName(string $rawEntityName, string $nsPrefix): NamespaceConfig
    {
        $cleanedEntityName = trim($rawEntityName, '\\');

        $nsWithPrefix = $cleanedEntityName;
        if ('' !== $nsPrefix && false === str_starts_with($cleanedEntityName, $nsPrefix)) {
            $nsWithPrefix = $nsPrefix . '\\' . $cleanedEntityName;
        }

        $l = strrpos($nsWithPrefix, '\\');
        $fetchedEntityName = substr($nsWithPrefix, false === $l ? 0 : $l + 1);
        $nsWithRemovedEntityName = $fetchedEntityName === $cleanedEntityName ? $nsWithPrefix : substr($nsWithPrefix, 0, false === $l ? 0 : $l);

        $application = $nsWithRemovedEntityName . '\\' . self::NS_APPLICATION;
        $domain = $nsWithRemovedEntityName . '\\' . self::NS_DOMAIN;
        $infrastructure = $nsWithRemovedEntityName . '\\' . self::NS_INFRASTRUCTURE;
        $ui = $nsWithRemovedEntityName . '\\' . self::NS_UI;

        return new self($application, $domain, $infrastructure, $ui);
    }

    #[\Override]
    public function application(): string
    {
        return $this->application;
    }

    #[\Override]
    public function domain(): string
    {
        return $this->domain;
    }

    #[\Override]
    public function infrastructure(): string
    {
        return $this->infrastructure;
    }

    #[\Override]
    public function ui(): string
    {
        return $this->ui;
    }
}
