<?php

    class User{
        private $db;
        public function __construct($cnx)
        {
            $this->db = $cnx;
        }

        //Valdation email:

        public function validEmail($email){
            $patternEmail = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
            if(!preg_match($patternEmail, $email)){
                return ['valid'=>false, 'message'=>"Format email invalide!"];
            }
            return['valid'=>true];
        }
        //si email deja kayn:
        public function emailExist($email){
            $stmt = $this->db->prepare("SELECT id_user FROM user WHERE Gmail = ?");
            $stmt->execute([$email]);
            return $stmt->rowCount() > 0; //rowCount: return le nombre des lignes trouvées/affectés par la requete sql

        }

        //-------------------------------------------

        //validation password

        public function validPass($password){
            $errors = [];
            if(strlen($password) < 8){
                $errors[] = "Minimum 8 caractères!!";
            }
            if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()_+\-=\[\]{};\':"\\|,.<>\/?]).+$/', $password)) {
                $errors[] = 'Le mot de passe doit contenir : minuscule, majuscule, chiffre et caractère spécial';
            }
        }
        
    }

?>