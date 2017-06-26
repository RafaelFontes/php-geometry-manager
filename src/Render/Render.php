<?php

namespace N2OTI\GM\Render;

class Render
{
    /**
     *
     * @param Component $component
     * @return void
     */
    private function getParentCountOfComponent($component)
    {
        $output = 0;

        $tmpComponent = $component;

        while ( $tmpComponent->parent() != null ) {
            $tmpComponent = $tmpComponent->parent();
            ++$output;
        }

        return $output;
    }

    /**
     *
     * @param Component $component
     * @return string
     */
    function draw($component)
    {
        $output = str_repeat("  ", $this->getParentCountOfComponent($component) );

        $geometry = $component->geometry();

        $output .= sprintf('<component id="%s" x="%d" y="%d" width="%d" height="%d" margin="%d" spacing="%d">',
            $component->hash(),
            $geometry->x(),
            $geometry->y(),
            $geometry->width(),
            $geometry->height(),
            ($component->layout()) ? $component->layout()->margin() : 0,
            ($component->layout()) ? $component->layout()->spacing() : 0
        );

        if (! empty($component->children()))
        {
            $output .= PHP_EOL;
        }

        foreach( $component->children() as $child )
        {
            $output .= $this->draw( $child );
        }

        $output .= "</component>".PHP_EOL;

        return $output;
    }
}
