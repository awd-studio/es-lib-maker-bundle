<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use <?php echo $entity_fqn; ?>;
use AwdEs\ValueObject\Id;

interface <?php echo $class_name; ?>
{
    /**
     * @throws \<?php echo $exception_ns; ?>\<?php echo $entity_name; ?>NotFound
     */
    public function get(Id $id): <?php echo $entity_name; ?>;

    /**
     * @throws \<?php echo $exception_ns; ?>\<?php echo $entity_name; ?>PersistenceError
     */
    public function store(<?php echo $entity_name; ?> $<?php echo \lcfirst((string) $entity_name); ?>): void;
}
