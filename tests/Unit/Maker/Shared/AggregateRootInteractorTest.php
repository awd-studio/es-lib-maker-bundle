<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Unit\Maker\Shared;

use AwdEs\Aggregate\AggregateRoot;
use AwdEs\EsLibMakerBundle\Maker\Shared\AggregateRootInteractor;
use AwdEs\EsLibMakerBundle\Tests\Shared\AppTestCase;
use AwdEs\Registry\Entity\EntityRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;

use function PHPUnit\Framework\assertSame;

/**
 * @internal
 */
#[CoversClass(AggregateRootInteractor::class)]
#[CoversMethod(AggregateRootInteractor::class, 'interact')]
final class AggregateRootInteractorTest extends AppTestCase
{
    private readonly InputInterface $input;
    private readonly OutputInterface $output;
    private readonly AggregateRootInteractor $interactor;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $entityRegistry = $this->prophesize(EntityRegistry::class);
        $this->input = new ArrayInput([]);
        $this->output = new BufferedOutput();
        $this->interactor = new AggregateRootInteractor($entityRegistry->reveal());
    }

    public function testShouldReturnSelfRootDeterminerWhenProvidedValueIsSelfRootDeterminer(): void
    {
        // Act
        $result = $this->interactor->interact($this->input, $this->output, '-');

        // Assert
        assertSame('-', $result);
    }

    /**
     * This test verifies that the interact method returns the provided value when it's a valid aggregate root.
     */
    public function testShouldReturnValidAggregateRootWhenProvidedValueIsValid(): void
    {
        // Arrange
        // Use a real class that implements AggregateRoot
        $aggregateRootFqn = Stub\ConcreteAggregateRoot::class;
        $machineName = 'concreteaggregateroot';

        // Create a mock of EntityRegistry
        $registry = $this->prophesize(EntityRegistry::class);

        // Make it iterable and return appropriate values
        // The AggregateRootInteractor iterates over the registry
        // We need to include the ConcreteAggregateRoot class in the iterator
        // because it will be checked with is_subclass_of
        $registry->getIterator()->willReturn(new \ArrayIterator([
            $machineName => $aggregateRootFqn,
        ]));

        // Create the interactor with the registry
        $interactor = new AggregateRootInteractor($registry->reveal());

        // Act
        // Provide the machine name of the aggregate root to the interact method
        $result = $interactor->interact($this->input, $this->output, $machineName);

        // Assert
        // The method should return the FQN of the aggregate root
        assertSame($aggregateRootFqn, $result);
    }

    /**
     * This test verifies that the interact method returns the provided value when it's a valid aggregate root FQN.
     */
    public function testShouldReturnValidAggregateRootWhenProvidedValueIsFqn(): void
    {
        // Arrange
        // Use a real class that implements AggregateRoot
        $aggregateRootFqn = Stub\ConcreteAggregateRoot::class;
        $machineName = 'concreteaggregateroot';

        // Create a mock of EntityRegistry
        $registry = $this->prophesize(EntityRegistry::class);

        // Make it iterable and return appropriate values
        $registry->getIterator()->willReturn(new \ArrayIterator([
            $machineName => $aggregateRootFqn,
        ]));

        // Create the interactor with the registry
        $interactor = new AggregateRootInteractor($registry->reveal());

        // Act
        // Provide the FQN of the aggregate root to the interact method
        $result = $interactor->interact($this->input, $this->output, $aggregateRootFqn);

        // Assert
        // The method should return the FQN of the aggregate root
        assertSame($aggregateRootFqn, $result);
    }

    /**
     * This test verifies that the interact method returns the provided value when it's a valid aggregate root short name.
     */
    public function testShouldReturnValidAggregateRootWhenProvidedValueIsShortName(): void
    {
        // Arrange
        // Use a real class that implements AggregateRoot
        $aggregateRootFqn = Stub\ConcreteAggregateRoot::class;
        $machineName = 'concreteaggregateroot';
        $shortName = 'ConcreteAggregateRoot';

        // Create a mock of EntityRegistry
        $registry = $this->prophesize(EntityRegistry::class);

        // Make it iterable and return appropriate values
        $registry->getIterator()->willReturn(new \ArrayIterator([
            $machineName => $aggregateRootFqn,
        ]));

        // Create the interactor with the registry
        $interactor = new AggregateRootInteractor($registry->reveal());

        // Act
        // Provide the short name of the aggregate root to the interact method
        $result = $interactor->interact($this->input, $this->output, $shortName);

        // Assert
        // The method should return the FQN of the aggregate root
        assertSame($aggregateRootFqn, $result);
    }

    /**
     * This test verifies that the interact method throws an exception when the provided value is not a valid aggregate root.
     */
    public function testShouldThrowExceptionWhenProvidedValueIsNotValid(): void
    {
        // Arrange
        // Use a real class that does not implement AggregateRoot
        $nonAggregateRootFqn = Stub\NonAggregateRoot::class;
        $machineName = 'nonaggregateroot';

        // Create a mock of EntityRegistry
        $registry = $this->prophesize(EntityRegistry::class);

        // Make it iterable and return appropriate values
        // The AggregateRootInteractor iterates over the registry
        // We need to include the NonAggregateRoot class in the iterator
        // but it will be skipped because it's not a subclass of AggregateRoot
        $registry->getIterator()->willReturn(new \ArrayIterator([
            $machineName => $nonAggregateRootFqn,
        ]));

        // Create the interactor with the registry
        $interactor = new AggregateRootInteractor($registry->reveal());

        // Assert
        // The method should throw an InvalidArgumentException when provided with a non-aggregate root
        $this->expectException(InvalidArgumentException::class);

        // Act
        $interactor->interact($this->input, $this->output, $machineName);
    }
}
