<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\Processing;

use Symfony\Bundle\MakerBundle\Generator;

final readonly class CasesMakingProcessor implements MakingProcessor
{
    /**
     * @param iterable<MakingProcessorCase> $cases
     */
    public function __construct(
        private iterable $cases,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        foreach ($this->cases as $case) {
            $case->process($config, $generator);
        }
    }
}
