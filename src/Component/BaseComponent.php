<?php

namespace N2OTI\GM\Component;

interface BaseComponent
{
    /**
     *
     *
     * @return string
     */
    public function hash();

    public function insert( &$newChild );
    public function insertAfter( &$newChild, &$referenceChild );

    /**
     * @return BaseComponent
     */
    public function parent();

    /**
     * @return Layout
     */
    public function layout();

    /**
     * @return void
     */
    public function setParent( &$parent );

    /**
     *
     * @return N2OTI\GM\Geometry\Size
     */
    public function sizeHint();
}
