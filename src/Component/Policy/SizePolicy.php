<?php

namespace N2OTI\GM\Component\Policy;

class SizePolicy
{
    const FIXED = 0;
    const MINIMUM = PolicyFlag::GROW_FLAG;
    const MAXIMUM = PolicyFlag::SHRINK_FLAG;
    const PREFERED = PolicyFlag::GROW_FLAG |PolicyFlag::SHRINK_FLAG;
    const MINIMUM_EXPANDING = PolicyFlag::GROW_FLAG | PolicyFlag::EXPAND_FLAG;
    const EXPANDING = PolicyFlag::GROW_FLAG | PolicyFlag::SHRINK_FLAG | PolicyFlag::EXPAND_FLAG;
    const IGNORED = PolicyFlag::SHRINK_FLAG | PolicyFlag::GROW_FLAG | PolicyFlag::IGNORE_FLAG;

    private $_horizontalPolicy;
    private $_verticalPolicy;

    /**
     * @param int $horizontalPolicy
     * @param int $verticalPolicy
     */
    public function __construct( int $horizontalPolicy, int $verticalPolicy )
    {
        $this->_horizontalPolicy = $horizontalPolicy;
        $this->_verticalPolicy = $verticalPolicy;
    }

    public function getHorizontalPolicy()
    {
        return $this->_horizontalPolicy;
    }

    public function getVerticalPolicy()
    {
        return $this->_horizontalPolicy;
    }
}
