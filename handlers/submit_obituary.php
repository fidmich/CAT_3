<?php
require_once __DIR__ . '/../includes/db.php';

$errors = [];
$success = false;
$newSlug = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name          = trim($_POST['name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $date_of_death = trim($_POST['date_of_death'] ?? '');
    $content       = trim($_POST['content'] ?? '');
    $author        = trim($_POST['author'] ?? '');

    if ($name === '' || mb_strlen($name) > 100) {
        $errors[] = 'Name is required and must be 100 characters or fewer.';
    }
    if ($author === '' || mb_strlen($author) > 100) {
        $errors[] = 'Author is required and must be 100 characters or fewer.';
    }
    if ($content === '' || mb_strlen($content) < 20) {
        $errors[] = 'Content is required and must be at least 20 characters.';
    }

    $dob = parse_dmy_date($date_of_birth);
    $dod = parse_dmy_date($date_of_death);

    if (!$dob) {
        $errors[] = 'A valid date of birth is required (DD/MM/YYYY).';
    }
    if (!$dod) {
        $errors[] = 'A valid date of death is required (DD/MM/YYYY).';
    }
    if ($dob && $dod && $dod < $dob) {
        $errors[] = 'Date of death cannot be before date of birth.';
    }

    if (empty($errors)) {
        try {
            // Build a unique slug from the name; append a numeric suffix on collision.
            $baseSlug = slugify($name);
            if ($baseSlug === '') {
                $baseSlug = 'obituary';
            }
            $slug = $baseSlug;
            $suffix = 1;

            $checkStmt = $pdo->prepare('SELECT COUNT(*) FROM obituaries WHERE slug = :slug');
            do {
                $checkStmt->execute(['slug' => $slug]);
                if ($checkStmt->fetchColumn() > 0) {
                    $suffix++;
                    $slug = $baseSlug . '-' . $suffix;
                } else {
                    break;
                }
            } while (true);

            $insertStmt = $pdo->prepare(
                'INSERT INTO obituaries (name, date_of_birth, date_of_death, content, author, slug)
                 VALUES (:name, :date_of_birth, :date_of_death, :content, :author, :slug)'
            );
            $insertStmt->execute([
                'name'          => $name,
                'date_of_birth' => $dob->format('Y-m-d'),
                'date_of_death' => $dod->format('Y-m-d'),
                'content'       => $content,
                'author'        => $author,
                'slug'          => $slug,
            ]);

            $success = true;
            $newSlug = $slug;
        } catch (PDOException $e) {
            $errors[] = 'Could not save the obituary due to a database error. Please try again.';
        }
    }
} else {
    header('Location: ../obituary_form.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submission Result | Obituary Platform</title>
    <meta name="description" content="Result of your obituary submission.">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="../obituary_form.php">Obituary Platform</a>
        <nav class="site-nav">
            <a href="../obituary_form.php">Submit an Obituary</a>
            <a href="../pages/view_obituaries.php">View Obituaries</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    Obituary submitted successfully.
                </div>
                <p>Thank you for sharing this obituary.</p>
                <a class="btn" href="../pages/obituary.php?slug=<?php echo urlencode($newSlug); ?>">View Obituary</a>
                <a class="btn" href="../pages/view_obituaries.php">View All Obituaries</a>
            <?php else: ?>
                <div class="alert alert-error">
                    There were problems with your submission:
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error); ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <a class="btn" href="../obituary_form.php">Back to Form</a>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        &copy; <?php echo date('Y'); ?> Obituary Platform. Built with PHP &amp; MySQL.
    </footer>
</body>
</html>
