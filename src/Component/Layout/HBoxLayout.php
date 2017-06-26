<?php

namespace N2OTI\GM\Component\Layout;

use N2OTI\GM\Component\Policy\SizePolicy;
use N2OTI\GM\Geometry\Rect;
use N2OTI\GM\Component\Policy\PolicyFlag;

class HBoxLayout extends Layout
{
    /**
     * @param Rect $rect
     * @return void
     */
    public function calcGeometry()
    {
        $component = $this->parentComponent();
        $index = -1;
        $totalStretch = $this->totalStretch();

        $lastX = $this->margin()+$component->point()->x();
        $usableWidth = max(0,$this->maxWidth() - ((count($component->children()) - 1) * $this->spacing() ));
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

            $newWidth = ( $totalStretch > 0 ) ? $usableWidth * ($child->stretch() / $totalStretch) : 0;

            if ($hPolicy & PolicyFlag::EXPAND_FLAG > 0)
            {
                $size->setWidth($newWidth);
            }

            if ($vPolicy & PolicyFlag::EXPAND_FLAG > 0)
            {
                // @todo consider child sizepolicy to define its height ( using parent max height )
                $size->setHeight( $this->maxHeight() );
            }

            // @todo consider grow and shrink policy to define its width

            $rect->setSize($size);

            if ( $child->previousComponent() != null && $child->previousComponent()->parent() != null &&
                 $child->previousComponent()->parent()->hash() != $component->hash() )
            {
                $previousChildRect = $child->previousComponent()->geometry(false);
                $lastX = $previousChildRect->x() + ( ($previousChildRect->width() - $rect->width()) / 2 );
            }

            $rect->setX( $lastX  );

            $lastX += $newWidth + $this->spacing();

            $rect->setY( $this->margin()+$component->point()->y() + (($this->maxHeight() - $rect->height()) / 2) );

            $child->setGeometry($rect);

        }
    }
}
