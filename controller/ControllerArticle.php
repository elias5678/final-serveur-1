<?php

/**
 * Contrôleur pour la gestion des blogues 
 */
class ControllerArticle extends Controller
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


    function consulter()
    {
        $idArticle = filter_input(INPUT_GET, 'idarticle', FILTER_SANITIZE_NUMBER_INT);
        $article = $this->articleRepo->select($idArticle);

        $vue = new ViewCreator("view/article.phtml");
        $vue->assign("article", $article);
        echo $vue->render();
    }


    function ajouter()
    {
        $blogue = $this->recupererBlogue();
        $varsToAssign = array('blogue' => $blogue);
        if (isset($blogue))
        {
            $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $texte = filter_input(INPUT_POST, 'texte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            if (isset($titre) && isset($texte))
            {
                try
                {
                    $nvArticle = new Article($titre, $texte, $blogue);

                    if (isset($tags))
                    {
                        foreach ($tags as $idTag)
                        {
                            $tag = $this->tagRepo->select($idTag);
                            if ($tag != null)
                                $nvArticle->addTag($tag);
                        }
                    }

                    $nvId = $this->articleRepo->insert($nvArticle);
                    if ($nvId > 0)
                    {
                        $titre = '';
                        $texte = '';
                        $tags = array();
                        $info = "L'article a été ajouté avec succès.";
                    }
                    else
                    {
                        $erreurs[] = "Erreur à la création de l'article en BD.";
                    }
                }
                catch (Exception $ex)
                {
                    $erreurs[] = $ex->getMessage();
                }
            }
        }

        $varsToAssign['titre'] = $titre;
        $varsToAssign['texte'] = $texte;
        $varsToAssign['tags'] = $tags;
        $varsToAssign['erreurs'] = $erreurs;
        $varsToAssign['info'] = $info;
        $this->afficherBlogue($varsToAssign);
    }


    function supprimer()
    {
        $blogue = $this->recupererBlogue();
        $varsToAssign = array('blogue' => $blogue);
        if (isset($blogue))
        {
            $idarticle = filter_input(INPUT_POST, 'idarticle', FILTER_SANITIZE_NUMBER_INT);
            if (isset($idarticle))
            {
                $succes = $this->articleRepo->delete($idarticle);
                if ($succes)
                    $info = "Article supprimé avec succès!";
                else
                    $erreurs[] = "Erreur à la suppression de l'article.";
            }
        }

        $varsToAssign['erreurs'] = $erreurs;
        $varsToAssign['info'] = $info;
        $this->afficherBlogue($varsToAssign);
    }


    function demandeModification()
    {
        $blogue = $this->recupererBlogue();
        $varsToAssign = array('blogue' => $blogue);
        if (isset($blogue))
        {
            $idarticle = filter_input(INPUT_POST, 'idarticle', FILTER_SANITIZE_NUMBER_INT);
            if (isset($idarticle))
            {
                $articleAModifier = $this->articleRepo->select($idarticle);
                if ($articleAModifier != null)
                {
                    $titre = $articleAModifier->getTitre();
                    $texte = $articleAModifier->getTexte();
                    $tags = array();
                    foreach ($articleAModifier->getTags() as $tag)
                    {
                        $tags[] = $tag->getId();
                    }
                }
            }
        }

        $varsToAssign['articleAModifier'] = $articleAModifier;
        $varsToAssign['titre'] = $titre;
        $varsToAssign['texte'] = $texte;
        $varsToAssign['tags'] = $tags;
        $this->afficherBlogue($varsToAssign);
    }


    function modifier()
    {
        $blogue = $this->recupererBlogue();
        $varsToAssign = array('blogue' => $blogue);
        if (isset($blogue) && isset($_POST['boutonModifier']))
        {
            $idarticle = filter_input(INPUT_POST, 'idarticle', FILTER_SANITIZE_NUMBER_INT);
            $titre = filter_input(INPUT_POST, 'titre', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $texte = filter_input(INPUT_POST, 'texte', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $tags = filter_input(INPUT_POST, 'tags', FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);

            if (isset($idarticle) && isset($titre) && isset($texte))
            {
                $articleAModifier = $this->articleRepo->select($idarticle);
                if ($articleAModifier != null)
                {
                    try
                    {
                        $articleAModifier->setTitre($titre);
                        $articleAModifier->setTexte($texte);
                        $articleAModifier->setTags(array());
                        if (isset($tags))
                        {
                            foreach ($tags as $idTag)
                            {
                                $tag = $this->tagRepo->select($idTag);
                                if ($tag != null)
                                    $articleAModifier->addTag($tag);
                            }
                        }

                        $succes = $this->articleRepo->update($articleAModifier);
                        if ($succes)
                        {
                            $articleAModifier = null;
                            $titre = '';
                            $texte = '';
                            $tags = array();
                            $info = "L'article a été modifié avec succès.";
                        }
                        else
                        {
                            $erreurs[] = "Erreur à la modification de l'article en BD.";
                        }
                    }
                    catch (Exception $ex)
                    {
                        $erreurs[] = $ex->getMessage();
                    }
                }
            }
        }

        $varsToAssign['articleAModifier'] = $articleAModifier;
        $varsToAssign['titre'] = $titre;
        $varsToAssign['texte'] = $texte;
        $varsToAssign['tags'] = $tags;
        $varsToAssign['erreurs'] = $erreurs;
        $varsToAssign['info'] = $info;
        $this->afficherBlogue($varsToAssign);
    }


    function default()
    {
        $this->consulter();
    }


    private function recupererBlogue()
    {
        $blogue = null;
        if (isset($_REQUEST['idblogue']))
        {
            $idBlogue = filter_var($_REQUEST['idblogue'], FILTER_SANITIZE_NUMBER_INT);
            $blogue = $this->utilisateurRepo->select($idBlogue);
        }

        return $blogue;
    }


    private function afficherBlogue($varsToAssign)
    {
        $blogue = $varsToAssign['blogue'];
        if (isset($blogue))
        {
            $varsToAssign['articles'] = $this->articleRepo->selectAll($blogue->getId());
            $varsToAssign['tagsRef'] = $this->tagRepo->selectAll();
        }

        $vue = new ViewCreator("view/blogue.phtml", $varsToAssign);
        echo $vue->render();
    }
}
