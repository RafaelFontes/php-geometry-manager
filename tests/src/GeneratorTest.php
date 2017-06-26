<?php

namespace N2OTI\GM\Test;

use PHPUnit\Framework\TestCase;
use N2OTI\GM\Generator;
use N2OTI\GM\Component\Component;

class GeneratorTest extends TestCase
{
    public function testCreate()
    {
        echo "\n-------------------------- testCreate\n";
        echo "Generating new Component\n";

        $component = Generator::create(Generator::COMPONENT);

        echo "Testing creating\n";
        $this->assertNotNull($component);

        echo "Testing hash\n";
        $this->assertEquals( $component, Generator::get( $component->hash() ) );
    }

    public function tearDown()
    {
        echo "\n--------------------------\n";
        echo "OK.\n";
    }
}
