<?php

include __DIR__ . "/../../vendor/autoload.php";

use N2OTI\GM\Generator;
use N2OTI\GM\Component\Component;
use N2OTI\GM\Component\Layout\HBoxLayout;
use N2OTI\GM\Component\Layout\VBoxLayout;
use N2OTI\GM\Component\Policy\SizePolicy;

$container = Generator::create(Generator::COMPONENT);
$layoutContainer = new VBoxLayout();
$layoutContainer->setMargin(0);
$layoutContainer->setSpacing(0);
$container->setWidth(800);
$container->setHeight(400);
$container->setLayout($layoutContainer);
$container->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

$component = Generator::create(Generator::COMPONENT);

$layout = new HBoxLayout();
$layout->setMargin(50);
$layout->setSpacing(50);
$component->setHeight(200);
$component->setLayout($layout);
$component->setSizePolicy(new SizePolicy( SizePolicy::MINIMUM, SizePolicy::FIXED));

$component2 = Generator::create(Generator::COMPONENT);
$layout2 = new HBoxLayout();
$layout2->setMargin(50);
$layout2->setSpacing(50);
$component2->setHeight(200);
$component2->setLayout($layout2);
$component2->setSizePolicy(new SizePolicy( SizePolicy::MINIMUM, SizePolicy::FIXED));

$item1 = Generator::create(Generator::COMPONENT);
$item1->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));
$item1->setWidth(36);
$item1->setHeight(36);

$item2 = Generator::create(Generator::COMPONENT);
$item2->setWidth(100);
$item2->setHeight(80);
$item2->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

$item3 = Generator::create(Generator::COMPONENT);
$item3->setWidth(36);
$item3->setHeight(36);
$item3->setSizePolicy(new SizePolicy(SizePolicy::FIXED, SizePolicy::FIXED));

$component->insert( $item1 );
$component->insert( $item2 );
$component2->insertAfter( $item3, $item2 );

$container->insert($component);
$container->insert($component2);
