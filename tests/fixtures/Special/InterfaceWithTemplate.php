<?php declare(strict_types=1);

namespace Tests\PHPat\fixtures\Special;

use Tests\PHPat\fixtures\Simple\SimpleInterface;

/**
 * @template T of SimpleInterface
 */
interface InterfaceWithTemplate
{
    /**
     * @param  T          $simple
     * @return null|T
     * @throws \Exception
     */
    public function something(SimpleInterface $simple);
}
