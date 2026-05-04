<?php

use Liamduckett\Structures\Dictionary;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;

class DictionaryTest extends TestCase
{
    /**
     * @dataProvider constructProvider
     *
     * @param iterable<non-empty-string, mixed> $data
     */
    public function test_can_construct_a_dictionary(iterable $data): void
    {
        $this->assertDictionary(
            ['foo' => 2, 'bar' => 4],
            Dictionary::make($data),
        );
    }

    public function test_can_count_a_dictionary(): void
    {
        $this->assertCount(
            2,
            Dictionary::make(['foo' => 2, 'bar' => 4]),
        );
    }

    /**
     * @dataProvider containsProvider
     */
    public function test_can_check_if_a_dictionary_contains_a_given_value(mixed $item, bool $expected): void
    {
        $this->assertSame(
            $expected,
            Dictionary::make(['foo' => 2, 'bar' => 4])->containsValue($item),
        );
    }

    /**
     * @dataProvider getProvider
     */
    public function test_can_retrieve_a_dictionary_value_by_its_key(int|string $key, mixed $value): void
    {
        $this->assertSame(
            $value,
            Dictionary::make(['foo' => 2, 'bar' => 4])->get($key),
        );
    }

    /**
     * @dataProvider addProvider
     *
     * @param array<non-empty-string, mixed> $existing
     * @param array<non-empty-string, mixed> $result
     */
    public function test_can_add_an_item_to_a_dictionary(array $existing, mixed $value, array $result): void
    {
        $this->assertDictionary(
            $result,
            Dictionary::make($existing)->add($value),
        );
    }

    /**
     * @dataProvider setProvider
     */
    public function test_can_set_a_dictionary_value(int|string $key, mixed $value): void
    {
        $this->assertDictionary(
            [$key => $value],
            Dictionary::make()->set($key, $value),
        );
    }

    /**
     * @dataProvider filterProvider
     *
     * @param iterable<non-empty-string, mixed> $items
     * @param callable(mixed, non-empty-string): bool $callable
     * @param iterable<non-empty-string, mixed> $expected
     */
    public function test_can_filter_a_dictionary(array $items, callable $callable, array $expected): void
    {
        $this->assertDictionary(
            $expected,
            Dictionary::make($items)->filter($callable),
        );
    }

    /**
     * @dataProvider mergeProvider
     *
     * @param iterable<non-empty-string, mixed> $toMerge
     * @param iterable<non-empty-string, mixed> $expected
     */
    public function test_can_merge_a_dictionary_with_provided_iterable(iterable $toMerge, iterable $expected): void
    {
        $this->assertDictionary(
            $expected,
            Dictionary::make(['foo' => 2, 'bar' => 4])->merge($toMerge),
        );
    }

    /**
     * @dataProvider valuesProvider
     *
     * @param iterable<non-empty-string, mixed> $items
     * @param iterable<non-empty-string, mixed> $expected
     */
    public function test_can_create_a_consecutively_keyed_dictionary(array $items, array $expected): void
    {
        $this->assertDictionary(
            $expected,
            Dictionary::make($items)->values(),
        );
    }

    /**
     * @dataProvider allProvider
     */
    public function test_can_retrieve_all_items_from_a_dictionary(array $items): void
    {
        $this->assertSame(
            $items,
            Dictionary::make($items)->items(),
        );
    }

    public function test_can_map_over_a_dictionary(): void
    {
        $result = Dictionary::make([1, 2, 3])->map(fn(int $item) => $item * $item);

        $this->assertSame(
            [1, 4, 9],
            iterator_to_array($result),
        );
    }

    /**
     * @dataProvider getIteratorProvider
     *
     * @param iterable<non-empty-string, mixed> $items
     */
    public function test_can_create_an_iterator_from_a_dictionary(iterable $items): void
    {
        $array = is_array($items) ? $items : iterator_to_array($items);

        $iterator = Dictionary::make($items)->getIterator();

        $results = iterator_to_array($iterator);

        $this->assertInstanceOf(Traversable::class, $iterator);
        $this->assertSame($results, $array);
    }

    public function test_can_check_if_a_dictionary_is_empty(): void
    {
        $empty = Dictionary::make();
        $notEmpty = Dictionary::make([0]);

        $this->assertTrue(
            $empty->isEmpty()
        );

        $this->assertFalse(
            $notEmpty->isEmpty()
        );
    }

    /**
     * @dataProvider rejectProvider
     *
     * @param iterable<non-empty-string, mixed> $items
     * @param (callable(mixed, non-empty-string): bool) $callable
     * @param iterable<non-empty-string, mixed> $expected
     */
    public function test_can_reject_items_from_a_dictionary(array $items, callable $callable, array $expected): void
    {
        $this->assertDictionary(
            $expected,
            Dictionary::make($items)->reject($callable),
        );
    }


    /**
     * @param iterable<non-empty-string, mixed> $expected
     */
    protected function assertDictionary(array $expected, Dictionary $actual): void
    {
        self::assertThat(
            $actual->items(),
            new IsIdentical($expected),
        );
    }

    public static function constructProvider(): array
    {
        return [
            'array' => [['foo' => 2, 'bar' => 4]],
            'iterable' => [new ArrayIterator(['foo' => 2, 'bar' => 4])],
            'generator' => [(function() {
                foreach(['foo' => 2, 'bar' => 4] as $item) {
                    yield $item;
                }
            })()],
        ];
    }

    public static function containsProvider(): array
    {
        return [
            'does' => [2, true],
            'doesnt' => [3, false],
            'doesnt loose' => ['2', false],
        ];
    }

    public static function getProvider(): array
    {
        return [
            'integer key present' => [2, 3],
            'integer key not present' => [5, null],
            'string key present' => ['foo', 4],
            'string key not present' => ['bar', null],
        ];
    }

    public static function addProvider(): array
    {
        return [
            'list' => [['foo' => 2, 'bar' => 4], 3, [1, 2, 3]],
            'integer dictionary' => [[3 => 'foo', 1 => 'bar'], 'baz', [3 => 'foo', 1 => 'bar', 4 => 'baz']],
            'string dictionary' => [['foo' => 1, 'bar' => 2], 3, ['foo' => 1, 'bar' => 2, 'baz' => 3]],
        ];
    }

    public static function setProvider(): array
    {
        return [
            'integer key' => [2, 3],
            'string key' => ['foo', 4],
        ];
    }

    public static function filterProvider(): array
    {
        return [
            'only value' => [
                [1, 2, 3, 4, 5],
                fn(mixed $number) => $number === 5,
                [0 => 1, 2 => 3, 4 => 5]
            ],
            'using key' => [
                [1, 2, 3, 4, 5],
                /** @param non-empty-string $key */
                fn(mixed $_, string $key) => strtolower($key) === $key, [1 => 2, 3 => 4],
            ],
        ];
    }

    public static function mergeProvider(): array
    {
        /** @var ArrayIterator<non-empty-string, int> $arrayIterator */
        $arrayIterator = new ArrayIterator(['baz' => 6, 'qux' => 8]);

        return [
            'array' => [['baz' => 3, 'qux' => 8], ['foo' => 2, 'bar' => 4, 'baz' => 3, 'qux' => 8]],
            'iterable' => [$arrayIterator, ['foo' => 2, 'bar' => 4, 'baz' => 6, 'qux' => 8]],
        ];
    }

    public static function valuesProvider(): array
    {
        return [
            'list' => [[1, 2, 3], [1, 2, 3]],
            'integer dictionary' => [[3 => 'foo', 1 => 'bar', 2 => 'baz'], ['foo', 'bar', 'baz']],
            'string dictionary' => [['foo' => 1, 'bar' => 2, 'baz' => 3], [1, 2, 3]],
        ];
    }

    public static function allProvider(): array
    {
        return [
            'list' => [[1, 2, 3]],
            'integer dictionary' => [[3 => 'foo', 1 => 'bar', 2 => 'baz']],
            'string dictionary' => [['foo' => 1, 'bar' => 2, 'baz' => 3]],
        ];
    }

    public static function getIteratorProvider(): array
    {
        /** @var ArrayIterator<non-empty-string, int> $arrayIterator */
        $arrayIterator = new ArrayIterator(['foo' => 2, 'bar' => 4]);

        return [
            'array' => [['foo' => 2, 'bar' => 4]],
            'iterable' => [$arrayIterator],
            'generator' => [(function() {
                foreach(['foo' => 2, 'bar' => 4] as $key => $item) {
                    yield $key => $item;
                }
            })()],
        ];
    }

    public static function rejectProvider(): array
    {
        return [
            'only value' => [
                [1, 2, 3, 4, 5],
                fn(mixed $value) => $value === 5,
                [1 => 2, 3 => 4]
            ],
            'using key' => [
                [1, 2, 3, 4, 5],
                /** @param non-empty-string $key */
                fn(mixed $_, string $key) => strtolower($key) === $key, [0 => 1, 2 => 3, 4 => 5]
            ],
        ];
    }
}
