<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Repository;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class RepositoryMakingProcessor implements MakingProcessorCase
{
    public const string TPL_INTERFACE = __DIR__ . '/templates/RepositoryInterface.tpl.php';
    public const string TPL_IMPLEMENTATION = __DIR__ . '/templates/RepositoryImplementation.tpl.php';

    public function __construct(
        private string $pathTplInterface = self::TPL_INTERFACE,
        private string $pathTplImplementation = self::TPL_IMPLEMENTATION,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        $ns = $config->namespaceConfig;

        $baseDetails = $generator->createClassNameDetails($config->namespaceConfig->domain(), '', '');
        $entityDetails = $generator->createClassNameDetails($config->unprocessedFqn, '', '');

        $repoInterfaceFqn = $ns->domain() . '\Repository\\' . $config->classShortName;
        $interfaceDetails = $generator->createClassNameDetails($repoInterfaceFqn . 'Repository', '');
        $generator->generateClass($interfaceDetails->getFullName(), $this->pathTplInterface, [
            'ns_domain' => $ns->domain(),
            'entity_name' => $config->classShortName,
            'entity_fqn' => $entityDetails->getFullName(),
            'exception_ns' => $baseDetails->getFullName() . '\Exception',
        ]);

        $repoImplementationFqn = $ns->infrastructure() . '\Repository\UoW' . $config->classShortName;
        $implementationDetails = $generator->createClassNameDetails($repoImplementationFqn . 'Repository', '');
        $generator->generateClass($implementationDetails->getFullName(), $this->pathTplImplementation, [
            'ns_domain' => $ns->domain(),
            'entity_name' => $config->classShortName,
            'entity_fqn' => $entityDetails->getFullName(),
            'exception_ns' => $baseDetails->getFullName() . '\Exception',
            'repo_interface_fqn' => $interfaceDetails->getFullName(),
        ]);
    }
}
