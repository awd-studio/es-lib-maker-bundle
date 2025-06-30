<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\DependencyInjection\CompilerPass;

use AwdEs\EsLibMakerBundle\Maker\Processing\CasesMakingProcessor;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

final readonly class MakingProcessorCaseCompilerPass implements CompilerPassInterface
{
    public const string TAG = 'awd_es.maker.processor.case';

    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(CasesMakingProcessor::class)) {
            return;
        }

        $definition = $container->findDefinition(CasesMakingProcessor::class);
        $taggedServices = $container->findTaggedServiceIds(self::TAG);

        $cases = [];
        foreach ($taggedServices as $id => $tags) {
            $cases[] = new Reference($id);
        }

        $definition->setArgument('$cases', $cases);
    }
}
