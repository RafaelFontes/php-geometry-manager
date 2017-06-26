<?php

namespace N2OTI\GM\Component\Layout;

use N2OTI\GM\Component\Policy\SizePolicy;
use N2OTI\GM\Geometry\Rect;
use N2OTI\GM\Component\Policy\PolicyFlag;

class VBoxLayout extends Layout
{
    public function calcGeometry( )
    {
        $component = $this->parentComponent();
        $index = -1;
        $totalStretch = $this->totalStretch();

        $lastY = $this->margin()+$component->point()->y();
        $usableHeight = max(0,$this->maxHeight() - ((count($component->children()) - 1) * $this->spacing() ));

         /**
         * @var Component $child
         */
        foreach( $component->children() as $child )
        {
            ++$index;
            /**
             * @var Rect $rect
             */
            $rect = $child->geometry(false); // get just the geometry without calculations

            $policy = $child->sizePolicy();

            $hPolicy = $policy->getHorizontalPolicy();
            $vPolicy = $policy->getVerticalPolicy();

            $size = $rect->size();

            $newHeight = ( $totalStretch > 0 ) ? $usableHeight * ($child->stretch() / $totalStretch) : 0;

            $rect->setY( $lastY  );

            $lastY += $newHeight + $this->spacing();

            if ($hPolicy & PolicyFlag::EXPAND_FLAG > 0)
            {
                $size->setWidth( $this->maxWidth() );
            }

            if ($vPolicy & PolicyFlag::EXPAND_FLAG > 0)
            {
                $size->setHeight( $newHeight );
            }

            // @todo consider grow and shrink policy to define its width

            $rect->setSize($size);

            $rect->setX( $this->margin()+$component->point()->x() + (($this->maxWidth() - $rect->width()) / 2) );

            $child->setGeometry($rect);

        }
    }
}
