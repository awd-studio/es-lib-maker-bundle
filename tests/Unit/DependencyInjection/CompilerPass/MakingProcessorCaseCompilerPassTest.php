<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\DependencyInjection\CompilerPass;

use AwdEs\EsLibMakerBundle\DependencyInjection\CompilerPass\MakingProcessorCaseCompilerPass;
use AwdEs\EsLibMakerBundle\Maker\Processing\CasesMakingProcessor;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @internal
 */
#[CoversClass(MakingProcessorCaseCompilerPass::class)]
final class MakingProcessorCaseCompilerPassTest extends AppTestCase
{
    private ContainerBuilder|ObjectProphecy $container;
    private Definition|ObjectProphecy $definition;
    private MakingProcessorCaseCompilerPass $compilerPass;

    #[\Override]
    protected function setUp(): void
    {
        $this->container = $this->prophesize(ContainerBuilder::class);
        $this->definition = $this->prophesize(Definition::class);
        $this->compilerPass = new MakingProcessorCaseCompilerPass();
    }

    public function testProcessShouldDoNothingWhenCasesMakingProcessorIsNotDefined(): void
    {
        $this->container->has(CasesMakingProcessor::class)->willReturn(false);
        $this->container->findDefinition(CasesMakingProcessor::class)->shouldNotBeCalled();

        $this->compilerPass->process($this->container->reveal());
    }

    public function testProcessShouldCollectTaggedServicesAndSetThemAsArgument(): void
    {
        $taggedServices = [
            'service1' => [['tag' => 'value1']],
            'service2' => [['tag' => 'value2']],
        ];

        $this->container->has(CasesMakingProcessor::class)->willReturn(true);
        $this->container->findDefinition(CasesMakingProcessor::class)->willReturn($this->definition->reveal());
        $this->container->findTaggedServiceIds(MakingProcessorCaseCompilerPass::TAG)->willReturn($taggedServices);

        $this->definition->setArgument('$cases', [
            new Reference('service1'),
            new Reference('service2'),
        ])->willReturn($this->definition->reveal());

        $this->compilerPass->process($this->container->reveal());

        $this->container->has(CasesMakingProcessor::class)->shouldHaveBeenCalled();
        $this->container->findDefinition(CasesMakingProcessor::class)->shouldHaveBeenCalled();
        $this->container->findTaggedServiceIds(MakingProcessorCaseCompilerPass::TAG)->shouldHaveBeenCalled();
        $this->definition->setArgument('$cases', [
            new Reference('service1'),
            new Reference('service2'),
        ])->shouldHaveBeenCalled();
    }

    public function testProcessShouldHandleEmptyTaggedServices(): void
    {
        $this->container->has(CasesMakingProcessor::class)->willReturn(true);
        $this->container->findDefinition(CasesMakingProcessor::class)->willReturn($this->definition->reveal());
        $this->container->findTaggedServiceIds(MakingProcessorCaseCompilerPass::TAG)->willReturn([]);

        $this->definition->setArgument('$cases', [])->willReturn($this->definition->reveal());

        $this->compilerPass->process($this->container->reveal());

        $this->container->has(CasesMakingProcessor::class)->shouldHaveBeenCalled();
        $this->container->findDefinition(CasesMakingProcessor::class)->shouldHaveBeenCalled();
        $this->container->findTaggedServiceIds(MakingProcessorCaseCompilerPass::TAG)->shouldHaveBeenCalled();
        $this->definition->setArgument('$cases', [])->shouldHaveBeenCalled();
    }
}
