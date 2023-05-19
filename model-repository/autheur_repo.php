<?php

class autheurRepository extends ModelRepository
{

    public function selectAll(): array
    {
        $requete = $this->connexion->prepare("SELECT * FROM auteurs");
        $requete->execute();

        $auteurs = array();
        while ($record = $requete->fetch())
            $auteurs[] = $this->constructUtilisateurFromRecord($record);

        return $auteurs;
    }


    /**
     * @param string $id L'identifant unique de l'utilisateur à sélectionner.
     * @return Utilisateur L'utilisateur si trouvé, sinon null.
     */
    public function select($id): ?auteurs
    {
        $requete = $this->connexion->prepare("SELECT * FROM auteurs WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $auteurs = null;
        if ($record = $requete->fetch())
            $utilisateur = $this->constructUtilisateurFromRecord($record);

        return $auteurs;
    }


    private function constructUtilisateurFromRecord($record): ?auteurs
    {
        // `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
        // `name` varchar(50) NOT NULL,
        // `distributeur`varchar(100),
        // `nbr_livre` int(10),
        // `note`int(10),

        return new auteurs(
            $record['name'],
            $record['distributeur'],
            $record['nbr_livre'],
            $record['note'],
            $record['id']
        );
    }
}
