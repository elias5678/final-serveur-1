<?php

/**
 * Cette classe permet de générer une page HTML à partir d'un template tout en pouvant y injecter des variables.
 * 
 * Utilisation :
 * 1) Construire une instance en spécifiant le template html et optionnellement les champs/valeurs présents dans la page html.
 * 2) Appeler render();
 * 
 * La méthode assign() permet en option d'ajouter des champs/valeurs  après la construction de la vue (avant d'appeler render()).
 * 
 * Inspiré partiellement de : http://www.sitepoint.com/flexible-view-manipulation-1/
 */
class ViewCreator
{
    /** Le template html */
    private string $template;

    /** Les champs du template */
    private array $fields;

    /**
     * Constructeur. 
     * @param string $template Le chemin du template html à utiliser
     * @param array $fields Les champs présents dans le template
     */
    function __construct(string $template, array $fields = array())
    {
        $this->template = $template;
        $this->fields = $fields;
    }

    /**
     * Méthode permettant d'ajouter des champs après la construction de la vue.
     * @param type $field Le nom du champ
     * @param type $value La valeur du champ
     */
    function assign($field, $value)
    {
        $this->fields[$field] = $value;
    }

    /**
     * Méthode permettant d'ajouter plusieurs champs
     * après la construction de la vue.
     * 
     * @param array $varsToAssign Le tableau de variables à assigner. 
     */
    function assignSeveral(array $varsToAssign)
    {
        $this->fields = array_merge($this->fields, $varsToAssign);
    }

    /**
     * Méthode permettant de générer la vue.
     * @return string Le contenu html de la vue.
     */
    function render()
    {
        // extract() permet de créer les variables dont les noms
        // sont les index du tableau en leur affectant la valeur
        // associée. En d'autres mots, c'est comme si les variables
        // étaient explicitement déclarées à l'extérieur du tableau.
        extract($this->fields);

        // ob_start() permet d'ouvrir un buffer (tampon) de sortie. Ce qui est 
        // mis dans le buffer de sortie (html, echo, etc.) est ce qui sera envoyé 
        // au navigateur web.
        ob_start();

        // Le faire de faire un require du template permet de l'inclure dans le
        // buffer de sortie.
        require $this->template;

        // ob_get_clean() permet d'obtenir le contenu du buffer de sortie sous 
        // forme de chaîne de caractères. Ensuite, le buffer est effacé et fermé.
        return ob_get_clean();
    }
}
