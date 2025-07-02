<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use <?php echo $entity_fqn; ?>;
use Awd\ValueObject\IDateTime;
use AwdEs\ValueObject\Id;

interface <?php echo $class_name; ?>
{
<?php if (true === $is_simple): ?>
    public function createAsActive(Id $id, IDateTime $createdAt): <?php echo $entity_name; ?>;

    public function createAsInactive(Id $id, IDateTime $createdAt): <?php echo $entity_name; ?>;
<?php else: ?>
    public function create(Id $id, <?php echo $main_value_type; ?> $new<?php echo ucfirst((string) $main_value_name); ?>, IDateTime $createdAt): <?php echo $entity_name; ?>;
<?php endif; ?>
}
