<?php

class livreRepository extends ModelRepository
{
    
    private autheurRepository $autheurRepository;


    public function __construct(
        ModelRepositoryConfig $config,
     
        autheurRepository $autheurRepository
    )
    {
        parent::__construct($config);
       
        $this->autheurRepository = $autheurRepository;
    }


    /**
     * Permet de sélectionner tous les articles en BD ou
     * bien seulement ceux d'un utilisateur en particulier.
     * Les commentaires ne sont pas récupérés.
     * 
     * @param string $utilisateur_id Si fourni, correspond à l'id de l'utilisateur 
     *                               pour lequel sélectionner l'ensemble des articles 
     *                               dont il est l'auteur.
     * @return Article[] Un tableau d'articles.
     */
    public function selectAll($idAuteurs = null) : Array
    {
        $s_requete = "SELECT * FROM livres ";
        if ($idAuteurs != null)
            $s_requete = $s_requete . "WHERE idAuteurs=:idAuteurs";

        $requete = $this->connexion->prepare($s_requete);
        $requete->bindValue(":idAuteurs", $idAuteurs);
        $requete->execute();

        $livress = array();
        while ($record = $requete->fetch())
        {
            $livres = $this->constructArticleFromRecord($record);
            if ($livres != null)
                $livress[] = $livres;
        }

        return $livress;
    }


    /**
     * Permet de sélectionner un article ainsi que ses commentaires.
     * 
     * @param string $id L'identifiant de l'article à récupérer.
     * @return Article L'article si trouvé, null dans le cas contraire.
     */
    public function select($id) : ?livres
    {
        $livres = null;

        $requete = $this->connexion->prepare("SELECT * FROM livres WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        if ($record = $requete->fetch())
        {
            $livres = $this->constructlivresFromRecord($record);
            if ($livres != null)
            {
               
                $livress[] = $livres;
            }
        }

        return $livres;
    }


    /**
     * Permet d'insérer un article en BD. 
     * Les commentaires ne sont pas insérés.
     * 
     * @param Article $article L'article à insérer en BD.
     * @return int Le nouvel id de l'article. 0 si l'insertion ne se produit pas. 
     */
    public function insert(livres $livres) : int
    {
        $this->connexion->beginTransaction();
//             `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
//   `titre` varchar(50) NOT NULL,
//   `location` boolean not null,
//   `idnuméro` int(10) unsigned NOT NULL AUTO_INCREMENT,
//   `resumer` text NOT NULL,
//   `idAuteurs` int(10) unsigned NOT NULL,

        $requete = $this->connexion->prepare(
            "INSERT INTO livres(titre, location, idnuméro, resumer, idautheurs) " .
                "VALUE(:titre,  :location, :idnuméro,:resumer :idautheur)"
        );

        $requete->bindValue(":titre", $livres->getTitre());
        $requete->bindValue(":idnuméro", $livres->getIdnuméro());
        $requete->bindValue(":location", $livres->getLocation(), PDO::PARAM_BOOL);
        $requete->bindValue(":idauteurs", $livres>getAuteur()->getId());
        $requete->bindValue(":resumer", $livres->getResumer());
        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $livres->setId($id);
        $this->updateTagsLivre($livres);

        $this->connexion->commit();

        return $id;
    }


    /**
     * Permet de mettre à jour un article en BD. 
     * Les commentaires ne sont pas mis à jour.
     * 
     * @param Article $article L'article à mettre à jour en BD.
     * @return bool Vrai si la mise à jour a été effectuée. Faux dans le cas contraire.
     */

    public function update(livres $livres) : bool
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "UPDATE livres SET titre=:titre, texte=:texte, dateModification=NOW(), publie=:publie WHERE id=:id"
        );

        $requete->bindValue(":titre", $article->getTitre());
        $requete->bindValue(":texte", $article->getTexte());
        $requete->bindValue(":publie", $article->getPublie(), PDO::PARAM_BOOL);
        $requete->bindValue(":id", $article->getId());

        $requete->execute();

        $succes = $requete->rowCount() != 0;
        $this->updateTagsArticle($article);

        $this->connexion->commit();

        return $succes;
    }


    /**
     * Permet de supprimer un article en BD. 
     * Les commentaires associés à l'article 
     * sont conséquemment supprimés en cascade.
     * 
     * @param string $id L'id de l'article à supprimer en BD.
     * @return bool Vrai si la suppression a été effectuée. Faux dans le cas contraire.
     */
    public function delete($id) : bool
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare("DELETE FROM livres WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $succes = $requete->rowCount() != 0;

        $this->connexion->commit();

        return $succes;
    }


    private function constructlivresFromRecord($record) : ?livre
    {
        $livre= null;
        $autheur = $this->autheurRepository->select($record['idautheurs']);
        if ($autheur != null)
        {

//             `id` int(10) unsigned NOT NULL AUTO_INCREMENT, 
//   `titre` varchar(50) NOT NULL,
//   `location` boolean not null,
//   `idnuméro` int(10) unsigned NOT NULL AUTO_INCREMENT,
//   `resumer` text NOT NULL,
//   `idAuteurs` int(10) unsigned NOT NULL,

            $livre = new Article(
                $record['titre'],
                $record['location'],
          
                $record['idnuméro'],
                $record['idauteurs'],
                $record['resumer'],
                $record['id']
            );

            $this->selectTagslivres($livre);
        }

        return $livre;
    }


    

    /**
     * Permet de mettre à jour en BD les tag_article d'un article.
     * La transaction doit être ouverte et "non commit". Après la fonction,
     * la transaction n'est pas commité. L'appelant est responsable de commit.
     * 
     * @param Article $article L'article pour lequel mettre à jour les tag_article.
     */
    private function updateTagslivres(livres $livres)
    {
        // Suppression des anciens tag_article
        $requete = $this->connexion->prepare("DELETE FROM tag_article WHERE article_id=:article_id");
        $requete->bindValue(":article_id", $article->getId());
        $requete->execute();

        // Mise à jour avec les nouveaux tag_article
        $requete = $this->connexion->prepare("INSERT INTO tag_article(tag_id, article_id) VALUES(:tag_id, :article_id)");
        foreach ($article->getTags() as $tag)
        {
            $requete->bindValue(":tag_id", $tag->getId());
            $requete->bindValue(":article_id", $article->getId());
            $requete->execute();
        }
    }