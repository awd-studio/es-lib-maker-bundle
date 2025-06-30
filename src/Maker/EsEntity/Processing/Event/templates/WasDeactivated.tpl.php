<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use <?php echo $entity_fqn; ?>;
use Awd\ValueObject\IDateTime;
use AwdEs\Attribute\AsEntityEvent;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

#[AsEntityEvent(name: '<?php echo $entity_machine_name; ?>_WAS_DEACTIVATED', entityFqn: <?php echo $entity_short_name; ?>::class)]
final readonly class <?php echo $class_name; ?> extends <?php echo $entity_short_name; ?>Event
{
    public function __construct(Id $id, IDateTime $deactivatedAt, Version $version)
    {
        parent::__construct($id, false, $deactivatedAt, $version);
    }
}
