<?php
/**
 * @author: Bouteillier Nicolas <http://www.kaizendo.fr>
 * Date: 12/08/15
 * Time: 14:10
 */
/**
 * Load des vendors, et init
 */
if (@file_exists('../include/connect.inc.php')) {
    $racine = '../';
} else {
    $racine = '';
}
/* load vendors */
require_once $racine.'vendor/autoload.php';

/**
 * Twig init
 */
global $twig;
$loader = new Twig_Loader_Filesystem($racine.'src/Main/Resources/Templates/'.$template.'/views/');
/**
 * debug true, and profiler, only for dev env, todo : manage env dev or prod
 */
/*$twig = new Twig_Environment($loader, array(
    'cache' => $racine.'app/cache/',
    'debug' => false,
));*/
$twig = new Twig_Environment($loader, array(
    'cache' => false,
    'debug' => true,
));
$twig->addExtension(new Twig_Extension_Debug());

/**
 * event dispatcher init
 */
use Symfony\Component\EventDispatcher\EventDispatcher;
global $dispatcher;
$dispatcher = new EventDispatcher();
var_dump($dispatcher);

/**
 * je charge les plugins
 */

include_once "plugins.php";