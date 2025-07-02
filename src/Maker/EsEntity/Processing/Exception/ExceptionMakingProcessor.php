<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\Exception;

use AwdEs\EsLibMakerBundle\Maker\EsEntity\Processing\EntityMakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingConfig;
use AwdEs\EsLibMakerBundle\Maker\Processing\MakingProcessorCase;
use Symfony\Bundle\MakerBundle\Generator;

final readonly class ExceptionMakingProcessor implements MakingProcessorCase
{
    public const string TPL_PERSISTENCE_ERROR = __DIR__ . '/templates/PersistenceError.tpl.php';
    public const string TPL_NOT_FOUND = __DIR__ . '/templates/NotFound.tpl.php';

    private const string SUFFIX_PATH = 'Exception';
    private const string SUFFIX_NOT_FOUND = 'NotFound';
    private const string SUFFIX_PERSISTENCE_ERROR = 'PersistenceError';

    public function __construct(
        private string $pathTplPersistenceError = self::TPL_PERSISTENCE_ERROR,
        private string $pathTplNotFound = self::TPL_NOT_FOUND,
    ) {}

    #[\Override]
    public function process(MakingConfig $config, Generator $generator): void
    {
        if (!$config instanceof EntityMakingConfig) {
            return;
        }

        $ns = $config->namespaceConfig;

        $domainNs = $ns->domain() . '\\' . self::SUFFIX_PATH . '\\';

        $notFoundFqn = $domainNs . $config->classShortName;
        $notFoundDetails = $generator->createClassNameDetails($notFoundFqn . self::SUFFIX_NOT_FOUND, '');

        $generator->generateClass($notFoundDetails->getFullName(), $this->pathTplPersistenceError);

        $persistenceErrorFqn = $domainNs . $config->classShortName;
        $persistenceErrorDetails = $generator->createClassNameDetails($persistenceErrorFqn . self::SUFFIX_PERSISTENCE_ERROR, '');
        $generator->generateClass($persistenceErrorDetails->getFullName(), $this->pathTplNotFound);
    }
}
