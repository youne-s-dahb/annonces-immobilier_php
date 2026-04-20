<?php
include (__DIR__."/../db.php");

class annonces{
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function consulter(){
        $sql ="SELECT annonce.* , user.Nom,Prenom ,ville.nom_ville,categorie.Categorie 
                FROM annonce 
                INNER JOIN user ON annonce.id_user = user.id_user
                INNER JOIN ville ON annonce.id_ville = ville.id_ville
                INNER JOIN categorie ON annonce.id_categorie=categorie.id_categorie";
        $stmt=$this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}

?>
