<?php 
session_start();

require_once 'head/head.php';
require_once 'int/db.php';

class Login extends Database {


    public function inloggen($naam, $wachtwoord)
    {

        try {
            $sql ="SELECT * FROM users WHERE naam = :naam";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':naam', $naam);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_OBJ);

            if ($result) {
                if (property_exists($result, 'wachtwoord') && password_verify($wachtwoord, $result->wachtwoord)) {
                  
                    $_SESSION['inloggen'] = true;
                    $_SESSION['naam'] = $result->naam;
                    header('Location: Index.php');
                    exit();
                } else {
                    throw new Exception("Combinatie onjuist");
                }
            } else {
                throw new Exception("Gebruiker niet gevonden");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function uitloggen()
    {

        if (isset($_SESSION['inloggen'])) {


            unset($_SESSION['inloggen']);
            unset($_SESSION[['naam']]);
            session_destroy();

            header('Location: Login.php');
            exit();
        } else {


            echo 'User is not logged in.';
        }
    }

}
$gebruiker = new Login();

if (isset($_POST['inloggen'])){

    $gebruiker->inloggen($_POST['naam'], $_POST['wachtwoord']);
}



?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>head</title>
</head>

<body>
    <main>
        <label>Inloggen</label>
        <section class="formR">
            <form method="post">

                <label for="naam">naam</label>
                <input type="text" name="naam" id="user_name">
                <label for="wachtwoord">wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord">
                <input type="submit" name="inloggen" value="inloggen">
            </form>
            <a href="Register.php">Registreren</a>
        </section>
    </main>
</body>

</html>