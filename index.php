<?php
require_once 'autoloader.php';

const NOM_BD = "LIVRE";
const ADRESSE_HOTE_BD = "localhost";
const NOM_UTILISATEUR_BD = "root";
const MDP_BD = "";

try
{
    $config = new ModelRepositoryConfig(NOM_BD, ADRESSE_HOTE_BD, NOM_UTILISATEUR_BD, MDP_BD); 
    
    $router = new RouteManager('ControllerDefault', $config);
    $router->execute();
} 
catch (Exception $ex)
{
    // Idéalement, nous devrions indiquer un message "neutre et non alarmant" à l'utilisateur.
    // En même temps, nous devrions envoyer un courriel à l'administrateur du site web pour 
    // lui indiquer qu'une erreur importante est survenue. Également, nous pourrions écrire 
    // l'erreur dans un log
    echo 'Une erreur est survenue : ' . $ex->getMessage();
}