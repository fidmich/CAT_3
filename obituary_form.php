<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit an Obituary | Obituary Platform</title>
    <meta name="description" content="Submit an obituary to honor and remember a loved one. Share their story with family, friends, and the wider community.">
    <meta name="keywords" content="obituary, submit obituary, memorial, in memory of, funeral notice">
    <link rel="canonical" href="obituary_form.php">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header class="site-header">
        <a class="brand" href="obituary_form.php">Obituary Platform</a>
        <nav class="site-nav">
            <a href="obituary_form.php">Submit an Obituary</a>
            <a href="pages/view_obituaries.php">View Obituaries</a>
        </nav>
    </header>

    <main>
        <div class="card">
            <h1>Submit an Obituary</h1>
            <p>Please fill in the details below to publish an obituary in memory of a loved one.</p>

            <form id="obituary-form" class="obituary-form" method="POST" action="handlers/submit_obituary.php" novalidate>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" maxlength="100" required>
                <span id="name-error" class="field-error"></span>

                <label for="date_of_birth">Date of Birth (DD/MM/YYYY)</label>
                <input type="text" id="date_of_birth" name="date_of_birth" inputmode="numeric" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" required>
                <span id="date_of_birth-error" class="field-error"></span>

                <label for="date_of_death">Date of Death (DD/MM/YYYY)</label>
                <input type="text" id="date_of_death" name="date_of_death" inputmode="numeric" placeholder="DD/MM/YYYY" maxlength="10" autocomplete="off" required>
                <span id="date_of_death-error" class="field-error"></span>

                <label for="content">Obituary Content</label>
                <textarea id="content" name="content" required></textarea>
                <span id="content-error" class="field-error"></span>

                <label for="author">Author (your name)</label>
                <input type="text" id="author" name="author" maxlength="100" required>
                <span id="author-error" class="field-error"></span>

                <button type="submit" class="btn">Submit Obituary</button>
            </form>
        </div>
    </main>

    <footer class="site-footer">
        &copy; <?php echo date('Y'); ?> Obituary Platform. Built with PHP &amp; MySQL.
    </footer>

    <script src="assets/validate.js"></script>
</body>
</html>
