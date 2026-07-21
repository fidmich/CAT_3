<?php
require_once __DIR__ . '/../includes/db.php';

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
    http_response_code(404);
    die('Obituary not found.');
}

$stmt = $pdo->prepare('SELECT * FROM obituaries WHERE slug = :slug LIMIT 1');
$stmt->execute(['slug' => $slug]);
$obituary = $stmt->fetch();

if (!$obituary) {
    http_response_code(404);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Obituary Not Found | Obituary Platform</title>
        <link rel="stylesheet" href="../assets/style.css">
    </head>
    <body>
        <main><div class="card">
            <h1>Obituary Not Found</h1>
            <p>We couldn't find the obituary you were looking for.</p>
            <a class="btn" href="view_obituaries.php">Back to All Obituaries</a>
        </div></main>
    </body>
    </html>
    <?php
    exit;
}

// Build absolute URL for canonical / OG tags.
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/');
$baseUrl = $protocol . '://' . $host . $scriptDir;
$pageUrl = $baseUrl . '/obituary.php?slug=' . urlencode($obituary['slug']);

$pageTitle = htmlspecialchars($obituary['name']) . ' - Obituary';
$excerpt = mb_substr(strip_tags($obituary['content']), 0, 160);
$metaDescription = htmlspecialchars(rtrim($excerpt) . (mb_strlen($obituary['content']) > 160 ? '...' : ''));

$jsonLd = [
    '@context' => 'https://schema.org',
    '@type' => 'Person',
    'name' => $obituary['name'],
    'birthDate' => $obituary['date_of_birth'],
    'deathDate' => $obituary['date_of_death'],
    'description' => strip_tags($obituary['content']),
    'url' => $pageUrl,
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> | Obituary Platform</title>
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="obituary, <?php echo htmlspecialchars($obituary['name']); ?>, memorial, in memory of">
    <link rel="canonical" href="<?php echo htmlspecialchars($pageUrl); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="profile">
    <meta property="og:title" content="<?php echo $pageTitle; ?>">
    <meta property="og:description" content="<?php echo $metaDescription; ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($pageUrl); ?>">
    <meta property="og:site_name" content="Obituary Platform">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="<?php echo $pageTitle; ?>">
    <meta name="twitter:description" content="<?php echo $metaDescription; ?>">

    <!-- Structured Data (schema.org) -->
    <script type="application/ld+json">
        <?php echo json_encode($jsonLd, JSON_UNESCAPED_SLASHES); ?>
    </script>

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
        <article class="card obituary-detail">
            <h1><?php echo htmlspecialchars($obituary['name']); ?></h1>
            <p class="dates">
                <?php echo htmlspecialchars(format_dmy($obituary['date_of_birth'])); ?>
                &ndash;
                <?php echo htmlspecialchars(format_dmy($obituary['date_of_death'])); ?>
            </p>

            <div class="content"><?php echo nl2br(htmlspecialchars($obituary['content'])); ?></div>

            <p class="author">Written by <?php echo htmlspecialchars($obituary['author']); ?></p>
        </article>
    </main>

    <footer class="site-footer">
        &copy; <?php echo date('Y'); ?> Obituary Platform. Built with PHP &amp; MySQL.
    </footer>
</body>
</html>
