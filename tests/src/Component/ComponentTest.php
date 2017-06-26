<?php

namespace N2OTI\GM\Test\Component;

use PHPUnit\Framework\TestCase;
use N2OTI\GM\Component\Component;

class ComponentTest extends TestCase
{
    public function testInsert()
    {
        echo "\n-------------------------- testInsert\n";
        echo "Creating new Component\n";
        $component = new Component();

        $this->assertEquals(0, count($component->children()));

        $newComponent = new Component();

        echo "Adding Component\n";
        $component->insert( $newComponent );

        $this->assertEquals( 1, count($component->children()));

        echo "Validating Insertion\n";
        $this->assertTrue( array_search($newComponent, $component->children() ) >= 0 );
    }

    public function testInsertAfter()
    {
        echo "\n-------------------------- testInsertAfter\n";
        echo "Creating new Components\n";

        $component = new Component();
        $newComponent = new Component();
        $newComponent2 = new Component();
        $newComponent3 = new Component();
        echo "Components created {$component->hash()} {$newComponent->hash()} {$newComponent2->hash()} {$newComponent3->hash()}\n";

        echo "Adding Component1\n";
        $component->insert( $newComponent );

        echo "Adding Component2\n";
        $component->insert( $newComponent2 );

        echo "Adding Component3 after Component1 \n";
        $component->insertAfter( $newComponent3, $newComponent );

        echo "Validating insertions\n";
        $this->assertEquals( 3, count($component->children()));

        $this->assertEquals( $newComponent3->previousComponent()->hash(), $newComponent->hash() );

    }

    public function tearDown()
    {
        echo "\n--------------------------\n";
        echo "OK.\n";
    }
}
