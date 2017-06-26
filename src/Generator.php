<?php

namespace N2OTI\GM;

/**
 * @todo implementar interface I/O para metadados
 */
class Generator
{
    const COMPONENT="N2OTI\GM\Component\Component";

    /**
     * @var array
     */
    private static $components = array();
    private static $metadataIndex   = array();
    private static $metadata   = array();

    /**
     *
     * @param string $name
     * @return \N2OTI\GM\Component\BaseComponent
     */
    public static function create( $name )
    {
        $component = new $name;

        self::$components[$component->hash()] = $component;

        return $component;
    }

    public static function randomHash()
    {
        return str_shuffle(hash('md5', rand(0,time())));
    }

    /**
     *
     * @param string $hash
     * @return \N2OTI\GM\Component\BaseComponent
     */
    public static function get( $hash )
    {
        return self::$components[ $hash ];
    }

    public static function writeMetadata( $name, $value, $component )
    {
        self::$metadata[$component->hash().":". $name] = $value;

        if (empty(self::$metadataIndex[$name.":".$value]))
        {
            self::$metadataIndex[$name.":".$value] = array();
        }

        self::$metadataIndex[$name.":".$value][] = $component;
    }

    public static function filterComponentsByMetadata( $name, $value )
    {
        return self::$metadataIndex[$name.":".$value];
    }

    public static function getMetadata($name, $component)
    {
        if (! empty(self::$metadata[$component->hash().":".$name])) {
             return self::$metadata[$component->hash().":".$name];
        }
    }
}
