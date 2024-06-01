<?php
session_start();

// Verifica se l'utente è loggato, altrimenti reindirizza al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

define("DB_SERVERNAME", "localhost");
define("DB_USERNAME", "root");
define("DB_PASSWORD", "root");
define("DB_NAME", "myblog");


$user_id = $_SESSION['user_id'];

// Connessione al database
$connection = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

if ($connection->connect_error) {
    die('Errore di connessione: ' . $connection->connect_error);
}

// Funzione per pulire i dati di input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Aggiungi un nuovo post
if (isset($_POST['create'])) {
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $stmt = $connection->prepare("INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param('iss', $user_id, $title, $content);
    $stmt->execute();
    $stmt->close();
}

// Cancella un post
if (isset($_POST['delete'])) {
    $post_id = $_POST['post_id'];
    $stmt = $connection->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ii', $post_id, $user_id);
    $stmt->execute();
    $stmt->close();
}

// Query per selezionare i post dell'utente specificato
$query = $connection->prepare("SELECT * FROM posts WHERE user_id = ?");
$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header class="d-flex justify-content-between container pt-4">
    <div>
        <h1>User Dashboard</h1>
    </div>
    <div >
        <a href="index.php" class="btn btn-secondary">Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</header>
<main class="container">
    <h2>Posts</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="3" required></textarea>
        </div>
        <button type="submit" name="create" class="btn btn-primary">Add Post</button>
    </form>
    <div class="row">
        <div class="col-12">
            <?php
            if ($result && $result->num_rows > 0) {
                echo "<table class='table table-bordered mt-3'>";
                echo "<thead><tr><th>Title</th><th>Content</th><th>Actions</th></tr></thead>";
                echo "<tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td>
                        <a href='edit_post.php?id=" . $row['id'] . "' class='btn btn-warning'>Edit</a>
                        <form method='POST' action='' class='d-inline'>
                            <input type='hidden' name='post_id' value='" . $row['id'] . "'>
                            <button type='submit' name='delete' class='btn btn-danger'>Delete</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "Nessun post trovato.";
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
$query->close();
$connection->close();
?>
