<?php
require_once 'head/Head.php';
require_once 'int/db.php';

class VakantieWijzigen extends Database
{
    public function wijzigVakantie($bestemming, $vertrekdatum, $terugkeerdatum, $prijs, $vakantieid)
    {
        try {
            $sql = "UPDATE vakanties 
                    SET bestemming = :bestemming, 
                        vertrekdatum = :vertrekdatum, 
                        terugkeerdatum = :terugkeerdatum, 
                        prijs = :prijs 
                    WHERE vakantieid = :vakantieid";

            $stmt = $this->connect()->prepare($sql);

            $stmt->bindParam(':bestemming', $bestemming, PDO::PARAM_STR);
            $stmt->bindParam(':vertrekdatum', $vertrekdatum, PDO::PARAM_STR);
            $stmt->bindParam(':terugkeerdatum', $terugkeerdatum, PDO::PARAM_STR);
            $stmt->bindParam(':prijs', $prijs, PDO::PARAM_INT);
            $stmt->bindParam(':vakantieid', $vakantieid, PDO::PARAM_INT);

            $stmt->execute();

            return $stmt->rowCount();
        } catch (Exception $e) {
            error_log("Error in wijzigVakantie: " . $e->getMessage());
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
            throw new Exception("Error: " . $e->getMessage());
        }
    }

    public function deleteVakantie($vakantieid)
    {
        try {
            $sql =   "DELETE FROM vakanties WHERE vakantieid = :vakantieid";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(':vakantieid', $vakantieid, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            throw new Exception("Error deleting vakantie" . $e->getMessage());
        }
    }
}

$mes = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $wVakantie = new VakantieWijzigen();


        if (isset($_POST['wijzigVakantie'])) {
            $bestemming = $_POST['bestemming'];
            $vertrekdatum = $_POST['vertrekdatum'];
            $terugkeerdatum = $_POST['terugkeerdatum'];
            $prijs = $_POST['prijs'];
            $vakantieid = $_POST['vakantieid'];

            $wVakantie2 = $wVakantie->wijzigVakantie($bestemming, $vertrekdatum, $terugkeerdatum, $prijs, $vakantieid);

            if ($wVakantie2 > 0) {
                $mes = 'Vakantie gewijzigd';
            } else {
                $mes = 'Vakantie wijzigen mislukt';
            }
        }


        if (isset($_POST['deleteVakantie'])) {
            $vakantieid = $_POST['vakantieid'];
            $del = $wVakantie->deleteVakantie($vakantieid);
            if ($del > 0) {
                $mes = 'Vakantie verwijderd';
            } else {
                $mes = 'Vakantie verwijderen mislukt';
            }
        }
    } catch (Exception $e) {
        $mes = 'Error: ' . $e->getMessage();
    }
}
?>

<section class="formR">
    <h2>Vakantie wijzigen</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <label for="bestemming">Bestemming</label>
        <input type="text" name="bestemming" id="bestemming" required>
        <label for="vertrekDatum">Vertrekdatum</label>
        <input type="date" name="vertrekdatum" id="vertrekDatum" required>
        <label for="terugkeerDatum">Terugkeerdatum</label>
        <input type="date" name="terugkeerdatum" id="terugkeerDatum" required>
        <label for="prijs">Prijs</label>
        <input type="number" step="0.01" name="prijs" id="prijs" required>
        <input type="hidden" name="vakantieid" value="<?php echo isset($_POST['vakantieid']) ? htmlspecialchars($_POST['vakantieid']) : ''; ?>">
        <input type="submit" name="wijzigVakantie" value="Wijzig">
    </form>
    <a href="Vakantien.php">Vakantie boeken</a>
</section>

<br>

<table class="tab2">
    <h2>Vakantie bestemmingen</h2>
    <tr>
        <th>Bestemming</th>
        <th>Vertrekdatum</th>
        <th>Terugkeerdatum</th>
        <th>Action</th>
    </tr>
    <?php
    $vakantieBoek = new VakantieWijzigen();
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
                    <button type='submit' name='deleteVakantie'>Delete</button>
                </form>
              </td>";
        echo "</tr>";
    }
    ?>
</table>