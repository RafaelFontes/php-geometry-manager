<?php

namespace N2OTI\GM\Component\Layout;
use N2OTI\GM\Component\Component;
use N2OTI\GM\Geometry\Rect;
use N2OTI\GM\Component\Policy\SizePolicy;

abstract class Layout
{
    /**
     *
     * @var Rect
     */
    protected $_geometry=null;

    /**
     * @var Component
     */
    private $_parentComponent=null;

    private $_margin=10;
    private $_spacing=10;

    public function setParentComponent( $component )
    {
        $this->_parentComponent = $component;
    }

    /**
     *
     * @param int $value
     * @return void
     */
    public function setMargin( $value )
    {
        $this->_margin = $value;
    }

    /**
     *
     * @param int $value
     * @return void
     */
    public function setSpacing( $value )
    {
        $this->_spacing = $value;
    }

    /**
     * Space between parent and children
     *
     * @return int
     */
    public function margin()
    {
        return $this->_margin;
    }

    /**
     * Space between children
     *
     * @return int
     */
    public function spacing()
    {
        return $this->_spacing;
    }

    /**
     * @return Component
     */
    protected function parentComponent()
    {
        return $this->_parentComponent;
    }

    /**
     * @return boolean
     */
    public function isEmpty()
    {
        return count($this->parentComponent()->children()) == 0;
    }

    /**
     * @todo: cover when parentComponent has a parent
     *
     * @return void
     */
    public function maxWidth()
    {
        //$parent = $this->parentComponent()->parent();

        $size = $this->parentComponent()->size()->isValid() ? $this->parentComponent()->size() : $this->parentComponent()->sizeHint();

        return \max($size->width() - ($this->margin() * 2),0);
    }

    /**
     * @todo: cover when parentComponent has a parent
     *
     * @return void
     */
    public function maxHeight()
    {
        $size = $this->parentComponent()->size()->isValid() ? $this->parentComponent()->size() : $this->parentComponent()->sizeHint();

        return \max($size->height() - ($this->margin() * 2),0);
    }

    public function totalStretch()
    {
        $totalStretch = 0;

        foreach( $this->parentComponent()->children() as $child )
        {
            $totalStretch+=$child->stretch();
        }

        return $totalStretch;
    }

    abstract function calcGeometry();
}
