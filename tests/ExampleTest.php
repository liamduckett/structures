<?php

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /** @test */
    public function example(): void
    {
        $this->assertTrue(true); // @phpstan-ignore method.alreadyNarrowedType
    }
}
