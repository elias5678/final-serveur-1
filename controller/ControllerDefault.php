<?php
/**
 * Contrôleur par défaut. Il permet d'afficher la page d'accueil.
 */
class ControllerDefault extends Controller
{
    function accueil()
    {
        $vue = new ViewCreator('view/accueil.phtml');
        $vue->assign('uneVariable', "Bonjour la terre!");
        echo $vue->render();
    }

    function default()
    {
        $this->accueil();
    }
}
