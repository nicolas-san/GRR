<?php

/**
 * @author: Bouteillier Nicolas <http://www.kaizendo.fr>
 * Date: 12/08/15
 * Time: 14:10
 */
/**
 * Load des vendors, et init.
 */
if (@file_exists('../include/connect.inc.php')) {
    $racine = '../';
} else {
    $racine = '';
}
/* load vendors */
require_once $racine . 'vendor/autoload.php';

/**
 * symfony autoloader
 */
require_once $racine . 'vendor/symfony/class-loader/UniversalClassLoader.php';

use Symfony\Component\ClassLoader\UniversalClassLoader;

$loader = new UniversalClassLoader();

// enregistrez les espaces de noms et préfixes ici (voir ci-dessous)
//$loader->registerNamespace('Grr\Event', $racine . 'src/Grr/Event');
//$loader->registerNamespace('Grr', $racine . 'src/Grr/Event');
$loader->registerNamespace('Grr', $racine . 'src/Grr');
// vous pouvez rechercher dans l'« include_path » en dernier recours.
echo "<pre>";
var_dump($loader);
echo "</pre>";
//$loader->useIncludePath(true);
$loader->register();
/*
 * Twig init
 */
global $twig;
$loader = new Twig_Loader_Filesystem($racine.'src/Grr/Resources/Templates/'.$template.'/views/');
/*
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

/*
 * event dispatcher init
 */
use Symfony\Component\EventDispatcher\EventDispatcher;

global $dispatcher;
$dispatcher = new EventDispatcher();

/**
 * je charge les plugins.
 */
include_once 'plugins.php';
