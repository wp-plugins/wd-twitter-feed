<?php

namespace Amarkal\Loaders;

class ClassNotFoundException extends \RuntimeException
{
    public function __construct( $class ) 
    {
        
        parent::__construct( "The class $class could not be found." );
    }
}
