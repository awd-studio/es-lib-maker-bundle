<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use AwdEs\Aggregate\Persistence\UoW\UnitOfWork;
use Awd\ValueObject\IDateTime;
use AwdEs\Event\Applying\EventApplier;
use AwdEs\ValueObject\Id;
use <?php echo $entity_fqn; ?>;
use \<?php echo $factory_interface_fqn; ?>;

final readonly class <?php echo $class_name; ?> implements <?php echo $entity_name; ?>Factory
{
    public function __construct(
        private EventApplier $eventApplier,
    ) {}

<?php if ($is_simple): ?>: ?>
    #[\Override]
    public function createAsActive(Id $id, IDateTime $createdAt): <?php echo $entity_name; ?>;
    {
        $instance = new <?php echo $entity_name; ?>($this->eventApplier);
        $instance->initAsActive($id, $createdAt);

        return $instance;
    }

    #[\Override]
    public function createAsInactive(Id $id, IDateTime $createdAt): <?php echo $entity_name; ?>;
    {
        $instance = new <?php echo $entity_name; ?>($this->eventApplier);
        $instance->initAsInactive($id, $createdAt);

        return $instance;
    }
<?php else: ?>
    #[\Override]
    public function create(Id $id, <?php echo $main_value_type; ?> $new<?php echo ucfirst((string) $main_value_name); ?>, IDateTime $createdAt): <?php echo $entity_name; ?>
    {
        $instance = new <?php echo $entity_name; ?>($this->eventApplier);
        $instance->init($id, $new<?php echo ucfirst((string) $main_value_name); ?>, $createdAt);

        return $instance;
    }
<?php endif; ?>
}
