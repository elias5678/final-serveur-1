<?php

class ArticleRepository extends ModelRepository
{
    private CommentaireRepository $commentaireRepository;
    private UtilisateurRepository $utilisateurRepository;


    public function __construct(
        ModelRepositoryConfig $config,
        CommentaireRepository $commentaireRepository,
        UtilisateurRepository $utilisateurRepository
    )
    {
        parent::__construct($config);
        $this->commentaireRepository = $commentaireRepository;
        $this->utilisateurRepository = $utilisateurRepository;
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
    public function selectAll($utilisateur_id = null) : Array
    {
        $s_requete = "SELECT * FROM article ";
        if ($utilisateur_id != null)
            $s_requete = $s_requete . "WHERE utilisateur_id=:utilisateur_id";

        $requete = $this->connexion->prepare($s_requete);
        $requete->bindValue(":utilisateur_id", $utilisateur_id);
        $requete->execute();

        $articles = array();
        while ($record = $requete->fetch())
        {
            $article = $this->constructArticleFromRecord($record);
            if ($article != null)
                $articles[] = $article;
        }

        return $articles;
    }


    /**
     * Permet de sélectionner un article ainsi que ses commentaires.
     * 
     * @param string $id L'identifiant de l'article à récupérer.
     * @return Article L'article si trouvé, null dans le cas contraire.
     */
    public function select($id) : ?Article
    {
        $article = null;

        $requete = $this->connexion->prepare("SELECT * FROM article WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        if ($record = $requete->fetch())
        {
            $article = $this->constructArticleFromRecord($record);
            if ($article != null)
            {
                $article->setCommentaires($this->commentaireRepository->selectAll($article->getId()));
                $articles[] = $article;
            }
        }

        return $article;
    }


    /**
     * Permet d'insérer un article en BD. 
     * Les commentaires ne sont pas insérés.
     * 
     * @param Article $article L'article à insérer en BD.
     * @return int Le nouvel id de l'article. 0 si l'insertion ne se produit pas. 
     */
    public function insert(Article $article) : int
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "INSERT INTO article(titre, texte, dateCreation, dateModification, publie, utilisateur_id) " .
                "VALUE(:titre, :texte, CURDATE(), NOW(), :publie, :utilisateur_id)"
        );

        $requete->bindValue(":titre", $article->getTitre());
        $requete->bindValue(":texte", $article->getTexte());
        $requete->bindValue(":publie", $article->getPublie(), PDO::PARAM_BOOL);
        $requete->bindValue(":utilisateur_id", $article->getAuteur()->getId());

        $requete->execute();

        $id = $this->connexion->lastInsertId();

        $article->setId($id);
        $this->updateTagsArticle($article);

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
    public function update(Article $article) : bool
    {
        $this->connexion->beginTransaction();

        $requete = $this->connexion->prepare(
            "UPDATE article SET titre=:titre, texte=:texte, dateModification=NOW(), publie=:publie WHERE id=:id"
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

        $requete = $this->connexion->prepare("DELETE FROM article WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $succes = $requete->rowCount() != 0;

        $this->connexion->commit();

        return $succes;
    }


    private function constructArticleFromRecord($record) : ?Article
    {
        $article = null;
        $utilisateur = $this->utilisateurRepository->select($record['utilisateur_id']);
        if ($utilisateur != null)
        {
            $article = new Article(
                $record['titre'],
                $record['texte'],
                $utilisateur,
                new DateTime($record['dateCreation']),
                new DateTime($record['dateModification']),
                $record['publie'],
                $record['id']
            );

            $this->selectTagsArticle($article);
        }

        return $article;
    }


    private function selectTagsArticle($article)
    {
        $requete = $this->connexion->prepare(
            "SELECT * FROM tag_article " .
                "INNER JOIN tag ON tag_id = id " .
                "WHERE article_id=:article_id"
        );
        $requete->bindValue(":article_id", $article->getId());
        $requete->execute();

        while ($record = $requete->fetch())
        {
            $article->addTag(new Tag($record['nom'], $record['id']));
        }
    }


    /**
     * Permet de mettre à jour en BD les tag_article d'un article.
     * La transaction doit être ouverte et "non commit". Après la fonction,
     * la transaction n'est pas commité. L'appelant est responsable de commit.
     * 
     * @param Article $article L'article pour lequel mettre à jour les tag_article.
     */
    private function updateTagsArticle(Article $article)
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
}
