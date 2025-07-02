<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity;

use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessor;
use AwdEs\EsLibMakerBundle\Maker\Shared\AggregateRootInteractor;
use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

final class AwdEsEntityMaker extends AbstractMaker
{
    public const string SELF_ROOT = '-';

    public function __construct(
        private readonly MakingProcessor $processor,
        private readonly AggregateRootInteractor $aggregateRootInteractorInteractor,
        private readonly EntityMakingConfigurator $configurator,
    ) {}

    #[\Override]
    public function interact(InputInterface $input, ConsoleStyle $io, Command $command): void
    {
        $providedAggregateRootValue = $this->asString($input->getArgument('aggregate-root'));
        $aggregateRootValue = $this->aggregateRootInteractorInteractor->interact($input, $io->getOutput(), $providedAggregateRootValue);

        $input->setArgument('aggregate-root', $aggregateRootValue);
    }

    #[\Override]
    public function configureCommand(Command $command, InputConfiguration $inputConfig): void
    {
        $command->addArgument(
            name: 'entity-name',
            mode: InputArgument::OPTIONAL,
            description: 'Enter a full-qualified class name for the aggregate, without the "Domain" suffix (e.g. <fg=yellow>Foo\FooAggregate</>)',
        );

        $command->addArgument(
            name: 'aggregate-root',
            mode: InputArgument::OPTIONAL,
            description: \sprintf(
                'The root for the aggregate ("%s" to configure the aggregate root itself)',
                self::SELF_ROOT,
            ),
        );

        $command->addOption(
            name: 'machine-name',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Enter a unique name of the aggregate (e.g. <fg=yellow>FOO_AGGREGATE</>)',
            default: '',
        );

        $command->addOption(
            name: 'main-value-type',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Enter a type for the main value (e.g. <fg=yellow>string</>)',
            default: 'string',
        );

        $command->addOption(
            name: 'main-value-name',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'Enter a name for the main value (e.g. <fg=yellow>value</>)',
            default: 'value',
        );

        $command->addOption(
            name: 'is-simple',
            shortcut: 's',
            mode: InputOption::VALUE_NONE,
            description: 'If entity is simple - the value is of type "boolean" and name "isActive"',
        );

        $command->addOption(
            name: 'deletable',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'If entity might be deleted',
            default: true,
        );

        $command->addOption(
            name: 'restorable',
            mode: InputOption::VALUE_OPTIONAL,
            description: 'If entity might be restored after deleting',
            default: true,
        );

        $inputConfig->setArgumentAsNonInteractive('aggregate-root');
        $inputConfig->setArgumentAsNonInteractive('aggregate-name');
        $inputConfig->setArgumentAsNonInteractive('machine-name');
        $inputConfig->setArgumentAsNonInteractive('is-simple');
    }

    #[\Override]
    public function configureDependencies(DependencyBuilder $dependencies): void {}

    #[\Override]
    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator): void
    {
        $entityName = str_replace([':', '/', '.'], '\\', $this->asString($input->getArgument('entity-name')));
        $aggregateRoot = $this->asString($input->getArgument('aggregate-root'));
        $machineName = $this->asString($input->getOption('machine-name'));
        $isSimple = (bool) $input->getOption('is-simple');
        $isDeletable = (bool) $input->getOption('deletable');
        $isRestorable = (bool) $input->getOption('restorable');
        $mainValueType = true === $isSimple ? 'bool' : $this->asString($input->getOption('main-value-type'));
        $mainValueName = true === $isSimple ? 'isActive' : $this->asString($input->getOption('main-value-name'));

        $config = $this->configurator->configure(
            entityName: $entityName,
            aggregateRoot: $aggregateRoot,
            machineName: $machineName,
            mainValueType: $mainValueType,
            mainValueName: $mainValueName,
            isDeletable: $isDeletable,
            isRestorable: $isRestorable,
        );

        $this->processor->process($config, $generator);

        $generator->writeChanges();

        $io->success('Next: Open your new class and start customizing it.');
    }

    #[\Override]
    public static function getCommandName(): string
    {
        return 'make:es:entity';
    }

    public static function getCommandDescription(): string
    {
        return 'Creates an event-sourcing entity';
    }

    private function asString(mixed $rawValue): string
    {
        if (false === \is_scalar($rawValue)) {
            throw new InvalidArgumentException(message: \sprintf('Values of type "%s" are not allowed!', get_debug_type($rawValue)));
        }

        return (string) $rawValue;
    }
}
