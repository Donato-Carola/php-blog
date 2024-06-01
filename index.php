<!-- //*! PHP -->

<?
session_start();

define("DB_SERVERNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "myblog");

$connection = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

if ( $connection->connect_error) {
    echo 'errore di connesione: ' . $connection->connect_error;
}

$query = "SELECT * FROM `posts`";
$result = $connection->query($query);



?>

<!-- //*! HTML -->


<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>
    <header class="d-flex justify-content-between container">
        <div>
            <h1>My Awesome Blog</h1>
        </div>
        <div class="pt-1">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="crud.php" class="btn btn-primary">Dashboard</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <main>
        <div class="container">
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3">
                <?php
                if ($result->num_rows > 0) {
                    // Output dei dati di ogni riga
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class=" col mb-3">';
                        echo '<div class="card h-100">';
                        echo '<div class="card-body">';
                        echo '<h2 class="card-title">' . htmlspecialchars($row["title"]) . '</h2>';
                        echo '<p class="card-text">' . htmlspecialchars($row["content"]) . '</p>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<div class="col">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<p class="card-text">Nessun post trovato</p>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGztycQ0tYChuyLk1T6gD60qu5T8FF35F6O8y5Rhcz+AG" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-6mGh2H6HUOMv70p2vWOV0Uy2slzOzN25cmrN1ThFLuB7ZEKy57ydF0k8+NfRBpUS" crossorigin="anonymous"></script>

</body>

</html>

<?php
$connection->close();
?>