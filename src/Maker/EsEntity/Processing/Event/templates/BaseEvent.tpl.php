<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use Awd\ValueObject\IDateTime;
use AwdEs\Event\EntityEvent;
use AwdEs\ValueObject\Id;
use AwdEs\ValueObject\Version;

abstract readonly class <?php echo $class_name; ?> implements EntityEvent
{
    public function __construct(
        public Id $id,
<?php if (true === $is_simple) { ?>
        public bool $isActive,
<?php } else { ?>
        public <?php echo $main_value_type; ?> $<?php echo $main_value_name; ?>,
<?php } ?>
        public IDateTime $occurredAt,
        public Version $version,
    ) {}

    #[\Override]
    public function entityId(): Id
    {
        return $this->id;
    }

    #[\Override]
    public function occurredAt(): IDateTime
    {
        return $this->occurredAt;
    }

    #[\Override]
    public function version(): Version
    {
        return $this->version;
    }
}
