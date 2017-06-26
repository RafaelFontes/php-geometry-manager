<?php

use N2OTI\GM\Render\Render;

include 'bpmn/sample-component.inc.php';

$render = new Render();

echo $render->draw( $container );
