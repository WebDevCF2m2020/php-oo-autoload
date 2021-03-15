<?php

// récupération des sections pour le menu
$sectionsForMenu = $TheSectionManager->getAllWithoutTheSectionDesc();

// si on essaie de se connecter
if(isset($_GET['p'])&&$_GET['p']==="connect"){
    
    // si on a envoyé le formulaire
    if(!empty($_POST)){
        $theUserInstance = new TheUser($_POST);
        $recup = $TheUserManager->connectTheUser($theUserInstance);
        // on s'est correctement connecté
        if(array_key_exists(0,$recup)){
            var_dump($_SESSION);
            // ON EST ICI
        }
    }
    
    echo $twig->render("publicView/connect_public.html.twig",["menu"=>$sectionsForMenu]);
    exit();
}

// si on veut voir le détail d'une rubrique et ses articles
if(isset($_GET['section'])&& ctype_digit($_GET['section'])){
    
    // string to int
    $idSection = (int) $_GET['section'];
    
    // récupartion de la section
    $recupSection = $TheSectionManager->getTheSectionById($idSection);
    
    //récupération des news de la section
    $recupTheNews = $TheNewsManager->getAllNewsInTheSection($idSection);
    
    // vérification des codes erreurs du tableau 0=> pas d'erreurs, 1 => warnings, 2 = error 404
    if(array_key_exists(0,$recupSection)){
        
        // transformation du tableau en instance
        $recupSection = $recupSection[0];
        
        // affichage de la vue si pas d'erreurs 404
    echo $twig->render("publicView/section_public.html.twig",["menu"=>$sectionsForMenu,"news"=>$recupTheNews,"detailSection"=>$recupSection]);
            
    }elseif (array_key_exists(2,$recupSection)) {
        // transformation du tableau en chaîne de caractère
        $error = $recupSection[2];
        // CREATE 404 error
        echo $twig->render("publicView/error_404.html.twig",["menu"=>$sectionsForMenu,"error"=>$error]);
    }
    
    
    exit();
}

// si on veut voir le détail d'une news
if(isset($_GET['page'])){

    $slugNews = htmlspecialchars(strip_tags(trim($_GET['page'])),ENT_QUOTES);
    $recupOneNews = $TheNewsManager->getDetailNews($slugNews);
    
    if(array_key_exists(0,$recupOneNews)){
       
        $recupOneNews = $recupOneNews[0];
    // Appel de la vue (objet de type Twig, la méthode render utilise un modèle Twig se trouvant dans view, suivi de paramètres)
    echo $twig->render("publicView/detail_news_public.html.twig",["menu"=>$sectionsForMenu,"news"=>$recupOneNews]);
    
    }elseif (array_key_exists(2,$recupOneNews)) {
        // transformation du tableau en chaîne de caractère
        $error = $recupOneNews[2];
        // CREATE 404 error
        echo $twig->render("publicView/error_404.html.twig",["menu"=>$sectionsForMenu,"error"=>$error]);
    }

   exit();
}


// récupération de toutes les news quelque soient leur section ou auteur
$newsForHomepage = $TheNewsManager->getAllHomePage();


// Appel de la vue (objet de type Twig, la méthode render utilise un modèle Twig se trouvant dans view, suivi de paramètres)
echo $twig->render("publicView/index_public.html.twig",["menu"=>$sectionsForMenu,"news"=>$newsForHomepage]);