<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing;

use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\NamespaceConfig;

interface MakingConfig
{
    public function namespaceConfig(): NamespaceConfig;
}
