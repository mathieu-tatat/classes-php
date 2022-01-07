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

    function __construct(){// Est appelé automatiquement lors de l’initialisation de votre objet. Initialise les différents attributs de votre objet.
        //connexion a database

        $db = "mysql:host=localhost;dbname=classes;charset=UTF8";
        $this->bdd = new PDO($db, "root", "root");
        $sql = "SELECT * FROM utilisateurs" ;
        $query = $this->bdd->prepare($sql);
        $query -> execute();
        $this -> users = $query->fetchAll();
    
    }

    function register($login,$password,$email,$firstname,$lastname){// Créée l’utilisateur en BDD. Retourne un tableau contenant l’ensembles des informations de ce même utilisateur. 

        //verrification que le login n'est pas deja utilisé
    foreach($this -> users as $user){
        if ($login == $user[1]){
        $taken = true;
        echo "login indisponnible<br/>";
        }
    }
    
    //si login dispo alors creation de requete pour enregistrer le profil
    if ($login != $user[1] && $taken == false){
        $bdd=$this->bdd;
        $sql = "INSERT INTO utilisateurs(login,password,email,firstname,lastname) VALUES (:login, :password, :email, :firstname, :lastname)";
        $prepare = $bdd->prepare($sql);
        $execute = $prepare->execute([':login' => $login , ':password' => $password, ':email' => $email, ':firstname' => $firstname, ':lastname' => $lastname]);
        
        //affiche tableau des infos que user vient de creer 
        echo "
        <table style='text-align:center'>
            <theader>
            <th>login</th>
            <th>password</th>
            <th>email</th>
            <th>firstname</th>
            <th>lastname</th>
            </theader>
            <tbody>
            <td> $login </td>
            <td> $password </td>
            <td> $email </td>
            <td> $firstname </td>
            <td> $lastname </td>
            </tbody>
        </table>
        ";
    }
    
    }

    function connect($login,$password){// connecte l’utilisateur, et donne aux attributs de la classe les valeurs correspondantes à celles de l’utilisateur connecté. 
        
    //verrification que le login est password sont rrenseignés et correspondes a un profil pour le connecter
        foreach($this -> users as $user){
            if ($login == $user[1] && $password == $user[2]){
                $_SESSION["connected"] = $login ;
             
                $this -> login = $login;
                $this -> password = $user[2];
                $this -> email = $user[3];
                $this -> firstname = $user[4];
                $this -> lastname = $user[5];
               
                echo $this -> login . " vous etes connecté </br>";
            }
        }
    }

    function disconnect(){//Déconnecte l’utilisateur
    
    //detriut la session est affiche la deconnexion
    session_destroy();
    echo "déconnecté <br/>";
    }

    function delete(){//Supprime ET déconnecte un user
    // creation de variables qui ont la valeur des attributs de la classe
    $login = $this->login;
    $bdd=$this->bdd;
    
    //requete qui supprime le profil connécté dans la db
    $sql = "DELETE FROM `utilisateurs` WHERE `login` = :login";
    $prepare = $bdd->prepare($sql);
    $execute = $prepare->execute([':login' => $login]);
    session_destroy();
    $this -> login = NULL;
    $this -> email = NULL;
    $this -> firstname = NULL;
    $this -> lastname = NULL;    
    echo $login . "profil supprimé <br/>";
    }

    function update($login,$password,$email,$firstname,$lastname){//Met à jour les attributs de l’objet, et modifie les informations en BDD
        foreach($this -> users as $user){
            if ($login == $user[1]){
            $stop = 1;
            }
        }

        if($login == NULL || $password == NULL || $email == NULL || $firstname == NULL || $lastname == NULL){
            $stop == 1;
        } 
       
        if ($stop == 0 && isset($_SESSION["connected"])){
                
            $log = $this->login;
            $bdd=$this->bdd;

            //requete de mise a jour des infos de l'utilisateur
            $sql = "UPDATE `utilisateurs` SET login = :login, password = :password, email = :email, firstname = :firstname,lastname = :firstname WHERE `login` = :log";
            $prepare = $bdd->prepare($sql);
            $execute = $prepare->execute([':log' => $log, ':login' => $login, ':password' => $password, ':email' => $email, ':firstname' => $firstname, ':lastname' => $lastname]);
            $this -> login = $login;
            $this -> email = $email;
            $this -> firstname = $firstname;
            $this -> lastname = $lastname;
            echo"update complete <br/>";
        }
    
    }

    function isConnected(){//retourne un booleen permettant de savoir si un utilisateur est connecté ou non
        if(isset($_SESSION['connected'])){
            echo"vous etes bien connecté $this->login";}
            else echo "error";
    }

    function getAllInfos(){//retourne le login de l’utilisateur
        
        //tableau des infos de l'utilisateur
        echo "
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

    function getLogin(){//retourne le login de l’utilisateur
    echo "$this->login <br/>";
    }
    function getEmail(){//retourne l’email
    echo "$this->email <br/>";
    }
    function getFirstname(){//retourne le firstname
    echo "$this->firstname <br/>";
    }
    function getLastname() {//retourne le lastname
    echo "$this->lastname <br/>";
    }
}

//creation d'un nouvel objet
$user1 = new User();

// fonction enregister  profil
$user1->register("d","d","d","d","d");

// fonction connexion  profil
$user1->connect("polo","123");

// fonction deconnexion  profil
//$user1->disconnect();

// fonction delete  profil
//$user1->delete();

// fonction mise a jour  profil
//$user1->update("alx","x","x","x","x");

// fonction verification connexion  profil
//$user1->isConnected();

// fonction affichage toute infos profil
$user1->getAllInfos();

// fonction affichage login  profil
$user1->getLogin();

// fonction affichage  prenom profil
$user1->getFirstname();

// fonction affichage nom  profil
$user1->getLastname();

?>