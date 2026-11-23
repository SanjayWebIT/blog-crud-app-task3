<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
require_login();

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { http_response_code(400); exit('Invalid post ID.'); }

$stmt = $pdo->prepare('SELECT id, title, content FROM posts WHERE id = ? AND user_id = ?');
$stmt->execute([$id, $_SESSION['user_id']]);
$post = $stmt->fetch();
if (!$post) { http_response_code(404); exit('Post not found or you do not have permission to edit it.'); }

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    if (isset($_POST['delete'])) {
        $delete = $pdo->prepare('DELETE FROM posts WHERE id = ? AND user_id = ?');
        $delete->execute([$id, $_SESSION['user_id']]);
        redirect('index.php');
    }
    $title = trim((string)($_POST['title'] ?? ''));
    $content = trim((string)($_POST['content'] ?? ''));
    if ($title === '' || mb_strlen($title) > 200) $errors[] = 'Title is required and must be 200 characters or fewer.';
    if ($content === '') $errors[] = 'Content is required.';
    if (!$errors) {
        $update = $pdo->prepare('UPDATE posts SET title = ?, content = ? WHERE id = ? AND user_id = ?');
        $update->execute([$title, $content, $id, $_SESSION['user_id']]);
        redirect('index.php');
    }
    $post['title'] = $title;
    $post['content'] = $content;
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Edit post</title><link rel="stylesheet" href="style.css"></head>
<body><main class="container narrow"><a href="index.php">← Blog home</a><h1>Edit post</h1>
<?php foreach ($errors as $error): ?><p class="error"><?= e($error) ?></p><?php endforeach; ?>
<form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<label>Title</label><input name="title" maxlength="200" required value="<?= e($post['title']) ?>">
<label>Content</label><textarea name="content" rows="10" required><?= e($post['content']) ?></textarea>
<button type="submit">Save changes</button></form>
<form method="post" onsubmit="return confirm('Delete this post permanently?');" class="delete-form">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<button class="danger" type="submit" name="delete" value="1">Delete post</button>
</form></main></body></html>
