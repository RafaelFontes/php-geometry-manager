<?php

namespace N2OTI\GM\Test\Component;

use PHPUnit\Framework\TestCase;
use N2OTI\GM\Component\Layout\AbsoluteLayout;
use N2OTI\GM\Component\Layout\VBoxLayout;
use N2OTI\GM\Component\Layout\HBoxLayout;
use N2OTI\GM\Component\Layout\Layout;
use N2OTI\GM\Component\Component;
use N2OTI\GM\Component\Policy\SizePolicy;

/**
 * Layout->isEmpty()
 * Layout->maxWidth()
 * Layout->calcSize()
 * Layout->maxHeight()
 */
class LayoutTest extends TestCase
{
    public function setUp()
    {
        echo "\n------------------- " . $this->getName() . "\n";
    }

    public function tearDown()
    {

    }

    public function providerTestSize()
    {
        return array(
            array(100,80,5,0,90),
            array(100,0,5,0,90),
            array(100,1,5,0,90),
            array(0,80,5,0,0),
            array(100,0,5,0,90),
            array(100,80,15,0,70)
        );
    }

    //($width, $height, $margin, $spacing, $expectedWidth, $expectedHeight)
    public function providerTestCalcSize()
    {
        return array(
            array(0,0,5,0,0,0),
            array(100,80,5,0,90,70),
            array(100,120,5,0,90,110),
            array(100,30,5,10,90,20),
            array(100,80,10,0,80,60),
            array(100,0,5,0,90,0),
            array(0,80,15,0,0,50)
        );
    }

    //($width, $height, $spacing, $margin, $stretch1, $stretch2, $expectedWidth1, $expectedWidth2, $expectedX1, $expectedX2)
    public function providerTestCalcSizeStretching()
    {
        return array(
            array(0,0,0,0,0,0,0,0,0,0),
            array(100, 30, 10, 10, 1, 2, 70/3, 140/3, 10, (70/3) + 20),
            array(600, 200, 100, 10, 1, 2, 480/3, 480 * (2/3), 10, (480/3) + 110),
            array(600, 200, 100, 10, 2, 1, 480 * (2/3), 480/3, 10, (480 * (2/3)) + 110)
        );
    }

    public function testEmptyDetection()
    {
        $layout = new HBoxLayout();
        $component = new Component();
        $component->setLayout( $layout );

        $this->assertTrue($layout->isEmpty());

        $child = new Component();
        $component->insert( $child );

        $this->assertFalse($layout->isEmpty());
    }

    /**
     * @dataProvider providerTestSize
     */
    public function testMaxWidth($width, $height, $margin, $spacing, $expectedWidth)
    {
        $component = new Component();
        $component->setHeight($height);
        $component->setWidth($width);
        $component->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

        $layout = new HBoxLayout();
        $layout->setSpacing($spacing);
        $layout->setMargin($margin);

        $component->setLayout($layout);

        $child = new Component();
        $child->setSizePolicy(new SizePolicy(SizePolicy::EXPANDING, SizePolicy::EXPANDING));

        $this->assertEquals(50, $child->geometry()->width());

        $component->insert($child);

        $this->assertEquals($expectedWidth, $child->geometry()->width());
    }

    /**
     * @dataProvider providerTestSize
     */
    public function testMaxHeight($height, $width, $margin, $spacing, $expectedHeight)
    {
        $component = new Component();
        $component->setWidth($width);
        $component->setHeight($height);
        $component->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

        $layout = new HBoxLayout();
        $layout->setSpacing($spacing);
        $layout->setMargin($margin);

        $component->setLayout($layout);

        $child = new Component();
        $child->setSizePolicy(new SizePolicy(SizePolicy::EXPANDING, SizePolicy::EXPANDING));

        $this->assertEquals(50, $child->geometry()->height());

        $component->insert($child);

        $this->assertEquals($expectedHeight, $child->geometry()->height());
    }

    /**
     * @dataProvider providerTestCalcSize
     */
    public function testCalcSize($width, $height, $margin, $spacing, $expectedWidth, $expectedHeight)
    {
        $component = new Component();
        $component->setWidth($width);
        $component->setHeight($height);
        $component->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

        $layout = new HBoxLayout();
        $layout->setSpacing($spacing);
        $layout->setMargin($margin);

        $component->setLayout($layout);

        $child = new Component();
        $child->setSizePolicy(new SizePolicy(SizePolicy::EXPANDING, SizePolicy::EXPANDING));

        $this->assertEquals(50, $child->geometry()->width());
        $this->assertEquals(50, $child->geometry()->height());

        $component->insert($child);

        $this->assertEquals($expectedWidth, $child->geometry()->width());
        $this->assertEquals($expectedHeight, $child->geometry()->height());
    }

    /**
     * @dataProvider providerTestCalcSizeStretching
     */
    public function testCalcSizeStretching($width, $height, $spacing, $margin, $stretch1, $stretch2, $expectedWidth1, $expectedWidth2, $expectedX1, $expectedX2)
    {
        $component = new Component();
        $component->setWidth($width);
        $component->setHeight($height);
        $component->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

        $layout = new HBoxLayout();
        $layout->setSpacing($spacing);
        $layout->setMargin($margin);

        $component->setLayout($layout);

        $child1 = new Component();
        $child1->setStretch($stretch1);
        $child1->setSizePolicy(new SizePolicy(SizePolicy::EXPANDING, SizePolicy::EXPANDING));

        $child2 = new Component();
        $child2->setStretch($stretch2);
        $child2->setSizePolicy(new SizePolicy(SizePolicy::EXPANDING, SizePolicy::EXPANDING));

        $this->assertEquals(50, $child1->geometry()->width());
        $this->assertEquals(50, $child1->geometry()->height());

        $this->assertEquals(50, $child2->geometry()->width());
        $this->assertEquals(50, $child2->geometry()->height());

        $component->insert($child1);
        $component->insert($child2);

        $this->assertEquals( $expectedX1, $child1->geometry()->x());
        $this->assertEquals( $expectedX2, $child2->geometry()->x());

        $this->assertEquals( $expectedWidth1, $child1->geometry()->width() );
        $this->assertEquals( $expectedWidth2, $child2->geometry()->width() );

        $this->assertEquals( $margin , $child1->geometry()->y());
        $this->assertEquals( $margin, $child2->geometry()->y());
    }

}
