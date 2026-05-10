<?php

namespace Tests\Dictionary;

use Liamduckett\Structures\Dictionary;
use PHPUnit\Framework\TestCase;
use Tests\Dictionary\Concerns\TestsDictionaries;

/**
 * @internal
 *
 * @coversNothing
 */
class ConvertingTest extends TestCase
{
    use TestsDictionaries;

    // array ---

    public function testArrayReturnsTheUnderlyingArray(): void
    {
        $array = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->array()
        ;

        $this->assertSame(['foo' => 1, 'bar' => 2], $array);
    }

    // getIterator ---

    public function testGetIteratorCanBeIterated(): void
    {
        $iterator = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->getIterator()
        ;

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($iterator));
    }

    // iterator ---

    public function testIteratorCanBeIterated(): void
    {
        $iterator = Dictionary::make()
            ->merge(['foo' => 1, 'bar' => 2])
            ->iterator()
        ;

        $this->assertSame(['foo' => 1, 'bar' => 2], iterator_to_array($iterator));
    }
}
