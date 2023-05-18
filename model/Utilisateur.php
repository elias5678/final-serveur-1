<?php

class Utilisateur
{
    private int $id;
    private int $type;
    private string $username;
    private string $courriel;
    private string $hash;


    public function __construct(string $username, string $courriel, string $hash, int $type = 2, int $id = 0)
    {
        $this->setId($id);
        $this->setType($type);
        $this->setUsername($username);
        $this->setCourriel($courriel);
        $this->setHash($hash);
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
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @param int $type
     *
     * @return self
     */
    public function setType(int $type): self
    {
        if ($type != 1 && $type != 2)
            throw new Exception("Le type ($type) d'un utilisatoire doit être 1 ou 2.");
        $this->type = $type;
        return $this;
    }

    /**
     * Get the value of username
     *
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param string $username
     *
     * @return self
     */
    public function setUsername(string $username): self
    {
        $username = trim($username);
        if (strlen($username) > 50 || empty($username))
            throw new Exception("Le username '$username' doit être >= 1 ET <= 50.");
        $this->username = $username;
        return $this;
    }

    /**
     * Get the value of courriel
     *
     * @return string
     */
    public function getCourriel(): string
    {
        return $this->courriel;
    }

    /**
     * Set the value of courriel
     *
     * @param string $courriel
     *
     * @return self
     */
    public function setCourriel(string $courriel): self
    {
        $courriel = trim($courriel);
        if (!filter_var($courriel, FILTER_VALIDATE_EMAIL) || strlen($courriel) > 255)
            throw new Exception("Le courriel '$courriel' n'est pas de format valide.");
        $this->courriel = $courriel;
        return $this;
    }

    /**
     * Get the value of hash
     *
     * @return string
     */
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * Set the value of hash
     *
     * @param string $hash
     *
     * @return self
     */
    public function setHash(string $hash): self
    {
        $hash = trim($hash);
        if (strlen($hash) != 64)
            throw new Exception("Le hash '$hash' n'est pas de longueur 64.");
        $this->hash = $hash;
        return $this;
    }
}
