<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{if $pageTitle}{$pageTitle} — {/if}{$appName}</title>
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="/" class="logo">{$appName}</a>
            <nav>
                <a href="/">Главная</a>
            </nav>
        </div>
    </header>

    <main class="site-main">
        <div class="container">
            {block name="content"}{/block}
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; {$smarty.now|date_format:"%Y"} {$appName}</p>
        </div>
    </footer>
</body>
</html>
