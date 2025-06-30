<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use App\Shared\Exception\PersistenceError;
use AwdEs\ValueObject\Id;

final class <?php echo $class_name; ?> extends PersistenceError
{
    public function __construct(public Id $id, ?\Throwable $previous = null)
    {
        $message = \sprintf('Could not store the item with id "%s".', $id);

        parent::__construct($message, 404, $previous);
    }
}
