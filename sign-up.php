<?php
include "config.php";

$name = $email = $passwd = '';
$message = $nameErr = $emailErr = $passwdErr = $passwdConfirmErr = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["username"])) {
        $nameErr = "Please provide an username.";
    } else {
        $name = testInput($_POST["username"]);
        if (!preg_match("/^[a-zA-Z0-9_\-\.]{3,30}$/", $name)) {
            $nameErr = "Between 3 and 30 characters, may contain only letters, numbers, ., - and _.";
        } else {
            $stmt = $conn->prepare("SELECT * FROM `users` WHERE username = ?");
            $stmt->bind_param("s", $name);
            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $nameErr = "Username already exists.";
                    $stmt->close();
                }
            } else {
                echo "An error occurred" . $stmt->error;
                exit();
            }
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Please provide a valid email address.";
    } else {
        $email = testInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Email address is invalid.";
        }
    }

    if (empty($_POST["password"])) {
        $passwdErr = "Please provide a password.";
    } else if (!preg_match("/^(?=.*[A-Z])(?=.*\d)[\w_\.\-]{6,}$/", $_POST["password"])) {
        $passwdErr = "Password must be at least 6 characters, and include at least 1 capital letter and 1 digit.";
    } else if ($_POST["password-confirm"] !== $_POST['password']) {
        $passwdConfirmErr = "Confirmation password doesn't match.";
    } else {
        $passwd = password_hash($_POST["password"], PASSWORD_DEFAULT);
    }

    if (!$nameErr && !$emailErr && !$passwdErr && !$passwdConfirmErr) {
        if ($stmt = $conn->prepare("INSERT INTO `users` (`username`, `email`, `password_hash`) VALUES (?, ?, ?)")) {
            $stmt->bind_param('sss', $name, $email, $passwd);
            if ($stmt->execute()) {
                $message = "Successfully Signed Up.";
            } else {
                $message = "An error occurred" . $stmt->error;
            }
            $stmt->close();
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
    <title>Sign Up</title>
    <link href="styles.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>
    <main class="form-container">
        <h1>Sign Up</h1>
        <form class="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="POST">
            <div id="message-wrapper">
                <div class="message"><?php echo $message ?></div>
            </div>
            <script>
                const message = "<?php echo $message ?>"
                if (message.length > 0) document.getElementById('message-wrapper').style.display = 'flex'
            </script>
            <div class="label-wrapper">
                <label for="name">Name: </label>
            </div>
            <input type="text" name="username" placeholder="Name" id="name" value="<?php echo $name ?>" autocomplete>
            <div class="error"><?php echo $nameErr ?></div>
            <div class="label-wrapper">
                <label for="email">Email: </label>
            </div>
            <input type="email" name="email" placeholder="Email" id="email" value="<?php echo $email ?>" autocomplete>
            <div class="error"><?php echo $emailErr ?></div>
            <div class="label-wrapper">
                <label for="passwd">Password: </label>
            </div>
            <input type="password" name="password" placeholder="Password" id="password" autocomplete>
            <div class="error"><?php echo $passwdErr ?></div>
            <div class="label-wrapper">
                <label for="passwd-confirm">Confirm Password: </label>
            </div>
            <input type="password" name="password-confirm" placeholder="Confirm password" id="password-confirm" autocomplete>
            <div class="error"><?php echo $passwdConfirmErr ?></div>
            <input class="signup-form-button" type="submit" value="Sign up">
        </form>
        <div class="footer">
            <div>OR</div>
            <a id="footer-anchor" href="sign-in.php"><button style="width: 100px; height: 35px;" id="footer-btn">Sign In</button></a>
        </div>
    </main>
</body>

</html>