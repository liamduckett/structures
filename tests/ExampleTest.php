<?php

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ExampleTest extends TestCase
{
    /** @test */
    public function example(): void
    {
        $this->assertTrue(true); // @phpstan-ignore method.alreadyNarrowedType
    }
}
