<?php

require_once 'head/head.php';
require_once 'int/db.php';

class Register extends database{

    public function register($naam, $wachtwoord, $adres, $tel_nr)
    {
        $message = "";
        try {
            $options = ['cost' => 6];
            $passwordCrypt = password_hash($wachtwoord, PASSWORD_BCRYPT, $options);

            $sql = "INSERT INTO users (naam, wachtwoord, adres, tel_nr) VALUES(:naam, :wachtwoord, :adres, :tel_nr)";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':naam', $naam);
            $stmt->bindParam(':wachtwoord', $passwordCrypt);
            $stmt->bindParam(':adres', $adres);
            $stmt->bindParam(':tel_nr', $tel_nr);

            if ($stmt->execute()) {
                $message = "Gegevens opgeslagen, u wordt doorverwezen naar de volgende pagina.";

                header("Location: Login.php");
                exit(); 
            } else {
                throw new Exception("Er ging iets fout met het account aanmaken.");
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $message;
    }
}

$register = new Register();

if (isset($_POST['Register'])) {
    $message = $register->Register(
        
        $_POST['naam'],
        $_POST['wachtwoord'],
        $_POST['adres'],
        $_POST['tel_nr']

    );

    if (!empty($message)) {
        echo '<p class="succes-message">' . $message . '</p>';
    }
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
        <label>Klant registreren</label>
        <section class="formR">
            <form method="post">
                <label for="naam">Naam</label>
                <input type="text" name="naam" id="naam">

                <label for="wachtwoord">Wachtwoord</label>
                <input type="password" name="wachtwoord" id="wachtwoord">

                <label for="adres">Adres</label>
                <input type="text" name="adres" id="adres">

                <label for="tel_nr">Telefoonnummer</label>
                <input type="text" name="tel_nr" id="tel_nr">

                <input type="submit" name="Register" value="Register">
            </form>

            <a href="Login.php">Inloggen</a>
        </section>


    </main>
</body>