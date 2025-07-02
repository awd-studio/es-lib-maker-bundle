<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Factory;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class FactoryMakingProcessor implements MakingProcessorCase
{
    public const string TPL_INTERFACE = __DIR__ . '/templates/FactoryInterface.tpl.php';
    public const string TPL_IMPLEMENTATION = __DIR__ . '/templates/FactoryImplementation.tpl.php';

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

        $entityDetails = $generator->createClassNameDetails($config->unprocessedFqn, '', '');

        $repoInterfaceFqn = $ns->domain() . '\Repository\\' . $config->classShortName;
        $interfaceDetails = $generator->createClassNameDetails($repoInterfaceFqn, '', 'Factory');
        $generator->generateClass($interfaceDetails->getFullName(), $this->pathTplInterface, [
            'is_simple' => $config->mainValueConfig->isSimple(),
            'entity_name' => $config->classShortName,
            'entity_fqn' => $entityDetails->getFullName(),
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
        ]);

        $repoImplementationFqn = $ns->infrastructure() . '\Repository\Direct' . $config->classShortName;
        $implementationDetails = $generator->createClassNameDetails($repoImplementationFqn, '', 'Factory');
        $generator->generateClass($implementationDetails->getFullName(), $this->pathTplImplementation, [
            'is_simple' => $config->mainValueConfig->isSimple(),
            'entity_name' => $config->classShortName,
            'entity_fqn' => $entityDetails->getFullName(),
            'factory_interface_fqn' => $interfaceDetails->getFullName(),
            'main_value_type' => $config->mainValueConfig->type,
            'main_value_name' => $config->mainValueConfig->name,
        ]);
    }
}
