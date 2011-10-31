#!/usr/bin/env php
<?php
/**
 * File containing the image alias image variation generator
 *
 * @copyright Copyright (C) 1999-2011 Brookins Consulting. All rights reserved.
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU GPL v2 (or later)
 * @version //autogentag//
 * @package extension/bcimagealias
 */

// Add a starting timing point tracking script execution time
$srcStartTime = microtime();

// Load existing class autoloads
require 'autoload.php';

// Disable php time limit to prevent script execution time limit errors
set_time_limit( 0 );

// Load cli and script environment
$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' =>
                                     'eZ Publish content object attribute image alias variation creator. ' .
                                     'This script makes sure that content object attribute image' .
                                     ' alias variations are created before they are requested by users.',
                                     'extended-description' => '1. fetch ezcontentclass having an ezimage attribute, ' .
                                                               '2. fetch objects of these classes, ' .
                                                               '3. purge image alias for all version',
                                     'use-session' => false,
                                     'use-modules' => false,
                                     'use-extensions' => true ) );

// Fetch default script options
$options = $script->getOptions( '[force;][dry;][php-bin:]',
                                '[name]', array( 'force' => 'Force generation. Disable delayed startup', 'dry' => 'Display calculated execution. Make no system changes',
                                                 'php-bin' => 'Path to php binary on filesystem. IE: /usr/bin/php'  ) );

// Script parameters
$siteAccess = $options['siteaccess'] ? $options['siteaccess'] : false;
$verbose = isset( $options['verbose'] ) ? true : false;
$force = isset( $options['force'] ) ? true : false;
$dry = isset( $options['dry'] ) ? true : false;
$phpBin = isset( $options['php-bin'] ) ? $options['php-bin'] : '/usr/bin/php';
$generatorWorkerScript = 'extension/bcimagealias/bin/php/bcimagealias.php';
$options = ( $dry ? ' --dry ' : '' )
           . ( $force ? '--force ' : '' )
           . ( $verbose ? '--verbose ' : '' )
           . '--generate';

// General script options
$scriptExecutionOptions = array( 'verbose' => $verbose, 'dry' => $dry );
$script->initialize();
$script->setIterationData( '.', '~' );
$isQuiet = $script->isQuiet();
$script->startup();

$result = false;
passthru( "$phpBin ./$generatorWorkerScript $options;", $result );
print_r($result);

// Shutdown the script and exit eZ
$script->shutdown();

?>
