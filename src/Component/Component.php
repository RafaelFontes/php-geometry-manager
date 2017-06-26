<?php

namespace N2OTI\GM\Component;

use N2OTI\GM\Component\BaseComponent;
use N2OTI\GM\Geometry\Rect;
use N2OTI\GM\Component\Policy\SizePolicy;
use N2OTI\GM\Component\Layout\Layout;
use N2OTI\GM\Geometry\Size;
use N2OTI\GM\Component\Policy\PolicyFlag;

class Component implements BaseComponent
{
    /**
     * @var SizePolicy
     */
    private $_sizePolicy=null;

    /**
     *
     *
     * @var Rect
     */
    private $_rect=null;

    /**
     * @var Layout
     */
    private $_layout=null;

    /**
     * @var Component
     */
    private $_parent=null;

    /**
     * @var string
     */
    private $_hash = "";

    /**
     * @var Hash<string, Component>
     */
    private $_children=null;

    private $_lastChild=null;


    /**
     * @var Component
     */
    private $_previousComponent=null;
    private $_nextComponent=null;

    private $_stretch=1;

    /**
     *
     */
    public function __construct()
    {
        $this->_sizePolicy = new SizePolicy( SizePolicy::PREFERED, SizePolicy::PREFERED );
        $this->_rect = new Rect( 0, 0, -1, -1 );
        $this->_children = array();
    }

    /**
     * @param int $value
     * @return void
     */
    public function setStretch($value)
    {
        $this->_stretch = $value;
    }

    /**
     * @return int
     */
    public function stretch()
    {
        return $this->_stretch;
    }

    /**
     * @return SizePolicy
     */
    public function sizePolicy()
    {
        return $this->_sizePolicy;
    }

    /**
     * @param Component $component
     * @return void
     */
    private function setPreviousComponent( $component )
    {
        $this->_previousComponent = $component;
    }

    private function setNextComponent($component)
    {
        $this->_nextComponent = $component;
    }

    /**
     *
     *
     * @return Component
     */
    public function nextComponent()
    {
        return $this->_nextComponent;
    }

    /**
     * @return Component
     */
    public function previousComponent()
    {
        return $this->_previousComponent;
    }

    /**
     * @param SizePolicy $value
     * @return void
     */
    public function setSizePolicy( $value )
    {
        $this->_sizePolicy = $value;
    }

    /**
     * @return string
     */
    public function hash()
    {
        if ( empty($this->_hash) )
        {
            $this->_hash = str_shuffle(hash('crc32', rand(0,time())));
        }

        return $this->_hash;
    }

    /**
     * @param BaseComponent $newChild
     * @return void
     */
    public function insert( &$newChild )
    {
        $this->_children[$newChild->hash()] = $newChild;

        $newChild->setParent( $this );

        if ( $newChild->previousComponent() == null && $this->_lastChild != null )
        {
            $newChild->setPreviousComponent( $this->_lastChild );
            $this->_lastChild->setNextComponent($newChild);
        }

        $this->_lastChild = $newChild;
    }

    /**
     * @param BaseComponent $newChild
     * @param BaseComponent $referenceChild
     * @return void
     */
    public function insertAfter( &$newChild, &$referenceChild )
    {
        $newChild->setPreviousComponent( $referenceChild );
        $referenceChild->setNextComponent($newChild);
        $this->insert( $newChild );
    }

    /**
     * @return array
     */
    public function children()
    {
        return $this->_children;
    }

    /**
     * @return Component
     */
    public function parent()
    {
        return $this->_parent;
    }

    /**
     * @param BaseComponent $value
     * @return void
     */
    public function setParent( &$value )
    {
        $this->_parent = $value;
    }

    /**
     * @param Layout $layout
     * @return void
     */
    public function setLayout( $layout )
    {
        $this->_layout = $layout;
        $layout->setParentComponent( $this );
    }

    /**
     * @return \N2OTI\GM\Component\Layout\Layout
     */
    public function layout()
    {
        return $this->_layout;
    }

    /**
     * @return Rect
     */
    public function geometry($calcGeometry=true)
    {
        $this->_rect->setSize( $this->size()->isValid() ? $this->size() : $this->sizeHint() );

        if ( $calcGeometry && $this->parent() != null )
        {
            $this->parent()->layout()->calcGeometry();
        }

        return $this->_rect;
    }

    /**
     *
     * @param Rect $rect
     * @return void
     */
    public function setGeometry( $rect )
    {
        $this->_rect = $rect;
    }

    /**
     * Component's size
     *
     * @return \N2OTI\GM\Geometry\Size
     */
    public function size()
    {
        return $this->_rect->size();
    }

    /**
     *
     *
     * @return \N2OTI\GM\Geometry\Point
     */
    public function point()
    {
        return $this->_rect->point();
    }

    /**
     * Hint of the size for the component
     *
     * @return Size
     */
    public function sizeHint()
    {
        return new Size( 50, 50 );
    }

    /**
     * @param int $value
     * @return void
     */
    public function setWidth( $value )
    {
        $this->_rect->setWidth( $value );
    }

    /**
     * @param int $value
     * @return void
     */
    public function setHeight( $value )
    {
        $this->_rect->setHeight( $value );
    }
}
