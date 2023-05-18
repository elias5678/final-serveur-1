<?php

class autheurs
{
    private int $id;
    private int $name;
    private string $dustributeur;
    private int $nbr_l;
    private int $note; 

    // `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    // `name` varchar(50) NOT NULL,
    // `distributeur`varchar(100),
    // `nbr_livre` int(10),
    // `note`int(10),
    public function __construct(string $name,string $dustributeur, int $nbr_l = 0 ,int $note,int $id = 0)
    {
        $this->setId($id);
        $this->setname($name);
        $this->setdustributeur($dustributeur);
        $this->setNbrL($nbr_l);
        $this->setNote($note);
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
     * Get the value of type
     *
     * @return int
     */
    public function getname(): int
    {
        return $this->name;
    }

    /**
     * Set the value of type
     *
     * @param int $type
     *
     * @return self
     */
    public function setname(int $name): self
    {
        $name = trim($name);
        if (strlen($name) > 50 || empty($name))
            throw new Exception("Le name '$name' doit être >= 1 ET <= 50.");
        $this->name = $name;
        return $this;
    }

    /**
     * Get the value of username
     *
     * @return string
     */
    public function getdustributeur(): string
    {
        return $this->dustributeur;
    }

    /**
     * Set the value of username
     *
     * @param string $username
     *
     * @return self
     */
    public function setdustributeur(string $dustributeur): self
    {
        $dustributeur = trim($dustributeur);
        if (strlen($dustributeur) > 50 || empty($dustributeur))
            throw new Exception("Le distributeur'$dustributeur' doit être >= 1 ET <= 50.");
        $this->dustributeur = $dustributeur;
        return $this;
    }

    

    /**
     * Get the value of nbr_l
     *
     * @return int
     */
    public function getNbrL(): int
    {
        return $this->nbr_l;
    }

    /**
     * Set the value of nbr_l
     *
     * @param int $nbr_l
     *
     * @return self
     */
    public function setNbrL(int $nbr_l): self
    {
        $this->nbr_l = $nbr_l;

        return $this;
    }

    /**
     * Get the value of note
     *
     * @return int
     */
    public function getNote(): int
    {
        return $this->note;
    }

    /**
     * Set the value of note
     *
     * @param int $note
     *
     * @return self
     */
    public function setNote(int $note): self
    {
        $this->note = $note;

        return $this;
    }
}
