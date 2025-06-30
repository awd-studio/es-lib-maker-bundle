<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing;

use Symfony\Bundle\MakerBundle\Generator;

interface MakingProcessorCase
{
    public function process(MakingConfig $config, Generator $generator): void;
}
