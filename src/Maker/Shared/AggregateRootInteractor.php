<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Shared;

use AwdEs\Aggregate\AggregateRoot;
use AwdEs\Registry\Entity\EntityRegistry;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;

final readonly class AggregateRootInteractor
{
    public const string SELF_ROOT = '-';

    public function __construct(
        private EntityRegistry $entities,
    ) {}

    public function interact(
        InputInterface $input,
        OutputInterface $output,
        ?string $providedValue,
        string $selfRootDeterminer = self::SELF_ROOT,
    ): string {
        if ($selfRootDeterminer === $providedValue) {
            return $providedValue;
        }

        $aggregateRootValue = $providedValue;
        $style = new SymfonyStyle($input, $output);

        /** @var array<string, string> $arList */
        $arList = [];
        $arList[$selfRootDeterminer] = $selfRootDeterminer;

        foreach ($this->entities as $entityMachineName => $entityFqn) {
            if (false === is_subclass_of($entityFqn, AggregateRoot::class)) {
                continue;
            }

            $p = strrpos($entityFqn, '\\');
            $shortName = substr($entityFqn, false === $p ? 0 : $p + 1, \strlen($entityFqn));

            $arList[$shortName] = $entityFqn;
            $arList[$entityMachineName] = $entityFqn;
            $arList[$entityFqn] = $entityFqn;
        }

        $questionMessage = 'Enter an aggregate root (e.g. <fg=yellow>Foo\FooAggregate</>), put "-" for make it self-root:';
        if ('' === $aggregateRootValue || null === $aggregateRootValue) {
            $style->writeln(' <fg=green>Suggested Aggregate Roots:</>');
            $style->listing(array_values($arList));

            $question = new Question(\sprintf('<fg=green>%s</>', $questionMessage));
            $question->setAutocompleterValues(array_keys($arList));

            $aggregateRootValue = '';
            while ('' === $aggregateRootValue) {
                $answer = $style->askQuestion($question);
                if (false === \is_string($answer)) {
                    continue;
                }

                $aggregateRootValue = $arList[$answer] ?? '';
            }
        }

        // Direct match in arList
        if (\array_key_exists($aggregateRootValue, $arList)) {
            return $arList[$aggregateRootValue];
        }

        // Try to find a match by FQN, machine name, or short name
        foreach ($arList as $key => $value) {
            // Check if the provided value matches the FQN
            if ($value === $aggregateRootValue) {
                return $value;
            }

            // Check if the provided value matches the machine name or short name
            if ($key === $aggregateRootValue) {
                return $value;
            }
        }

        throw new InvalidArgumentException(\sprintf('The aggregate root "%s" is not a valid entity.', $aggregateRootValue));
    }
}
