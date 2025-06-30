<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class EsLibMakerBundle extends AbstractBundle
{
    /**
     * @phpstan-ignore missingType.iterableValue
     */
    #[\Override]
    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        // Load services from the services.yaml file
        $container->import('../config/services.yaml');
    }
}
