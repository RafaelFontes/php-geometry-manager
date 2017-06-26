<?php
use \N2OTI\GM\Generator;

include 'sample-component.inc.php';
include 'BpmnRenderer.php';

$renderer = new BpmnRenderer("Cadastro de Pessoa");

Generator::writeMetadata("name","Cadastro de Pessoa", $container);
Generator::writeMetadata("tagName","bpmn:process", $container);

Generator::writeMetadata("name","RH", $component);
Generator::writeMetadata("tagName","bpmn:lane", $component);

Generator::writeMetadata("name","Portaria", $component2);
Generator::writeMetadata("tagName","bpmn:lane", $component2);

Generator::writeMetadata("name","Início", $item1);
Generator::writeMetadata("tagName","bpmn:startEvent", $item1);

Generator::writeMetadata("name","Cadastrar", $item2);
Generator::writeMetadata("tagName","bpmn:task", $item2);

Generator::writeMetadata("name","Fim", $item3);
Generator::writeMetadata("tagName","bpmn:endEvent", $item3);


echo $renderer->draw();

