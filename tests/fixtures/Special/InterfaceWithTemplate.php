<?php

declare(strict_types=1);

namespace Tests\PHPat\Fixtures\Special;

use Exception;
use Tests\PHPat\Fixtures\Simple\SimpleInterface;

/**
 * @template T of SimpleInterface
 */
interface InterfaceWithTemplate
{
    /**
     * @param T $simple
     * @throws Exception
     * @return null|T
     */
    public function something(SimpleInterface $simple);
}
