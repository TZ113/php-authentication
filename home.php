<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="styles.css" rel="stylesheet">
</head>

<body style="background-color:antiquewhite; ">
    <main class="greetings-body">
        <h1 class="greetings-text">Hello, <?php
                                            if ($_SESSION["username"]) {
                                                echo $_SESSION["username"] .  "</h1>"; ?>
                <a href="sign-out.php" id="logout-anchor"><button id="logout-btn">Log Out</button></a>
            <?php
                                            } else {
                                                echo "Whoever you are!</h1>";
            ?>
                <a href="sign-out.php" id="logout-anchor"><button id="logout-btn">Get Out</button></a>
            <?php } ?>
    </main>
</body>

</html>