<?php

/**
 * Point d'entrée unique du site web.
 * 
 * Cette classe reçoit la requête HTTP. En fonction des paramètres 'ctrl' et 'action' présents dans GET/POST, 
 * elle redirige le traitement vers le contrôleur approprié.
 */
class RouteManager
{
    private string $defaultCtrl;
    private ModelRepositoryConfig $config;

    /**
     * @param string $defaultCtrl Contrôleur à utiliser par défaut si le  
     *                            paramètre "ctrl" présent dans GET/POST n'existe pas.
     *                            L'action par défaut de ce contrôleur sera appelée.
     * @param ModelRepositoryConfig $config La config à utiliser lors de la construction d'un contrôleur.
     */
    public function __construct(string $defaultCtrl, ModelRepositoryConfig $config)
    {
        $this->defaultCtrl = $defaultCtrl;
        $this->config = $config;
    }


    /**
     * Méthode pour traiter la requête HTTP.
     * @throws Exception Aucun contrôleur n'existe (même celui par défaut). Ne devrait jamais arriver. 
     */
    function execute()
    {
        $paramCtrl = "";

        // À priori, on met l'action à celle par défaut
        $paramAction = 'default';

        // Nous récupérons le paramètre "ctrl" de GET/POST
        if (isset($_REQUEST['ctrl']))
        {
            // Nous construisons le nom de la classe du contrôleur.
            // Note 1: Le tableau $_REQUEST rassemble autant les paramètres $_GET et $_POST
            // Note 2: ucfirst() met en majuscule la première lettre du mot
            $paramCtrl = 'Controller' . ucfirst(filter_var($_REQUEST['ctrl'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
        }

        // À l’aide du nom de la classe du contrôleur, nous vérifions si le contrôleur existe
        if (class_exists($paramCtrl))
        {
            // Le contrôleur existe, nous en construisons une instance
            $controller = new $paramCtrl($this->config);

            // Nous récupérons l'action à faire
            if (isset($_REQUEST['action']))
            {
                $paramAction = filter_var($_REQUEST['action'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                // Si l'action est inexistante, on remet à "default".
                if (!method_exists($controller, $paramAction))
                {
                    $paramAction = 'default';
                }
            }
        }
        // Si le contrôleur n'existe pas, on prend celui par défaut. Ceci peut arriver lors du développement 
        // (erreur de programmation) ou bien si un visiteur du site s'amuse avec le paramètre GET/POST "ctrl"
        else
        {
            if (class_exists($this->defaultCtrl))
            {
                $controller = new $this->defaultCtrl($this->config);
            }
            else
            {
                // Ne devrait jamais arriver car c’est le programmeur qui fourni le contrôleur par défaut!
                throw new Exception("Contrôleur par défaut ($this->defaultCtrl) non trouvé!");
            }
        }

        // Rendu ici, ça ne devrait jamais arriver, mais nous mettons cette vérification par précaution.
        if (!method_exists($controller, $paramAction))
        {
            throw new Exception("L'action $paramAction non trouvée dans le contrôleur " + get_class($controller));
        }

        // On appelle la méthode du contrôleur pour qu'il puisse traiter la requête.
        call_user_func(array($controller, $paramAction));
    }
}
