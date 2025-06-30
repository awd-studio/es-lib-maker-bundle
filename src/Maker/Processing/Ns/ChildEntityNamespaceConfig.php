<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing\Ns;

final readonly class ChildEntityNamespaceConfig implements NamespaceConfig
{
    private const string DOMAIN_REPLACEMENT = '{{ DOMAIN }}';

    public function __construct(
        public string $application,
        public string $domain,
        public string $infrastructure,
        public string $ui,
    ) {}

    public static function fromRawEntityNameAndAggregateRoot(string $rawEntityName, string $aggregateRootFqn, string $nsPrefix): NamespaceConfig
    {
        $arFqnWithoutNsPrefix = trim(str_replace($nsPrefix, '', $aggregateRootFqn), '\\');
        $arNs = explode('\\', $arFqnWithoutNsPrefix);
        array_pop($arNs);

        $cleanedEntityName = trim($rawEntityName, '\\');
        $p = strrpos($cleanedEntityName, '\\');
        $entityShortName = substr($cleanedEntityName, false === $p ? 0 : $p + 1);

        $s = trim(str_replace($entityShortName, '', $cleanedEntityName), '\\');
        $cn = str_replace('\\', '', $s);

        if ($cn === $entityShortName) {
            $a = explode('\\', $s);
            $entityName = array_pop($a);
        } else {
            $entityName = $entityShortName;
        }

        $ns = implode('\\', array_map(static fn(string $v) => self::NS_DOMAIN === $v ? self::DOMAIN_REPLACEMENT : $v, [...$arNs, 'Entity', $entityName]));

        return new self(
            $nsPrefix . '\\' . str_replace(self::DOMAIN_REPLACEMENT, self::NS_APPLICATION, $ns),
            $nsPrefix . '\\' . str_replace(self::DOMAIN_REPLACEMENT, self::NS_DOMAIN, $ns),
            $nsPrefix . '\\' . str_replace(self::DOMAIN_REPLACEMENT, self::NS_INFRASTRUCTURE, $ns),
            $nsPrefix . '\\' . str_replace(self::DOMAIN_REPLACEMENT, self::NS_UI, $ns),
        );
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
