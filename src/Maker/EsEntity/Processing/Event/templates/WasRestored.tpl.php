<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use <?php echo $entity_fqn; ?>;
use AwdEs\Attribute\AsEntityEvent;

#[AsEntityEvent(name: '<?php echo $entity_machine_name; ?>_WAS_RESTORED', entityFqn: <?php echo $entity_short_name; ?>::class)]
final readonly class <?php echo $class_name; ?> extends <?php echo $entity_short_name; ?>Event {}
