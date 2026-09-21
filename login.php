<?php

session_start();

require_once "config/database.php";
$stmt = $conn->prepare($sql);
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {

        $error = "Please fill all fields.";

    } else {

        $sql = "SELECT * FROM users WHERE email = :email";

        

        $stmt->execute([     ':email' => $email]);
        
       

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify(
            $password,
            $user['password']
        )) {

            $_SESSION['user_id'] = $user['id'];

            $_SESSION['user_name'] = $user['name'];

            $_SESSION['user_email'] = $user['email'];

            header("Location: index.php");

        }
         else {

            $error = "Invalid email or password.";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

</head>

<body>

<h1>Login</h1>

<?php if ($error): ?>

    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<form action="" method="POST">

    <div>

        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >

    </div>

    <div>

        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >

    </div>

    <button type="submit">
        Login
    </button>

</form>

<a href="register.php">
    Create New Account
</a>

</body>

</html>