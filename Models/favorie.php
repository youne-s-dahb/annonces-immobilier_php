 <?php 

 class favorie{
   //attributs dyal diagramme
    private int $id_favorie;
    private int $id_user; // Clé étrangère pour lier à l'utilisateur
    private int $id_annonce; // Clé étrangère pour lier à l'annonce
    private string $date_ajout;

  //constructeur
    public function __construct(int $id_favorie, int $id_user, int $id_annonce, string $date_ajout)
    {
      $this->id_favorie = $id_favorie;  //$this fait référence à l'objet que on est en train de créer
      $this->id_user = $id_user;
      $this->id_annonce = $id_annonce;
      $this->date_ajout = $date_ajout;
    }

    // Méthodes métier
    public function ajouter_favorie(): bool { // methode khas tkun booleen true ila sd9et"si" false ila la "sinon"
        return []; 
    }

    public function consulter_list_favorie(int $id_user): array {
        //array : C'est une déclaration de type de retour
        return [];
    }

    public function supprimer_favorie(int $id_favorie): bool {
        // pour supprimer un favori
        return true;
    }

    // Getters et Setters (nécessaires pour accéder aux propriétés privées)
    public function getIdFavorie(): int { return $this->id_favorie; }
 }
 ?>