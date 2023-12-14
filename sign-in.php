<?php
include "config.php";
session_start();
$username = $passwd = $error = $nameErr = $passwdErr = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $nameErr = "Username is required.";
    } else if (empty($_POST["passwd"])) {
        $passwdErr = "Password is required.";
    }


    if (!$nameErr && !$passwdErr) {
        $username = testInput($_POST["username"]);
        $passwd = $_POST["passwd"];
        if ($stmt = $conn->prepare("SELECT * FROM `users` WHERE `username` = ?")) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                // $stmt->store_result();
                $result = $stmt->get_result()->fetch_all();
                // echo $result;
                // echo count($result);
                if (count($result) === 1 && password_verify($passwd, $result[0][3])) {
                    echo "verified";
                    $_SESSION["id"] = $result[0][0];
                    $_SESSION["username"] = $result[0][1];
                    header("location:home.php");
                    exit;
                } else {
                    $error = "Username and/or password doesn't match.";
                }
                $stmt->close();
            } else {
                exit("An error occurred");
            }
        }
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <main class="signin form-container">
        <div class="error" style="margin: auto;"><?php echo $error ?></div>
        <h1>Sign In</h1>
        <form class="signin-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <input type="text" class="signin-form-input" name="username" value="<?php echo $username ?>" placeholder="Username" autocomplete>
            <div class="error signin-error"><?php echo $nameErr ?></div>
            <input type="password" class="signin-form-input" name="passwd" value="<?php echo $passwd ?>" placeholder="Password" autocomplete>
            <div class="error signin-error"><?php echo $passwdErr ?></div>
            <input type="submit" value="Sign In">
        </form>
        <div class="footer">
            <div>OR</div>
            <a id="footer-anchor" href="sign-up.php"><button id="footer-btn">Sign Up</button></a>
        </div>
    </main>
</body>

</html>