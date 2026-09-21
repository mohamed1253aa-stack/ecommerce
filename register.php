<?php

session_start();

require_once "config/database.php";
$stmt = $conn->prepare($sql);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($name) || empty($email) || empty($password)) {

        $error = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email address.";

    } elseif ($password !== $confirm_password) {

        $error = "Passwords do not match.";

    } elseif(strlen($password) < 6) {

        $error = "Password must be at least 6 characters.";

    } else {

        $sql = "SELECT id FROM users WHERE email = :email";

        $stmt = $conn->prepare($sql);

        $stmt->execute([':email' => $email ]);
            
       

        if ($stmt->fetch()) {

            $error = "Email already exists.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users
                    (name, email, password)
                    VALUES
                    (:name, :email, :password)";

            

            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $hashed_password
            ]);

            $success = "Account created successfully!";
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

    <title>Register</title>

    <link rel="stylesheet"
          href="assets/css/style.css">

</head>

<body>

<h1>Create Account</h1>

<?php if ($error): ?>

    <p style="color:red;">
        <?= htmlspecialchars($error) ?>
    </p>

<?php endif; ?>

<?php if ($success): ?>

    <p style="color:green;">
        <?= htmlspecialchars($success) ?>
    </p>

    <a href="login.php">
        Login Now
    </a>

<?php else: ?>

<form action="" method="POST">

    <div>
        <label>Name</label>

        <input
            type="text"
            name="name"
            required
        >
    </div>

    <br>

    <div>
        <label>Email</label>

        <input
            type="email"
            name="email"
            required
        >
    </div>

    <br>

    <div>
        <label>Password</label>

        <input
            type="password"
            name="password"
            required
        >
    </div>

    <br>

    <div>
        <label>Confirm Password</label>

        <input
            type="password"
            name="confirm_password"
            required
        >
    </div>

    <br>

    <button type="submit">
        Register
    </button>

</form>

<br>

<a href="login.php">
    Already have an account? Login
</a>

<?php endif; ?>

</body>

</html>