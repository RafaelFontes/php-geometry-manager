<?php

namespace N2OTI\GM\Geometry;

class Rect
{
    /**
     *
     *
     * @var Size
     */
    private $_size;

    /**
     *
     *
     * @var Point
     */
    private $_point;

    /**
     *
     *
     * @param int $x
     * @param int $y
     * @param int $width
     * @param int $height
     */
    public function __construct( $x, $y, $width, $height )
    {
        $this->_point = new Point( $x, $y );
        $this->_size = new Size( $width, $height );
    }

    public function x()
    {
        return $this->point()->x();
    }

    public function y()
    {
        return $this->point()->y();
    }

    public function setX( $value )
    {
        $this->point()->setX( $value );
    }

    public function setY( $value )
    {
        $this->point()->setY( $value );
    }

    public function width()
    {
        return $this->size()->width();
    }

    public function height()
    {
        return $this->size()->height();
    }

    public function setWidth( $value )
    {
        $this->size()->setWidth( $value );
        if (! $this->size()->isValid()) {
            $this->size()->setHeight(0) ;
        }
    }

    public function setHeight( $value )
    {
        $this->size()->setHeight( $value );
        if (! $this->size()->isValid()) {
            $this->size()->setWidth(0) ;
        }
    }

    public function size()
    {
        return $this->_size;
    }

    /**
     *
     * @return Point
     */
    public function point()
    {
        return $this->_point;
    }

    /**
     *
     * @param \N2OTI\GM\Size $size
     * @return void
     */
    public function setSize( $size )
    {
        $this->size()->setWidth( $size->width() );
        $this->size()->setHeight( $size->height() );
    }
}
