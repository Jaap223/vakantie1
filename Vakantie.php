<?php


require_once 'head/Head.php';
require_once 'int/db.php';

class Vakantie extends Database
{
    public function vakantieBoeken($klantid, $bestemming, $vertrekdatum, $terugkeerdatum, $prijs)
    {
        try {

            session_start();
            $klantid = isset($_SESSION['klantid']) ? $_SESSION['klantid'] : null;


            $sql = "INSERT INTO vakanties (klantid, bestemming, vertrekdatum, terugkeerdatum, prijs) VALUES (:klantid, :bestemming, :vertrekdatum, :terugkeerdatum, :prijs)";
            $stmt = $this->connect()->prepare($sql);

            $stmt->bindParam(':klantid', $klantid, PDO::PARAM_INT);
            $stmt->bindParam(':bestemming', $bestemming, PDO::PARAM_STR);
            $stmt->bindParam(':vertrekdatum', $vertrekdatum, PDO::PARAM_STR);
            $stmt->bindParam(':terugkeerdatum', $terugkeerdatum, PDO::PARAM_STR);
            $stmt->bindParam(':prijs', $prijs, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->rowCount();
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }


    public function VakantieData()
    {
        try {
            $sql = "SELECT * FROM vakanties";
            $stmt = $this->connect()->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            throw new Exception("Error " . $e->getMessage());
        }
    }

    public function deleteVakantie($vakantieid)
    {
        try {
            $sql = "DELETE FROM vakanties WHERE vakantieid = :vakantieid";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':vakantieid', $vakantieid, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error " . $e->getMessage());
        }
    }
}

$add = '';
$delResult = new Vakantie();
$mes = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['vakantieBoeken'])) {
        $klantid = isset($_SESSION['userid']) ? $_SESSION['userid'] : null;
        $bestemming = isset($_POST['bestemming']) ? $_POST['bestemming'] : '';
        $vertrekdatum = $_POST['vertrekDatum'];
        $terugkeerdatum = $_POST['terugkeerDatum'];
        $prijs = $_POST['prijs'];

        $vakantieBoek = new Vakantie();
        $vakantieData = $vakantieBoek->vakantieBoeken($klantid, $bestemming, $vertrekdatum, $terugkeerdatum, $prijs);

        if ($vakantieData > 0) {
            $add = 'Vakantie toegevoegd';
        } else {
            $add = 'Vakantie toevoegen mislukt';
        }
    }
}

?>

<section class="formR">
    <form method="post">
        <label for="bestemming">Bestemming</label>
        <input type="text" name="bestemming" id="bestemming" required>

        <label for="vertrekDatum">Vertrekdatum</label>
        <input type="date" name="vertrekDatum" id="vertrekDatum" required>

        <label for="terugkeerDatum">Terugkeerdatum</label>
        <input type="date" name="terugkeerDatum" id="terugkeerDatum" required>

        <label for="prijs">Prijs</label>
        <input type="number" step="0.01" name="prijs" id="prijs" required>
        <input type="hidden" name="userid" value="<?php echo $klantid; ?>">

        <input type="submit" name="vakantieBoeken" value="Vakantie Boeken">
    </form>
    <a href="VakantieWijzigen.php">Vakantie wijzigen</a>
</section>

<table class="tab2">
    <h2>Vakantie bestemmingen</h2>
    <tr>
        <th>Bestemming</th>
        <th>Vertrekdatum</th>
        <th>Terugkeerdatum</th>
        <th>Action</th>
    </tr>
    <?php
    $vakantieBoek = new Vakantie();

    $vakantieData = $vakantieBoek->VakantieData();

    foreach ($vakantieData as $vakantie) {
        echo "<tr>";
        echo "<td>{$vakantie['bestemming']}</td>";
        echo "<td>{$vakantie['vertrekdatum']}</td>";
        echo "<td>{$vakantie['terugkeerdatum']}</td>";
        echo "<td>{$vakantie['prijs']}</td>";
        echo "<td>
                <form method='post' action='{$_SERVER['PHP_SELF']}'>
                    <input type='hidden' name='vakantieid' value='{$vakantie['vakantieid']}'>
                    <input type='hidden' name='action' value='deleteVakantie'>
                    <button type='submit' name='deleteVakantie'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
</table>