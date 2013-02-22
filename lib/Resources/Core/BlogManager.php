<?php
/**
 * Classe de génération de page dynamique
 * Récupère les templates de page
 * 
 * Retourne la vue correspondant
 *
 * @author Flo
 */
class BlogManager {
    
    private $twig;
    
    function __construct($twigPath = PATH_TO_IMPORTANT_FILES) {
        $this->twig = FwkLoader::getTwigEnvironement($twigPath);
    }

    
    /**
     * Récupère l'ensemble des templates de page dynamiques
     */
    public static function getTemplates($path = 'web/views/blogTemplates'){
        
        $arrayDir = FwkUtils::getDirList(PATH_TO_IMPORTANT_FILES . $path);
        
        $fullArray = array();
        foreach($arrayDir as $oneFile){
            
            $fullArray[] = preg_replace('#\.html\.twig#', '', $oneFile);
            
        }
        
        return $fullArray;
        
    }
    
    /**
     * Display the post with the associate template
     * 
     * @param Resources\Entities\BlogPost $objBlogPost
     */
    public function loadTemplate(Resources\Entities\BlogPost $objBlogPost){
        
        $template = $this->twig->loadTemplate('views/blogTemplates/' . $objBlogPost->getTemplateUrl() . '.html.twig');
        
        require_once __DIR__.'/../../../controllers/PageController.php';
        $objPageController = new PageController($objBlogPost->getTemplateUrl());
        $params = $objPageController->getParameters();
        
        
        $params['objPost'] = $objBlogPost;
        
        echo $template->render($params);
        
        
    }
    
}

?>
