<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\EsEntity\Processing\Entity;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Entity\EntityMakingProcessor;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\MainValueConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\Ns\ChildEntityNamespaceConfig;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Prophecy\Argument;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

/**
 * @internal
 */
#[CoversClass(EntityMakingProcessor::class)]
#[CoversMethod(EntityMakingProcessor::class, 'process')]
final class EntityMakingProcessorTest extends AppTestCase
{
    public function testShouldNotProcessIfConfigIsNotEntityMakingConfig(): void
    {
        // Arrange
        $processor = new EntityMakingProcessor();
        $config = $this->prophesize(MakingConfig::class);
        $generator = $this->prophesize(Generator::class);

        // Act
        $processor->process($config->reveal(), $generator->reveal());

        // Assert
        // The generator should not be called if the config is not an EntityMakingConfig
        $generator->createClassNameDetails(Argument::any(), Argument::any(), Argument::any())->shouldNotHaveBeenCalled();
        $generator->generateClass(Argument::any(), Argument::any(), Argument::any())->shouldNotHaveBeenCalled();
    }

    public function testShouldProcessEntityMakingConfig(): void
    {
        // Arrange
        $processor = new EntityMakingProcessor();

        // Create a real EntityMakingConfig
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig('Foo\Bar\Baz', 'App\Foo\Domain\Bar', $mainValueConfig, $nsConfig);

        // Mock the Generator
        $generator = $this->prophesize(Generator::class);

        // Create a ClassNameDetails instance
        $classNameDetails = new ClassNameDetails('App\Foo\Domain\Entity\Baz\Baz', '', '');

        // Set up expectations
        $generator->createClassNameDetails($entityConfig->unprocessedFqn, '', '')
            ->willReturn($classNameDetails)
            ->shouldBeCalled()
        ;

        $generator->generateClass(
            'App\Foo\Domain\Entity\Baz\Baz',
            EntityMakingProcessor::TPL,
            [
                'is_self_root' => $entityConfig->isSelfRoot(),
                'machine_name' => $entityConfig->machineName,
                'agg_root_full' => $entityConfig->aggregateRootFqn,
                'agg_root_short' => Str::getShortClassName($entityConfig->aggregateRootFqn),
                'is_simple' => $entityConfig->mainValueConfig->isSimple(),
                'main_value_type' => $entityConfig->mainValueConfig->type,
                'main_value_name' => $entityConfig->mainValueConfig->name,
                'is_deletable' => $entityConfig->isDeletable,
                'is_restorable' => $entityConfig->isRestorable,
            ],
        )->shouldBeCalled();

        // Act
        $processor->process($entityConfig, $generator->reveal());
    }

    public function testShouldPassCorrectParametersToGenerator(): void
    {
        // Arrange
        $processor = new EntityMakingProcessor();

        // Create a real EntityMakingConfig with isDeletable and isRestorable set to true
        $nsConfig = ChildEntityNamespaceConfig::fromRawEntityNameAndAggregateRoot('Foo\Bar\Baz', 'App\Foo\Domain\Bar', 'App');
        $mainValueConfig = MainValueConfig::fromRawType('string', 'name');
        $entityConfig = new EntityMakingConfig(
            'Foo\Bar\Baz',
            'App\Foo\Domain\Bar',
            $mainValueConfig,
            $nsConfig,
            '',
            true,
            true,
        );

        // Mock the Generator
        $generator = $this->prophesize(Generator::class);

        // Create a ClassNameDetails instance
        $classNameDetails = new ClassNameDetails('App\Foo\Domain\Entity\Baz\Baz', '', '');

        // Set up expectations
        $generator->createClassNameDetails($entityConfig->unprocessedFqn, '', '')
            ->willReturn($classNameDetails)
            ->shouldBeCalled()
        ;

        // The key part of this test is to verify that the processor is using the hardcoded false values
        // for is_deletable and is_restorable instead of the values from the config
        $generator->generateClass(
            'App\Foo\Domain\Entity\Baz\Baz',
            EntityMakingProcessor::TPL,
            [
                'is_self_root' => $entityConfig->isSelfRoot(),
                'machine_name' => $entityConfig->machineName,
                'agg_root_full' => $entityConfig->aggregateRootFqn,
                'agg_root_short' => Str::getShortClassName($entityConfig->aggregateRootFqn),
                'is_simple' => $entityConfig->mainValueConfig->isSimple(),
                'main_value_type' => $entityConfig->mainValueConfig->type,
                'main_value_name' => $entityConfig->mainValueConfig->name,
                'is_deletable' => $entityConfig->isDeletable,
                'is_restorable' => $entityConfig->isRestorable,
            ],
        )->shouldBeCalled();

        // Act
        $processor->process($entityConfig, $generator->reveal());
    }
}
