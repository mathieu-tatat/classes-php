<?php
session_start();

    class user{
        private $id;
        public $login;
        public $password;
        public $email;
        public $firstname;
        public $lastname;
        public $bdd;
        public $users;

        public function  __construct(){ // Est appelé automatiquement lors de l’initialisation de votre objet. Initialise les différents attributs de votre objet.
            //connexion a database
            $servername = "localhost";
            $username = "root";
            $password = "root";
            $dbname = "classes";
            $this->bdd =  mysqli_connect($servername, $username, $password, $dbname);
            $sql = mysqli_query($this->bdd,"SELECT * FROM `utilisateurs`");
            $this->users =  mysqli_fetch_all($sql);
            
        }
        
        public function register($login, $password, $email, $firstname, $lastname){// Créée l’utilisateur en BDD. Retourne un tableau contenant l’ensembles des informations de ce même utilisateur. 
            
            //verrification que le login n'est pas deja utilisé
            foreach($this->users as $user){ 
                
                if ($login == $user[1]){
                    $taken = true;
                    echo "invalid login <br/>";
                    
                }
            }
            
            //si login dispo alors creation de requete pour enregistrer le profil
                if ($login != $user[1] && $taken == false){
                $req = mysqli_query ($this->bdd,"INSERT INTO utilisateurs(login, password, email, firstname, lastname) VALUES ('$login','$password','$email','$firstname','$lastname')");
                
                //affiche tableau des infos que user vient de creer 
                echo"
                            <table>
                        <tr>
                            <th>Login</th>
                            <th>Password</th>
                            <th>Email</th>
                            <th>Prenom</th>
                            <th>Nom</th>
                        </tr>
                        <tr>
                            <th>$login</th>
                            <th>$password</th>
                            <th>$email</th>
                            <th>$firstname</th>
                            <th>$lastname</th>
                        </tr>
                    </table>";   
            }
              
        }

        public function connect($login, $password){// connecte l’utilisateur, et donne aux attributs de la classe les valeurs correspondantes à celles de l’utilisateur connecté. 
                //verrification que le login est password sont rrenseignés et correspondes a un profil pour le connecter
                if($login != NULL && $password != NULL){
                    $exec_requete = mysqli_query($this->bdd,"SELECT * FROM utilisateurs WHERE login = '$login' && password = '$password'");
                    $reponse = mysqli_fetch_assoc($exec_requete);
                    $count = count($reponse);
                    }
                    if ($count == 0 ){
                        echo "identifiants incorrects <br/>";
                    } 
                    else{
                    $_SESSION['users'] = $reponse;
                    echo "Bravo $login vous etes connecté<br/>";
                    $this->login = $login;
                    $this->password = $password;
                    $this->email = $reponse['email'];
                    $this->firstname = $reponse['firstname'];
                    $this->lastname = $reponse['lastname'];
                }
        
        }

        public function disconnect(){//Déconnecte l’utilisateur
            //detriut la session est affiche la deconnexion
            session_destroy();
            echo"deconnecté <br/>";
           
            
        }

        public function delete(){//Supprime ET déconnecte un user
          
            //requete qui supprime le profil connécté dans la db
            $req = mysqli_query($this->bdd,"DELETE FROM utilisateurs WHERE login = '$this->login'");
            session_destroy();
            echo"profil supprimé <br/>";
        }

        public function update ($login, $password, $email, $firstname, $lastname){//Met à jour les attributs de l’objet, et modifie les informations en BDD
           
            $this->id = $_SESSION['users']['id'] ;
            $this->login = $login;
            $this->password = $password;
            $this->email = $email;
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            //requete de mise a jour des infos de l'utilisateur
            $req = mysqli_query($this->bdd,"UPDATE utilisateurs SET login = '$this->login', password = '$this->password', email = '$this->email' , firstname = '$this->firstname', lastname = '$this->lastname' WHERE id = '$this->id'" );
            echo"update complete <br/>";
    
        }

        public function isConnected (){//retourne un booleen permettant de savoir si un utilisateur est connecté ou non
            
            if(isset($_SESSION['connected'])){
            echo"vous etes bien connecté $this->login";}
            else echo "error";
            

        }

        public function getAllInfos(){//Retourne un tableau contenant l’ensemble des informations de l’utilisateur
            
            //tableau des infos de l'utilisateur
            echo"
            <table>
        <tr>
            <th>Login</th>
            <th>Password</th>
            <th>Email</th>
            <th>Prenom</th>
            <th>Nom</th>
        </tr>
        <tr>
            <th>$this->login</th>
            <th>$this->password</th>
            <th>$this->email</th>
            <th>$this->firstname</th>
            <th>$this->lastname</th>
        </tr>
    </table>";   
        }

        public function getLogin(){//retourne le login de l’utilisateur
           echo "$this->login <br/>";
        }

        public function getEmail (){//retourne l’email
            echo "$this->email<br/>";
        }

        public function getFirstname (){//retourne le firstname
            echo "$this->firstname<br/>";
        }

        public function getLastname (){//retourne le lastname
            echo "$this->lastname<br/>";
        }

    }
    
//creation d'un nouvel objet
$user1 = new User();
$user2 = new User();

// fonction enregister  profil
// $user1->register("z","z","z","z","z");
$user2->register("t","t","t","t","t");

// fonction connexion  profil
$user1->connect("mat","abc");

// fonction deconnexion  profil
//$user1->disconnect();

// fonction supprimer  profil
//$user1->delete();

// fonction mise a jour  profil
// $user1->update("o","o","o","o","o");

// fonction connexion est ok ?
$user1->isConnected();

// fonction tableau infos utilisateur
$user1->getAllInfos();

// fonction affichage login utilisateur
$user1->getLogin();

// fonction affichage login utilisateur
$user1->getEmail();

// fonction affichage login utilisateur
$user1->getFirstname();

// fonction affichage login utilisateur
$user1->getLastname();


?>
       
