<?php
include (__DIR__."/../db.php");

class annonces{
    private $db;
    public function __construct($db) {
        $this->db = $db;
    }
    
    public function getDb() {
        return $this->db;
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
            $sql="SELECT * FROM categorie";
            $stmt=$this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function allVille(){
        $sql="SELECT * FROM ville";
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
    public function publier_annonce($data){//heya post 
        $date=new DateTime("now", new DateTimeZone("Africa/Casablanca"));
        $date_pub=$date->format("Y-m-d H:i:s");
        $titre       = $data["titre"] ?? "Sans titre"; 
        $type        = $data["type"] ?? "";
        $telephone   = $data["telephone"] ?? "";
        $description = $data["description"] ?? "";
        $prix        = $data["prix"] ?? 0;
        $id_categorie = $data["categorie"] ?? null;
        $id_ville     = $data["ville"] ?? null;
       
        $sql="INSERT INTO `annonce` (Titre,Description,Prix,Telephone,Type,Date_Publication,id_user,id_ville,id_categorie)
              VALUES (?,?,?,?,?,?,?,?,?)
            ";
        $stmt=$this->db->prepare($sql);
        $id_user = $data["id_user"] ?? null;

        if (!$id_user) {
            return false;
        }

        $stmt->execute([
                $titre,$description,$prix,$telephone,$type,$date_pub,$id_user,$id_ville,$id_categorie
        ]);     
        return $this->db->lastInsertId();// return id dyal had annonces 
    }
    public function publier_image($url,$id_annonce){
        $sql ="INSERT INTO `image` (url_image,id_annonce) VALUES (?,?)";
        $stmt=$this->db->prepare($sql);
        return $stmt->execute([$url,$id_annonce]);
    }

    public function consulterParUser($id_user){
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
                LEFT JOIN image ON annonce.id_annonce = image.id_annonce
                WHERE annonce.id_user = ?
                GROUP BY annonce.id_annonce
                ORDER BY annonce.Date_Publication DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function modifier_annonces($id, $titre, $description, $prix, $type, $id_ville, $id_categorie){
        try{
            if (!$id_ville || !$id_categorie) {
                return ['success' => false, 'message' => 'Ville ou categorie non trouvee'];
            }
            
            $sql ="UPDATE `annonce` SET Titre=?, Description=?, Prix=?, Type=?, id_ville=?, id_categorie=? WHERE id_annonce=?";
          $stmt=$this->db->prepare($sql);
          $result = $stmt->execute([$titre, $description, (float)$prix, $type, (int)$id_ville, (int)$id_categorie, (int)$id]);
          
          if ($result && $stmt->rowCount() > 0) {
            return ['success' => true, 'message' => 'Annonce modifiee avec succes'];
        } else {
            return ['success' => false, 'message' => 'Erreur ou annonce non trouvee'];
        }
        }catch (PDOException $e) {
                return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
        }
          

    }
    public function Supprimer_annonce($id_annonce, $id_user = null){
          try {
              if (!$id_annonce) {
                  return ['success' => false, 'message' => 'Annonce introuvable'];
              }

              if ($id_user) {
                  $sql = "DELETE FROM `annonce` WHERE id_annonce = ? AND id_user = ?";
                  $stmt = $this->db->prepare($sql);
                  $result = $stmt->execute([(int)$id_annonce, (int)$id_user]);
              } else {
                  $sql = "DELETE FROM `annonce` WHERE id_annonce = ?";
                  $stmt = $this->db->prepare($sql);
                  $result = $stmt->execute([(int)$id_annonce]);
              }

              if ($result && $stmt->rowCount() > 0) {
                  return ['success' => true, 'message' => 'Annonce supprimée avec succès'];
              }

              return ['success' => false, 'message' => 'Annonce non trouvée ou suppression refusée'];
          } catch (PDOException $e) {
              return ['success' => false, 'message' => 'Erreur: ' . $e->getMessage()];
          }
    }
   

}  

?>
