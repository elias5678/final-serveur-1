<?php

/**
 * Cette classe abstraite est la classe de base afin
 * d'implémenter des classes qui permettent d'interagir
 * avec une base de données. Essentiellement, elle gère
 * la connexion à la BD via son constructeur et elle assure
 * la "mise à zéro" de la connexion avec son destructeur.
 */
abstract class ModelRepository
{
    protected ModelRepositoryConfig $config;
    protected ?PDO $connexion; // le ? permet de mettre à NULL la variable.

    /**
     * @param ModelRepositoryConfig $config La configuration pour se connecter à la BD
     * 
     * @throws PDOException Erreur de connexion à la base de données.
     */
    public function __construct(ModelRepositoryConfig $config)
    {
        $this->config = $config;
        $this->connexion = new PDO(
            $this->config->creerChaineConnexion(),
            $this->config->getNomUtilisateur(),
            $this->config->getMotDePasse(),
            array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_FOUND_ROWS => true 
            )
        );
    }


    public function getConfig() : ModelRepositoryConfig
    {
        return $this->config;
    }


    /**
     * Destructeur
     */
    function __destruct()
    {
        $this->connexion = null;
    }
}
