<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle;

use AwdEs\EsLibMakerBundle\DependencyInjection\CompilerPass\MakingProcessorCaseCompilerPass;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class EsLibMakerBundle extends AbstractBundle
{
    #[\Override]
    public function prependExtension(ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder
            ->registerForAutoconfiguration(MakingProcessorCase::class)
            ->addTag(MakingProcessorCaseCompilerPass::TAG)
        ;
    }

    #[\Override]
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        // Add compiler passes
        $container->addCompilerPass(new MakingProcessorCaseCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION);
    }
}
