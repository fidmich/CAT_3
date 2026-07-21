<?php
require_once __DIR__ . '/../includes/db.php';

$perPage = 10;
$page = isset($_GET['page']) ? max(1, (int) $_GET['page']) : 1;
$offset = ($page - 1) * $perPage;

$totalCount = (int) $pdo->query('SELECT COUNT(*) FROM obituaries')->fetchColumn();
$totalPages = max(1, (int) ceil($totalCount / $perPage));
$page = min($page, $totalPages);
$offset = ($page - 1) * $perPage;

$stmt = $pdo->prepare('SELECT id, name, date_of_birth, date_of_death, author, submission_date, slug
                        FROM obituaries
                        ORDER BY submission_date DESC
                        LIMIT :limit OFFSET :offset');
$stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$obituaries = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Obituaries | Obituary Platform</title>
    <meta name="description" content="Browse obituaries submitted to the Obituary Platform, honoring the lives and memories of loved ones.">
    <meta name="keywords" content="obituaries, memorials, in memory of, funeral notices">
    <link rel="canonical" href="view_obituaries.php">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="../obituary_form.php">Obituary Platform</a>
        <nav class="site-nav">
            <a href="../obituary_form.php">Submit an Obituary</a>
            <a href="view_obituaries.php">View Obituaries</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <h1>All Obituaries</h1>

            <?php if (empty($obituaries)): ?>
                <p>No obituaries have been submitted yet. <a href="../obituary_form.php">Submit the first one.</a></p>
            <?php else: ?>
                <table class="obituary-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Date of Birth</th>
                            <th>Date of Death</th>
                            <th>Author</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($obituaries as $obituary): ?>
                            <tr>
                                <td><a href="obituary.php?slug=<?php echo urlencode($obituary['slug']); ?>">
                                    <?php echo htmlspecialchars($obituary['name']); ?>
                                </a></td>
                                <td><?php echo htmlspecialchars(format_dmy($obituary['date_of_birth'])); ?></td>
                                <td><?php echo htmlspecialchars(format_dmy($obituary['date_of_death'])); ?></td>
                                <td><?php echo htmlspecialchars($obituary['author']); ?></td>
                                <td><?php echo htmlspecialchars($obituary['submission_date']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <?php if ($i === $page): ?>
                                <span class="current"><?php echo $i; ?></span>
                            <?php else: ?>
                                <a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            <?php endif; ?>
                        <?php endfor; ?>

                        <?php if ($page < $totalPages): ?>
                            <a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>

    <footer class="site-footer">
        &copy; <?php echo date('Y'); ?> Obituary Platform. Built with PHP &amp; MySQL.
    </footer>
</body>
</html>
