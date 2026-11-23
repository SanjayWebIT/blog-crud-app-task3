<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_login();

$errors = [];
$title = '';
$content = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $title = trim((string)($_POST['title'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    if ($title === '' || mb_strlen($title) > 200) $errors[] = 'Title is required and must be 200 characters or fewer.';
    if ($content === '') $errors[] = 'Content is required.';
    if (!$errors) {
        $stmt = $pdo->prepare('INSERT INTO posts (user_id, title, content) VALUES (?, ?, ?)');
        $stmt->execute([$_SESSION['user_id'], $title, $content]);
        redirect('index.php');
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>New post</title><link rel="stylesheet" href="style.css"></head>
<body><main class="container narrow"><a href="index.php">← Blog home</a><h1>Create post</h1>
<?php foreach ($errors as $error): ?><p class="error"><?= e($error) ?></p><?php endforeach; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Title</label><input name="title" maxlength="200" required value="<?= e($title) ?>">
<label>Content</label><textarea name="content" rows="10" required><?= e($content) ?></textarea>
<button type="submit">Publish post</button></form></main></body></html>
