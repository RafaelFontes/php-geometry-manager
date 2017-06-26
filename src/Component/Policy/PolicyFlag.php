<?php

namespace N2OTI\GM\Component\Policy;

class PolicyFlag
{
    /**
     * The component can grow beyond its size hint if necessary.
     */
    const GROW_FLAG=1;
    /**
     * The component should get as much space as possible.
     */
    const EXPAND_FLAG=2;
    /**
     * The component can shrink below its size hint if necessary.
     */
    const SHRINK_FLAG=4;
    /**
     * The component size hint is ignored. The component will get as much space as possible.
     */
    const IGNORE_FLAG=8;
}
