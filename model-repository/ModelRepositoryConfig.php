<?php
/**
 * Classe de configuration pour la construction d'un ModelRepository.
 */
class ModelRepositoryConfig
{
    private string $nomBD;
    private string $adresseHote;
    private string $nomUtilisateur;
    private string $motDePasse;
    private string $encodage;
    private string $moteur;

    public function __construct(
        string $nomBD,
        string $adresseHote,
        string $nomUtilisateur,
        string $motDePasse,
        string $encodage = 'utf8mb4',
        string $moteur = 'mysql'
    )
    {
        $this->nomBD = $nomBD;
        $this->adresseHote = $adresseHote;
        $this->nomUtilisateur = $nomUtilisateur;
        $this->motDePasse = $motDePasse;
        $this->encodage = $encodage;
        $this->moteur = $moteur;
    }

    public function creerChaineConnexion() : string
    {
        return "$this->moteur:dbname=$this->nomBD;host=$this->adresseHote;charset=$this->encodage;";
    }

    public function getNomBD() : string
    {
        return $this->nomBD;
    }

    public function getAdresseHote() : string
    {
        return $this->adresseHote;
    }

    public function getNomUtilisateur() : string
    {
        return $this->nomUtilisateur;
    }

    public function getMotDePasse() : string
    {
        return $this->motDePasse;
    }

    public function getEncodage() : string
    {
        return $this->encodage;
    }
    
    public function getMoteur() : string
    {
        return $this->moteur;
    }
}