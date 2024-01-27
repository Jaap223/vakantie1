<?php
session_start();

require_once 'head/Head.php';
require_once 'int/db.php';

?>

<body>

<?php echo 'Welcome, ' . $_SESSION['naam'] . '!'; ?>
<?php 
if (isset($_SESSION['inloggen']) && $_SESSION['inloggen']) {
    echo '<a href="Login.php">Logout</a>';
}


?>

<div class="welkom">
        <h1>Welkom !</h1>

        <p>Over ons:
            .</p>

    </div>





</body>