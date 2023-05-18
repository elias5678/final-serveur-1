<?php

/**
 * Contrôleur pour la gestion des blogues 
 */
class ControllerBlogue extends Controller
{
    private UtilisateurRepository $utilisateurRepo;
    private CommentaireRepository $commentaireRepo;
    private ArticleRepository $articleRepo;
    private TagRepository $tagRepo;


    function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->utilisateurRepo = new UtilisateurRepository($config);
        $this->commentaireRepo = new CommentaireRepository($config, $this->utilisateurRepo);
        $this->articleRepo = new ArticleRepository($config, $this->commentaireRepo, $this->utilisateurRepo);
        $this->tagRepo = new TagRepository($config);
    }


    function lister()
    {
        $blogues = $this->utilisateurRepo->selectAll();
        $vue = new ViewCreator("view/liste-blogues.phtml");
        $vue->assign("blogues", $blogues);
        echo $vue->render();
    }


    function consulter()
    {
        $varsToAssign = array();
        if (isset($_REQUEST['idblogue']))
        {
            $idBlogue = filter_var($_REQUEST['idblogue'], FILTER_SANITIZE_NUMBER_INT);
            $blogue = $this->utilisateurRepo->select($idBlogue);
            $varsToAssign['blogue'] = $blogue;

            if (isset($blogue))
            {
                $varsToAssign['articles'] = $this->articleRepo->selectAll($blogue->getId());
                $varsToAssign['tagsRef'] = $this->tagRepo->selectAll();
            }
        }

        $vue = new ViewCreator("view/blogue.phtml", $varsToAssign);
        echo $vue->render();
    }


    function default()
    {
        $this->lister();
    }
}
