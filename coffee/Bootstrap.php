<?php

define( 'Path' , realpath(__DIR__ . '/..') );

define( 'Coffee' , Path . '/coffee' );

define( 'App' , Path . '/app' );

require __DIR__.'/Loader.php';

\Coffee\Loader::register();