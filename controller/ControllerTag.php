<?php

/**
 * Contrôleur pour la gestion des tags. 
 */
class ControllerTag extends Controller
{
    private TagRepository $tagRepo;

    public function __construct(ModelRepositoryConfig $config)
    {
        parent::__construct($config);
        $this->tagRepo = new TagRepository($config);
    }

    function lister()
    {
        $vue = new ViewCreator('view/gestion-tag.phtml');
        $vue->assign("tags", $this->tagRepo->selectAll());
        echo $vue->render();
    }

    function ajouter()
    {
        //Gestion de l'ajout (exactement comme avant)
        $nomTag = filter_input(INPUT_POST, 'nomTag', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (isset($nomTag))
        {
            try
            {
                $tag = new Tag($nomTag);
                $this->tagRepo->insert($tag);
                $info = "Tag $nomTag ajouté avec succès!";
                $nomTag = "";
            }
            catch (Exception $ex)
            {
                $erreurs[] = $ex->getMessage();
            }
        }

        // On génère la vue
        $vue = new ViewCreator('view/gestion-tag.phtml');
        $vue->assign("tags", $this->tagRepo->selectAll());
        $vue->assign("tag", $tag);
        $vue->assign("info", $info);
        $vue->assign("erreurs", $erreurs);
        echo $vue->render();
    }

    function supprimer()
    {
        //Gestion de la suppression (exactement comme avant)
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (isset($id))
        {
            $succes = $this->tagRepo->delete($id);
            if ($succes)
                $info = "Tag supprimé avec succès!";
            else
                $erreurs[] = "Erreur à la suppression du tag.";
        }

        // On génère la vue
        $vue = new ViewCreator('view/gestion-tag.phtml');
        $vue->assign("tags", $this->tagRepo->selectAll());
        $vue->assign("info", $info);
        $vue->assign("erreurs", $erreurs);
        echo $vue->render();
    }

    function demandeModification()
    {
        // Gestion de la demande de modification (exactement comme avant)
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        if (isset($id))
        {
            $tagAModifier = $this->tagRepo->select($id);
            if ($tagAModifier != null)
                $nomTag = $tagAModifier->getNom();
        }

        // On génère la vue
        $vue = new ViewCreator('view/gestion-tag.phtml');
        $vue->assign("tags", $this->tagRepo->selectAll());
        $vue->assign("tagAModifier", $tagAModifier);
        $vue->assign("nomTag", $nomTag);
        echo $vue->render();
    }

    function modifier()
    {
        if (isset($_POST['boutonModifier'])) // Ça évite un message de succès en cas d'annulation
        {
            // Gestion de la modification (exactement comme avant)
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
            $nomTag = filter_input(INPUT_POST, 'nomTag', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            if (isset($id) && isset($nomTag))
            {
                $tagAModifier = $this->tagRepo->select($id);
                if ($tagAModifier != null)
                {
                    try
                    {
                        $tagAModifier->setNom($nomTag);
                        $succes = $this->tagRepo->update($tagAModifier);
                        $tagAModifier = null;
                        $nomTag = "";
                        if ($succes)
                            $info = "Tag modifié avec succès!";
                        else
                            $erreurs[] = "Impossible de modifier le tag.";
                    }
                    catch (Exception $ex)
                    {
                        $erreurs[] = $ex->getMessage();
                    }
                }
            }
        }

        // On génère la vue
        $vue = new ViewCreator('view/gestion-tag.phtml');
        $vue->assign("tags", $this->tagRepo->selectAll());
        $vue->assign("tagAModifier", $tagAModifier);
        $vue->assign("nomTag", $nomTag);
        $vue->assign("info", $info);
        $vue->assign("erreurs", $erreurs);
        echo $vue->render();
    }

    function default()
    {
        $this->lister();
    }
}
