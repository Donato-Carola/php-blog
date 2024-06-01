<?php
session_start();

$login = false;


define("DB_SERVERNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "myblog");

if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $connection = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

    if ($connection->connect_error) {
        die('Errore di connessione: ' . $connection->connect_error);
    }

    $query = $connection->prepare("SELECT * FROM `users` WHERE `username` = ? AND `password` = ?");
    $query->bind_param('ss', $username, $password);
    $query->execute();
    $result = $query->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        header("Location: crud.php");
        exit;
    } else {
        echo '<div class="alert alert-danger" role="alert">Credenziali non corrette</div>';
    }

    $query->close();
    $connection->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header class="d-flex justify-content-between container">
        <div>
            <a class="my_link" href="./index.php">
                <h1>My Awesome Blog</h1>
            </a>
        </div>
        <a href="login.php">
            Login
        </a>
    </header>
    <main class="container">
        <h1 class="text-center mb-5 mt-3">Login for personal CRUD</h1>
        <section class="row justify-content-center" id="login">
            <div class="col-5">
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGztycQ0tYChuyLk1T6gD60qu5T8FF35F6O8y5Rhcz+AG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-6mGh2H6HUOMv70p2vWOV0Uy2slzOzN25cmrN1ThFLuB7ZEKy57ydF0k8+NfRBpUS" crossorigin="anonymous"></script>
</body>
</html>
