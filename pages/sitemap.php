<?php
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/xml; charset=utf-8');

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['PHP_SELF'])), '/'); // .../pages
$siteRoot = rtrim(str_replace('\\', '/', dirname($scriptDir)), '/');           // one level up
$baseUrl = $protocol . '://' . $host . $scriptDir;
$siteRootUrl = $protocol . '://' . $host . $siteRoot;

$obituaries = $pdo->query('SELECT slug, submission_date FROM obituaries ORDER BY submission_date DESC')->fetchAll();

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?php echo htmlspecialchars($siteRootUrl . '/obituary_form.php'); ?></loc>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . '/view_obituaries.php'); ?></loc>
        <changefreq>daily</changefreq>
        <priority>0.8</priority>
    </url>
    <?php foreach ($obituaries as $obituary): ?>
    <url>
        <loc><?php echo htmlspecialchars($baseUrl . '/obituary.php?slug=' . urlencode($obituary['slug'])); ?></loc>
        <lastmod><?php echo date('Y-m-d', strtotime($obituary['submission_date'])); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <?php endforeach; ?>
</urlset>
