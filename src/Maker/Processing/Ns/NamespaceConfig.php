<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing\Ns;

interface NamespaceConfig
{
    public const string NS_APPLICATION = 'Application';
    public const string NS_DOMAIN = 'Domain';
    public const string NS_INFRASTRUCTURE = 'Infrastructure';
    public const string NS_UI = 'Ui';

    public function application(): string;

    public function domain(): string;

    public function infrastructure(): string;

    public function ui(): string;
}
