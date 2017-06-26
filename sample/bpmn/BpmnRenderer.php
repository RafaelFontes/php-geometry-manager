<?php

use N2OTI\GM\Generator;
use N2OTI\GM\Component\Component;

class BpmnRenderer
{
    private $_processName;
    private $_collaborationId;
    private $_sequences=array();
    static private $tabCounter=0;

    public function __construct($processName, $collaborationId="Collaboration_1")
    {
        $this->_processName = $processName;
        $this->_collaborationId=$collaborationId;
    }

    /**
     *
     *
     * @param Component $component
     * @return void
     */
    public function draw()
    {
        $output = '<?xml version="1.0" encoding="UTF-8"?>'.PHP_EOL;

        $output .= $this->openTag("bpmn:definitions", $this->getDefinitionAttributes()).PHP_EOL;

        $output .= $this->writeCollaboration();

        $processes = Generator::filterComponentsByMetadata("tagName","bpmn:process");

        foreach( $processes as $process)
        {
            $output .= $this->openComponentTag($process).PHP_EOL;

            $output .= $this->writeLaneSet();

            $output .= $this->drawSequences();

            $output .= $this->drawVisibileComponentsByTagName( array("bpmn:task","bpmn:startEvent","bpmn:endEvent") );

            $output .= $this->closeComponentTag($process);

            $output .= $this->writeDiagram();

            $output .= $this->closeTagWithTabs("bpmn:definitions");
        }

        return $output;
    }

    private function writeWaypoint($rect)
    {
        $output = "";

        $output .= $this->openTag("di:waypoint",
            array(
                "xsi:type" => "dc:Point",
                "x" => $rect->x(),
                "y" => $rect->y() + ($rect->height()/2),
            )
        );

        $output .= $this->closeTag("di:waypoint");

        return $output;
    }

    private function writeDiagramSequences()
    {
        $output = "";

        foreach( $this->_sequences as $sequence )
        {
            $output .= $this->openTag("bpmndi:BPMNEdge",
                array(
                    "id" => "SequenceFlow_" . $sequence["id"] . "_di",
                    "bpmnElement" => $sequence["id"]
                )
            ).PHP_EOL;

            $sourceComponent = Generator::get($sequence[0]);
            $source = $sourceComponent->geometry(false);
            $destination = Generator::get($sequence[1])->geometry(false);

            /**
             * If source->x + source->width > destination->x // going back
             * Then source->x should be source->x + ( source->width/2 )
             */
            if ($source->x() + $source->width() > $destination->x()) {
                $source->setX($source->x() + ($source->width()/2));
                $source->setY( $source->height()/2 + $source->y() ); // writeWayPointMethod adds half of the height to the Y coord, so we do it here too to make it go to the total height.

                $destination->setY($destination->y()-($destination->height()/2));
                $destination->setX($source->x());
            }
            else {
                $source->setX($source->x() + $source->width());
            }

            $output .= $this->writeWaypoint( $source );
            $output .= $this->writeWaypoint( $destination );

            $output .= $this->closeTagWithTabs("bpmndi:BPMNEdge");
        }

        return $output;
    }

    private function writeDiagram()
    {
        $output = "";

        $output .= $this->openTag("bpmndi:BPMNDiagram",array("id"=>"BPMNDiagram_1")).PHP_EOL;

        $output .= $this->openTag("bpmndi:BPMNPlane",$this->getPlaneAttributes()).PHP_EOL;

        $output .= $this->writeComponentsAsShape( array("bpmn:process","bpmn:lane","bpmn:task","bpmn:startEvent","bpmn:endEvent") );

        $output .= $this->writeDiagramSequences();

        $output .= $this->closeTagWithTabs("bpmndi:BPMNPlane");

        $output .= $this->closeTagWithTabs("bpmndi:BPMNDiagram");

        return $output;
    }

    private function writeComponentsAsShape( $tags )
    {
        $output = "";

        foreach( $tags as $tag )
        {
            $components = Generator::filterComponentsByMetadata("tagName", $tag);

            foreach($components as $component)
            {
                $output .= $this->writeShape($component);
            }
        }

        return $output;
    }

    private function writeBounds($rect)
    {
        $output = "";

        $output .= $this->openTag("dc:Bounds",
            array(
                "x"=>$rect->x(),
                "y"=>$rect->y(),
                "width"=>$rect->width(),
                "height"=>$rect->height()
            )
        );

        $output .= $this->closeTag("dc:Bounds");

        return $output;
    }


    private function writeShape($component)
    {
        $name = Generator::getMetadata("name", $component);
        $tagName = ucfirst(str_replace("bpmn:","",Generator::getMetadata("tagName",$component)));
        $output = "";
        $output .= $this->openTag("bpmndi:BPMNShape",
            array(
                "id" => $tagName."_".$component->hash()."_di",
                "bpmnElement" => $component->hash()
            )
        ).PHP_EOL;

        $rect = $component->geometry();

        $output .= $this->writeBounds( $rect );

        if (! empty($name)) {
            $output .= $this->openTag("bpmndi:BPMNLabel").PHP_EOL;

            $rectLabel = new \N2OTI\GM\Geometry\Rect($rect->x(), $rect->y()+$rect->height(),
                                                       $rect->width(), 12);

            $output .= $this->writeBounds( $rectLabel );

            $output .= $this->closeTagWithTabs("bpmndi:BPMNLabel");
        }

        $output .= $this->closeTagWithTabs("bpmndi:BPMNShape");

        return $output;
    }

    private function getPlaneAttributes()
    {
        return array("id"=>"BPMNPlane_1","bpmnElement"=>$this->_collaborationId);
    }

    private function getDefinitionAttributes()
    {
        return array(
            "id"=>"Definitions_1",
            "targetNamespace"=>"http://bpmn.io/schema/bpmn",
            "xmlns:di"=>"http://www.omg.org/spec/DD/20100524/DI",
            "xmlns:dc"=>"http://www.omg.org/spec/DD/20100524/DC",
            "xmlns:bpmndi"=>"http://www.omg.org/spec/BPMN/20100524/DI",
            "xmlns:bpmn"=>"http://www.omg.org/spec/BPMN/20100524/MODEL",
            "xmlns:xsi"=>"http://www.w3.org/2001/XMLSchema-instance"
        );
    }

    private function writeCollaboration()
    {
        $output = "";

        $output .= $this->openTag("bpmn:collaboration",$this->getCollabAttributes()).PHP_EOL;

        $processes = Generator::filterComponentsByMetadata("tagName","bpmn:process");

        foreach( $processes as $process )
        {
            $output .= $this->openTag("bpmn:participant",
                array(
                    "id"=> Generator::randomHash(),
                    "processRef"=>$process->hash()
                )
            );
            $output .= $this->closeTag("bpmn:participant");
        }

        $output .= $this->closeTagWithTabs("bpmn:collaboration");

        return $output;
    }

    private function closeTagWithTabs($tag)
    {
        return $this->closeTag($tag, str_repeat("    ",self::$tabCounter-1));
    }

    private function getCollabAttributes()
    {
        return array("id"=>$this->_collaborationId);
    }

    private function openTag($tag, $attributes=array(), $innerText="")
    {
        $output = str_repeat("    ", self::$tabCounter) . "<".$tag;

        if (! empty($attributes))
        {
            $output .= " ";

            $strAttributes=array();

            foreach($attributes as $key => $value)
            {
                $strAttributes[] = $key.'="'.$value.'"';
            }

            $output .= implode(" ",$strAttributes);
        }
        ++self::$tabCounter;

        return $output.">".$innerText;
    }

    private function openComponentTag( $component )
    {
        $tagName = Generator::getMetadata("tagName",$component);
        $name    = Generator::getMetadata("name",$component);
        $text    = Generator::getMetadata("text",$component);

        $attributes = array(
            "id" => $component->hash(),
            "name" => $name
        );

        return $this->openTag($tagName, $attributes, $text);
    }

    private function closeComponentTag($component)
    {
        return $this->closeTag(Generator::getMetadata("tagName",$component), str_repeat("    ",self::$tabCounter-1));
    }

    private function closeTag( $tag, $spacer = "" )
    {
        --self::$tabCounter;
        return $spacer . sprintf('</%s>', $tag).PHP_EOL;
    }

    private function writeLanes()
    {
        $lanes = Generator::filterComponentsByMetadata("tagName","bpmn:lane");

        $output = "";

        foreach($lanes as $lane)
        {
            $output .= $this->openComponentTag($lane).PHP_EOL;

            foreach($lane->children()  as $child)
            {
                if ( $child->previousComponent() != null )
                {
                    $this->_sequences[] = array($child->previousComponent()->hash(),
                                                $child->hash());
                }

                $output .= $this->openTag("bpmn:flowNodeRef",array(), $child->hash());
                $output .= $this->closeTag("bpmn:flowNodeRef");
            }
            $output .= $this->closeComponentTag($lane);
        }

        return $output;
    }

    private function drawSequences()
    {
        $output = "";

        foreach($this->_sequences as $key => $sequence)
        {
            $sequenceData = array(
                    "id" => Generator::randomHash(),
                    "sourceRef" => $sequence[0],
                    "targetRef" => $sequence[1]
                );

            $this->_sequences[$key]["id"] = $sequenceData["id"];

            $output .= $this->openTag("bpmn:sequenceFlow", $sequenceData);

            Generator::writeMetadata("incomingSequenceId", $sequenceData["id"], Generator::get($sequence[1]));
            Generator::writeMetadata("outgoingSequenceId", $sequenceData["id"], Generator::get($sequence[0]));

            $output .= $this->closeTag("bpmn:sequenceFlow");
        }

        return $output;
    }

    private function drawVisibleComponent($component)
    {

        $output = "";
        $output .= $this->openComponentTag($component).PHP_EOL;

        if ($component->previousComponent() != null)
        {
            $output .= $this->openTag("bpmn:incoming",array(),Generator::getMetadata("incomingSequenceId",$component));
            $output .= $this->closeTag("bpmn:incoming");
        }

        if ($component->nextComponent() != null)
        {
            $output .= $this->openTag("bpmn:outgoing",array(),Generator::getMetadata("outgoingSequenceId",$component));
            $output .= $this->closeTag("bpmn:outgoing");
        }

        $output .= $this->closeComponentTag($component);

        return $output;
    }

    private function drawVisibleComponentByTagName($tag)
    {
        $output = "";
        $items = Generator::filterComponentsByMetadata("tagName",$tag);
        foreach($items as $item)
        {
            $output .= $this->drawVisibleComponent($item);
        }
        return $output;
    }

    private function drawVisibileComponentsByTagName($tags = array())
    {
        $output =  "";
        foreach($tags as $tag)
        {
            $output .= $this->drawVisibleComponentByTagName($tag);
        }
        return $output;
    }

    private function writeLaneSet()
    {
        $output = "";

        $output .= $this->openTag("bpmn:laneSet").PHP_EOL;

        $output .= $this->writeLanes();

        $output .= $this->closeTagWithTabs("bpmn:laneSet");

        return $output;
    }
}
