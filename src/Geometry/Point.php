<?php

namespace N2OTI\GM\Geometry;

class Point
{
    private $_x;
    private $_y;

    public function __construct( $x, $y )
    {
        $this->_x = $x;
        $this->_y = $y;
    }

    public function x()
    {
        return $this->_x;
    }

    public function y()
    {
        return $this->_y;
    }

    public function setX( $value )
    {
        $this->_x = $value;
    }

    public function setY( $value )
    {
        $this->_y = $value;
    }
}
