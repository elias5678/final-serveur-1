<?php

class Article
 {
//      `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
//     `titre` varchar(50) NOT NULL,
//     `location` boolean not null,
//     `idnuméro` int(10) unsigned NOT NULL AUTO_INCREMENT,
//     `resumer` text NOT NULL,
//     `idAuteurs` int(10) unsigned NOT NULL,
    private int $id;
    private string $titre;
  
    private bool $location;
    private autheurs $auteur;
    private string $resumer;
    private int $idnum;

    public function __construct(
        string $titre,
     
        autheurs $auteur,
        string $resumer,
        int $idnum,
        bool $location = false,
        int $id = 0
    )
    {
        $this->setId($id);
        $this->setTitre($titre);

        $this->setPublie($location);
        $this->setAuteur($auteur);
        $this->setResumer($resumer);
        $this->setIdnum($idnum);
       
    }


    /**
     * Get the value of id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of titre
     *
     * @return string
     */
    public function getTitre(): string
    {
        return $this->titre;
    }

    /**
     * Set the value of titre
     *
     * @param string $titre
     *
     * @return self
     */
    public function setTitre(string $titre): self
    {
        $titre = trim($titre);
        if (empty($titre) || strlen($titre) > 255)
            throw new Exception("Le titre '$titre' doit être entre 1 et 255 caractères.");
        $this->titre = $titre;
        return $this;
    }

    /**
     * Get the value of texte
     *
     * @return string
     */
    public function getTexte(): string
    {
        return $this->texte;
    }

    /**
     * Set the value of texte
     *
     * @param string $texte
     *
     * @return self
     */
    public function setTexte(string $texte): self
    {
        $texte = trim($texte);
        if (strlen($texte) > 65535 || empty($texte))
            throw new Exception("Le texte '$texte' doit être >= 1 ET <= 65535.");
        $this->texte = $texte;
        return $this;
    }

    
    /**
     * Get the value of publie
     *
     * @return bool
     */
    public function getlocation(): bool
    {
        return $this->location;
    }

    /**
     * Set the value of publie
     *
     * @param bool $publie
     *
     * @return self
     */
    public function setPublie(bool $location): self
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get the value of auteur
     *
     * @return Utilisateur
     */
    public function getAuteur(): autheurs
    {
        return $this->auteur;
    }

    /**
     * Set the value of auteur
     *
     * @param Utilisateur $auteur
     *
     * @return self
     */
    public function setAuteur(autheurs $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

   

    /**
     * Get the value of resumer
     *
     * @return string
     */
    public function getResumer(): string
    {
        return $this->resumer;
    }

    /**
     * Set the value of resumer
     *
     * @param string $resumer
     *
     * @return self
     */
    public function setResumer(string $resumer): self
    {
        $this->resumer = $resumer;

        return $this;
    }

    /**
     * Get the value of idnum
     *
     * @return int
     */
    public function getIdnum(): int
    {
        return $this->idnum;
    }

    /**
     * Set the value of idnum
     *
     * @param int $idnum
     *
     * @return self
     */
    public function setIdnum(int $idnum): self
    {
        $this->idnum = $idnum;

        return $this;
    }
}