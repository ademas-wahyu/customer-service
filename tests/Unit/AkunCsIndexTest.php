<?php

namespace Tests\Unit;

use App\Livewire\AkunCs\Index as AkunCsIndex;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class AkunCsIndexTest extends TestCase
{
    public function test_generate_chart_values_respects_minimum_baseline(): void
    {
        $component = new AkunCsIndex();

        $method = new ReflectionMethod(AkunCsIndex::class, 'generateChartValues');
        $method->setAccessible(true);

        $values = $method->invoke($component, 5);

        $this->assertCount(7, $values);
        $this->assertGreaterThanOrEqual(0, min($values));
        $this->assertGreaterThanOrEqual(0, max($values));
    }

    public function test_build_chart_meta_produces_consistent_structure(): void
    {
        $component = new AkunCsIndex();

        $method = new ReflectionMethod(AkunCsIndex::class, 'buildChartMeta');
        $method->setAccessible(true);

        $values = array_fill(0, 7, 12);

        $meta = $method->invoke($component, $values);

        $this->assertSame($values, $meta['values']);
        $this->assertCount(7, $meta['labels']);
        $this->assertCount(7, $meta['points']);
        $this->assertSame(13, $meta['max']);
        $this->assertSame(11, $meta['min']);
        $this->assertSame(12.0, $meta['average']);

        foreach ($meta['points'] as $point) {
            $this->assertArrayHasKey('x', $point);
            $this->assertArrayHasKey('y', $point);
            $this->assertArrayHasKey('value', $point);
            $this->assertSame(12, $point['value']);
        }

        $this->assertStringStartsWith('M ', $meta['path']);
        $this->assertStringEndsWith(' Z', $meta['area']);
    }
}
