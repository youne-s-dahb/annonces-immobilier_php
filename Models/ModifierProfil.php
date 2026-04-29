/**
 * Modifie le profil de l'utilisateur
 * Utilise les méthodes de validation existantes: validName, validEmail, validTele
 */
public function modifierProfil($id, $nom, $prenom, $email, $telephone) {
    try {
        $validNom = $this->validName($nom);
        $validPre = $this->validName($prenom);
        $validEmail = $this->validEmail($email);
        $validPhone = $this->validTele($telephone);

        // Vérifier que tous les champs sont valides
        if (!$validNom['valid'] || !$validPre['valid'] || !$validEmail['valid'] || !$validPhone['valid']) {
            return ['success' => false, 'message' => "Données non valide!"];
        }

        $stmt = $this->db->prepare("UPDATE user SET Nom = ?, Prenom = ?, Gmail = ?, Telephone = ? WHERE id_user = ?");

        $stmt->execute([
            trim($nom),
            trim($prenom),
            trim($email),
            trim($telephone),
            $id
        ]);

        return ['success' => true, 'message' => "✅ Profil mis a jour avec succés"];

    } catch (PDOException $e) {
        return ['success' => false, 'message' => "Erreur lors de la modification. Veuillez réessayer plus tard!!"];
    }
}