<?php

class UtilisateurRepository extends ModelRepository
{

    public function selectAll(): array
    {
        $requete = $this->connexion->prepare("SELECT * FROM utilisateur");
        $requete->execute();

        $utilisateurs = array();
        while ($record = $requete->fetch())
            $utilisateurs[] = $this->constructUtilisateurFromRecord($record);

        return $utilisateurs;
    }


    /**
     * @param string $id L'identifant unique de l'utilisateur à sélectionner.
     * @return Utilisateur L'utilisateur si trouvé, sinon null.
     */
    public function select($id): ?Utilisateur
    {
        $requete = $this->connexion->prepare("SELECT * FROM utilisateur WHERE id=:id");
        $requete->bindValue(":id", $id);
        $requete->execute();

        $utilisateur = null;
        if ($record = $requete->fetch())
            $utilisateur = $this->constructUtilisateurFromRecord($record);

        return $utilisateur;
    }


    private function constructUtilisateurFromRecord($record): ?Utilisateur
    {
        return new Utilisateur(
            $record['username'],
            $record['courriel'],
            $record['hash'],
            $record['type'],
            $record['id']
        );
    }
}
