<?php

/**
 * Un contrôleur est responsable de gérer un ensemble d'actions qui ont un
 * lien étroit entre elles.
 * 
 * Ex: Gestion des inscriptions, gestion de l'authentification, etc.
 * 
 * Un controleur doit obligatoirement implémenter
 * une action dite par défaut : "default".
 * 
 * À la fin de l'exécution d'une action, 
 * le controleur doit générer une vue et l'afficher.
 */
abstract class Controller
{
    /**
     * Configuration qui permettra de construire les ModelRepository nécessaires
     */
    protected ModelRepositoryConfig $configBD;

    /**
     * Constructeur 
     * 
     * @param ModelRepositoryConfig $config La configuration pour construire des ModelRepository
     */
    public function __construct(ModelRepositoryConfig $configBD)
    {
        $this->configBD = $configBD;
    }

    /**
     * Action par défaut du contrôleur.
     */
    abstract function default();
}
