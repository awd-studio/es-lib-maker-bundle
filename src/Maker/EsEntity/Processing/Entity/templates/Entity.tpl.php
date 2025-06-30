<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use Awd\ValueObject\IDateTime;
<?php if (true === $is_self_root) { ?>
use AwdEs\Aggregate\AggregateRoot;
<?php } else { ?>
<?php echo 'use ' . $agg_root_full . ";\n"; ?>
<?php } ?>
use AwdEs\Aggregate\Entity;
use AwdEs\Attribute\AsAggregateEntity;
use AwdEs\Attribute\EventHandler;
use AwdEs\ValueObject\Id;

#[AsAggregateEntity(name: '<?php echo $machine_name; ?><?php echo $is_self_root ? '_ROOT' : ''; ?>', rootFqn: <?php echo $agg_root_short; ?>::class)]
final class <?php echo $class_name; ?> extends Entity<?php echo $is_self_root ? ' implements AggregateRoot' : ''; ?>
{
    public Id $id;
<?php if (true === $is_simple) { ?>
    public bool $isActive;
<?php } else { ?>
    public <?php echo $main_value_type; ?> $<?php echo $main_value_name; ?>;
<?php } ?>
    public IDateTime $createdAt;
    public IDateTime $changedAt;
<?php if (true === $is_deletable) { ?>
    public ?IDateTime $deletedAt = null;
<?php } ?>

    #[EventHandler(Event\<?php echo $class_name; ?>WasCreated::class)]
    public function on<?php echo $class_name; ?>WasCreated(Event\<?php echo $class_name; ?>WasCreated $event): void
    {
        $this->id = $event->id;
<?php if (true === $is_simple) { ?>
        $this->isActive = $event->isActive;
<?php } ?>
        $this->createdAt = $event->occurredAt;
        $this->changedAt = $event->occurredAt;
    }

<?php if (true === $is_simple) { ?>
    public function initAsActive(Id $id, IDateTime $createdAt): void
    {
        if (true === $this->isInitialized()) {
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasCreated($id, true, $createdAt));
    }

    public function initAsInactive(Id $id, IDateTime $createdAt): void
    {
        if (true === $this->isInitialized()) {
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasCreated($id, false, $createdAt));
    }

    public function activate(IDateTime $activatedAt): void
    {
        if (true === $this->isActive) {
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasActivated($this->id, $activatedAt, $this->version->next()));
    }

    #[EventHandler(Event\<?php echo $class_name; ?>WasActivated::class)]
    public function on<?php echo $class_name; ?>WasActivated(Event\<?php echo $class_name; ?>WasActivated $event): void
    {
        $this->isActive = true;
        $this->changedAt = $event->occurredAt;
    }

    public function deactivate(IDateTime $deactivatedAt): void
    {
        if (false === $this->isActive) {
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasDeactivated($this->id, $deactivatedAt, $this->version->next()));
    }

    #[EventHandler(Event\<?php echo $class_name; ?>WasDeactivated::class)]
    public function on<?php echo $class_name; ?>WasDeactivated(Event\<?php echo $class_name; ?>WasDeactivated $event): void
    {
        $this->isActive = false;
        $this->changedAt = $event->occurredAt;
    }
<?php } else { ?>
    public function init(Id $id, <?php echo $main_value_type; ?> $<?php echo $main_value_name; ?>, IDateTime $createdAt): void
    {
        if (true === $this->isInitialized()) {
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasCreated($id, $<?php echo $main_value_name; ?>, $createdAt));
    }

    public function change(<?php echo $main_value_type; ?> $new<?php echo mb_ucfirst($main_value_name); ?>, IDateTime $changedAt): void
    {
<?php if ('IDateTime' === $main_value_type) { ?>
        if (true === $this-><?php echo $main_value_name; ?>->isEqual($new<?php echo mb_ucfirst($main_value_name); ?>)) {
<?php } else { ?>
        if ($this-><?php echo $main_value_name; ?> === $new<?php echo mb_ucfirst($main_value_name); ?>) {
<?php } ?>
            return;
        }

        $this->recordThat(new Event\<?php echo $class_name; ?>WasChanged($this->id, $new<?php echo ucfirst((string) $main_value_name); ?>, $this-><?php echo $main_value_name; ?>, $changedAt, $this->version->next()));
    }

    #[EventHandler(Event\<?php echo $class_name; ?>WasChanged::class)]
    public function on<?php echo $class_name; ?>WasChanged(Event\<?php echo $class_name; ?>WasChanged $event): void
    {
        $this-><?php echo $main_value_name; ?> = $event-><?php echo $main_value_name; ?>;
        $this->changedAt = $event->occurredAt;
    }
<?php } ?>

    #[\Override]
    public function aggregateId(): Id
    {
        return $this->id;
    }
}
