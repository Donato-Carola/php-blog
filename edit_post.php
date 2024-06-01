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
$post_id = $_GET['id'];

// Connessione al database
$connection = new mysqli(DB_SERVERNAME, DB_USERNAME, DB_PASSWORD, DB_NAME, 3306);

if ($connection->connect_error) {
    die('Errore di connessione: ' . $connection->connect_error);
}

// Funzione per pulire i dati di input
function clean_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Aggiorna un post esistente
if (isset($_POST['update'])) {
    $title = clean_input($_POST['title']);
    $content = clean_input($_POST['content']);
    $stmt = $connection->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?");
    $stmt->bind_param('ssii', $title, $content, $post_id, $user_id);
    $stmt->execute();
    $stmt->close();
    header("Location: crud.php");
    exit;
}

// Query per selezionare il post da modificare
$stmt = $connection->prepare("SELECT * FROM posts WHERE id = ? AND user_id = ?");
$stmt->bind_param('ii', $post_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Post non trovato.");
}

$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
<header class="d-flex justify-content-between container">
    <div>
        <h1>Edit Post</h1>
    </div>
    <a href="crud.php">Back to Dashboard</a>
</header>
<main class="container">
    <h2>Edit Post</h2>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo $post['title']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="3" required><?php echo $post['content']; ?></textarea>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Post</button>
    </form>
</main>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybBogGztycQ0tYChuyLk1T6gD60qu5T8FF35F6O8y5Rhcz+AG" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-6mGh2H6HUOMv70p2vWOV0Uy2slzOzN25cmrN1ThFLuB7ZEKy57ydF0k8+NfRBpUS" crossorigin="anonymous"></script>
</body>
</html>

<?php
$stmt->close();
$connection->close();
?>
