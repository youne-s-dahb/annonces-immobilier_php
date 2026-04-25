<?php
include (__DIR__."/../db.php");

class annonces{
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }

    public function consulter(){
        $sql = "SELECT 
                annonce.*, 
                user.Nom, 
                user.Prenom, 
                ville.nom_ville, 
                categorie.Categorie, 
                GROUP_CONCAT(image.url_image ORDER BY image.id_image SEPARATOR ',') AS url_image
            FROM annonce 
            INNER JOIN user ON annonce.id_user = user.id_user
            INNER JOIN ville ON annonce.id_ville = ville.id_ville
            INNER JOIN categorie ON annonce.id_categorie = categorie.id_categorie
            INNER JOIN image ON annonce.id_annonce = image.id_annonce
            GROUP BY annonce.id_annonce";
        $stmt=$this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allCategorie(){
            $sql="SELECT Categorie FROM categorie";
            $stmt=$this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function allVille(){
        $sql="SELECT nom_ville FROM ville";
        $stmt=$this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function filtrer_Prix($prix_min,$prix_max){
        if(isset($prix_min)&&isset($prix_max)){
            if($prix_max>$prix_min){
                $sql= "SELECT     
                                annonce.*, 
                                user.Nom, 
                                user.Prenom, 
                                ville.nom_ville, 
                                categorie.Categorie, 
                                GROUP_CONCAT(image.url_image ORDER BY image.id_image SEPARATOR ',') AS tswira -- Alias
                            FROM annonce 
                            INNER JOIN user ON annonce.id_user = user.id_user
                            INNER JOIN ville ON annonce.id_ville = ville.id_ville
                            INNER JOIN categorie ON annonce.id_categorie = categorie.id_categorie
                            INNER JOIN image ON annonce.id_annonce = image.id_annonce
                            WHERE annonce.prix BETWEEN ? AND ?
                            GROUP BY annonce.id_annonce";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([$prix_min, $prix_max]);
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }else{?>
                    <script>
                        alert("le prix max doit etre supp a prix min ");
                    </script>
            <?php
            }
        }
    }


}

?>
