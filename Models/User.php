<?php

    class User{
        private $db;
        public function __construct($cnx)
        {
            $this->db = $cnx;
        }

        //Valdation email:

        public function validEmail($email){
            $patternEmail = "/^[A-Za-z0-9+_.-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/";
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
            if(!preg_match('/[a-z]/', $password)){
                $errors[] = "Le mot de passe doit contenir minimum une lettre miniscule!!";
            }
            if(!preg_match('/[A-Z]/', $password)){
                $errors[] = "Le mot de passe doit contenir minimum une lettre majuscule!!";
            }
            if(!preg_match('/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{8,}/', $password)){
                $errors[] = "Le mot de passe doit contenir au moin un caractère speciale(!@#$%^&*...)";
            }

            if(!empty($errors)){
                return['valid'=>false, 'errors'=>$errors];
            }
            return ['valid'=> true];
        }

        //-----------------------------------------------
        //validation nom et prenom

        public function validName($name){
            $name = trim($name);
            $taille = strlen($name);
            $errors =[];

            if($taille < 3){
                $errors[] = "Le nom/prénom doit contenir minimum 3 caractères!";
            }
            if($taille > 40){
                $errors[] = "Le nom/prénom doit contenir maximum 40 caractères!";
            }
            if(!preg_match('/^[a-zA-ZÀ-ÿ\s\-\']+$/', $name)){  //lettres + espaces
                $errors[] = "Caractères invalide!(Lettres et espaces uniquement)";

            }

            if(!empty($errors)){
                return['valid' => false, 'errors'=>$errors];
            }
            
            return ['valid' => true];
                        
        }

        //-------------------------------------------------
        //validation telephone

        public function validTele($telephone){
            $telephone = trim($telephone);

            $pattern = '/^^[0][5-7][0-9]{8}$/';

            if(!preg_match($pattern, $telephone)){
                return['valid'=>false, 'message'=>"Le numéro de téléphone doit commencer par  05, 06 ou 07 et contenir exactement 10 chiffres!!"];
            
            }
            return ['valid'=>true];

        }

        //-------------------------------------------------
        //s'inscrire

        public function register($nom, $prenom, $email, $password, $telephone){
            try{
                //valider les champs
                $validNom = $this->validName($nom);
                $validPre = $this->validName($prenom);
                $validEmail = $this->validEmail($email);
                $validPhone = $this->validTele($telephone);
                $validPassword = $this->validPass($password);

                $errors = [];

                if(!$validNom['valid']){
                    $errors[] = 'Nom: ' . $validNom['message'];
                }
                if (!$validPre['valid']){
                    $errors[] = 'Prénom: ' . $validPre['message'];
                } 
                if (!$validEmail['valid']){
                    $errors[] = $validEmail['message'];
                } 
                if (!$validPhone['valid']){
                    $errors[] = 'Téléphone: ' . $validPhone['message'];
                } 
                if (!$validPassword['valid']) {
                    foreach ($validPassword['errors'] as $error) {
                        $errors[] = $error;
                    }
                }

                if($this->emailExist($email)){
                    $errors[] = "Email exist deja!";
                }
                if(!empty($errors)){
                    return['valid' => false, 'errors'=>$errors];
                }
                
                //hash password
                $hashedPass = password_hash($password, PASSWORD_BCRYPT); //  password_hash(): fonction li katdir hashing sécurisé, PASSWORD_BCRYPT: protection contre rute force 

                //add user
                $stmt = $this->db->prepare("INSERT INTO user (Nom, Prenom, Gmail, Pasword, Telephone) VALUES (?,?,?,?,?)");

                //remplacer ? par les valeurs
                $stmt->execute([
                    trim($nom),
                    trim($prenom),
                    trim($email),
                    $hashedPass,
                    trim($telephone)
                ]);
                
                return ['sucess'=>true, 'message'=>'Inscription réussite'];

            }catch(PDOException $e){
                return['succes'=>false, 'message'=>"Erreur lors de l'inscription. Veuillez réessayer plus tard"];  //$e.->getMessage()
            }
        }

        //login

        public function login($email, $password){
            try{
                $stmt = $this->db->prepare("SELECT * FROM user where Gmail = ?");
                $stmt->execute([trim($email)]);

                $user = $stmt->fetch(PDO::FETCH_ASSOC); //katjib ligne whda mn DB en tant que tableau associatif

                if(!$user || !password_verify($password, $user['Password'])){  //password_verify: compare entre pass li dokhlo user w li kayn f DB
                    return ['success'=> false, 'message'=> "Email ou mot de passe incorrect!"];
                }

                //kolchi s7i7: return infos user

                return ['sucess'=>true, 'user'=> [
                    'id_user'=>$user['id_user'],
                    'nom'=>$user['nom'],
                    'prenom'=>$user['prenom'],
                    'email'=>$user['email'],
                    'role'=>$user['role']
                ]];

            }catch(PDOException $e){
                return ['success'=>false, 'message'=>"Erreur ors de la conexion.Veuillez réessayer plus tard!"];
            }
        }

        //gestion users

        //recuperer user pas id et les retun sous forme de array

        public function getUserById($id){
            try{

                $stmt = $this->db->prepare("SELECT * FROM user WHERE id_user = ?");
                $stmt->execute([$id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            }catch(PDOException $e){
                return null;
            }
        }

        //list de tous les users

        public function listUsers(){
            try{
                $stmt = $this->db->prepare("SELECT * FROM user ORDER BY id_user DESC"); //DESC: tertib mn kbir l sghir
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result;
            }catch(PDOException $e){
                return [];
            }
        }

        //count users

        public function countUser(){
            try{
                $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM user");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                return $result['total'];

            }catch(PDOException $e){
                return 0;
            }
        }
        
    }

?>