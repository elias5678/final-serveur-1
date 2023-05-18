<?php

class Article
{
    private int $id;
    private string $titre;
    private string $texte;
    private ?DateTime $dateCreation;
    private ?DateTime $dateModification;
    private bool $publie;
    private Utilisateur $auteur;
    private array $tags;
    private array $commentaires;


    public function __construct(
        string $titre,
        string $texte,
        Utilisateur $auteur,
        ?DateTime $dateCreation = null,
        ?DateTime $dateModification = null,
        bool $publie = true,
        int $id = 0
    )
    {
        $this->setId($id);
        $this->setTitre($titre);
        $this->setTexte($texte);
        $this->setDateCreation($dateCreation);
        $this->setDateModification($dateModification);
        $this->setPublie($publie);
        $this->setAuteur($auteur);
        $this->tags = array();
        $this->commentaires = array();
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
     * Get the value of dateCreation
     *
     * @return ?DateTime
     */
    public function getDateCreation(): ?DateTime
    {
        return $this->dateCreation;
    }

    /**
     * Set the value of dateCreation
     *
     * @param ?DateTime $dateCreation
     *
     * @return self
     */
    public function setDateCreation(?DateTime $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get the value of dateModification
     *
     * @return ?DateTime
     */
    public function getDateModification(): ?DateTime
    {
        return $this->dateModification;
    }

    /**
     * Set the value of dateModification
     *
     * @param ?DateTime $dateModification
     *
     * @return self
     */
    public function setDateModification(?DateTime $dateModification): self
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get the value of publie
     *
     * @return bool
     */
    public function getPublie(): bool
    {
        return $this->publie;
    }

    /**
     * Set the value of publie
     *
     * @param bool $publie
     *
     * @return self
     */
    public function setPublie(bool $publie): self
    {
        $this->publie = $publie;

        return $this;
    }

    /**
     * Get the value of auteur
     *
     * @return Utilisateur
     */
    public function getAuteur(): Utilisateur
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
    public function setAuteur(Utilisateur $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get the value of tags
     *
     * @return array
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * Set the value of tags
     *
     * @param array $tags
     *
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * Get the value of commentaires
     *
     * @return array
     */
    public function getCommentaires(): array
    {
        return $this->commentaires;
    }

    /**
     * Set the value of commentaires
     *
     * @param array $commentaires
     *
     * @return self
     */
    public function setCommentaires(array $commentaires): self
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    public function addTag(Tag $tag)
    {
        $this->tags[] = $tag;
    }

    public function addCommentaire(Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;
    }
}
