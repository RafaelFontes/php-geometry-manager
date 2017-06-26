<?php

namespace N2OTI\GM\Geometry;

class Size
{
    private $_width;
    private $_height;

    public function __construct( $width=-1, $height=-1 )
    {
        $this->_width = $width;
        $this->_height = $height;
    }

    public function isValid()
    {
        return $this->_width >= 0 && $this->_height >= 0;
    }

    public function width()
    {
        return $this->_width;
    }

    public function height()
    {
        return $this->_height;
    }

    public function setWidth( $value )
    {
        $this->_width = $value;
    }

    public function setHeight( $value )
    {
        $this->_height = $value;
    }
}
