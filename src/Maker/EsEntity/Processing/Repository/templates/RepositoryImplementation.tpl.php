<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use AwdEs\Aggregate\Exception\EntityNotFound;
use AwdEs\Aggregate\Exception\EntityPersistenceException;
use AwdEs\Aggregate\Persistence\UoW\UnitOfWork;
use AwdEs\ValueObject\Id;
use <?php echo $entity_fqn; ?>;
use \<?php echo $exception_ns; ?>\<?php echo $entity_name; ?>NotFound;
use \<?php echo $exception_ns; ?>\<?php echo $entity_name; ?>PersistenceError;
use \<?php echo $repo_interface_fqn; ?>;

final readonly class <?php echo $class_name; ?> implements <?php echo $entity_name; ?>Repository
{
    public function __construct(
        private UnitOfWork $uow,
    ) {}

    #[\Override]
        public function get(Id $id): <?php echo $entity_name; ?>
    {
        try {
            return $this->uow->get(<?php echo $entity_name; ?>::class, $id);
        } catch (EntityNotFound) {
            throw new <?php echo $entity_name; ?>NotFound($id);
        }
    }

    #[\Override]
    public function store(<?php echo $entity_name; ?> $<?php echo \lcfirst((string) $entity_name); ?>): void
    {
        try {
            $this->uow->store($<?php echo \lcfirst((string) $entity_name); ?>);
        } catch (EntityPersistenceException $e) {
            throw new <?php echo $entity_name; ?>PersistenceError($<?php echo \lcfirst((string) $entity_name); ?>->id, $e);
        }
    }
}
