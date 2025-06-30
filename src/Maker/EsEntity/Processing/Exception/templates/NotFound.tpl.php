<?php declare(strict_types=1);

echo "<?php\n"; ?>

declare(strict_types=1);

namespace <?php echo $namespace; ?>;

use App\Shared\Exception\NotFoundException;
use AwdEs\ValueObject\Id;

final class <?php echo $class_name; ?> extends NotFoundException
{
    public function __construct(public Id $id, ?\Throwable $previous = null)
    {
        $message = \sprintf('The item with id "%s" not found.', $id);

        parent::__construct($message, 404, $previous);
    }
}
