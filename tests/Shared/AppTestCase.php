<?php

declare(strict_types=1);

namespace AwdEs\EsLibMakerBundle\Tests\Shared;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

abstract class AppTestCase extends TestCase
{
    use ProphecyTrait;
}
