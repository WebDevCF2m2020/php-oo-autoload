<?php
/*
 * Contrôleur frontal
 */

/*
 * Lancement d'une session
 */
session_start();

/*
 * Config
 */
require_once "../config/config.php";

/*
 * Création d'un autoload personnel, si une classe est recherchée ou instanciée mais qu'elle n'est pas trouvée dans le coeur de PHP ni ses bibliothèques, on va automatiser la recherche de la classe dans notre dossier "model"
 */
spl_autoload_register(
    function($className){
        require "../model/".$className.".php";
    }
);

/*
 * Appel de l'autoload de composer (dossier vendor), ! toutes les classes sont chargées (identifiées par leur namespace), même les non utilisées. L'utilisation des namespaces devient primordiale pour éviter d'avoir des conflits de noms de classes. Toutes les bibliothèques externes de PHP sont généralement chargées via composer
 */
require_once '../vendor/autoload.php';

/*
 * Génération de l'espace de travail pour Twig (après l'autoload de composer)
 * https://twig.symfony.com/doc/3.x/
 */
$loader = new \Twig\Loader\FilesystemLoader('../view'); // lieux où se trouveront nos vues
// création de l'environnement, nous n'activerons le cache que lorsque le travail sera terminé
$twig = new \Twig\Environment($loader, [
    'debug' => true, // mode dev
    // 'cache' => '/path/to/compilation_cache',
]);
// activation du débogage
$twig->addExtension(new \Twig\Extension\DebugExtension());


/*
 * Connexion à la DB
 */
try{
    $myConnect = new MyPDO(DB_TYPE.":host=".DB_HOST.";dbname=".DB_NAME.";port=".DB_PORT.";charset=".DB_CHARSET,DB_LOGIN,DB_PWD,ENV_DEV);
}catch(PDOException $e){
    die($e->getMessage());
}

// Managers communs à tous les publics (ou rôles)
$TheSectionManager = new TheSectionManager($myConnect);
$TheNewsManager = new TheNewsManager($myConnect);
$TheUserManager = new TheUserManager($myConnect);


// Contrôleur publique (pour l'affichage du site lorsqu'on est pas connecté)
require_once "../controller/publicController.php";