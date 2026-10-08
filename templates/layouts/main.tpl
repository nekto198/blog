<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{if $pageTitle}{$pageTitle} — {/if}{$appName}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Source+Serif+4:opsz,wght@8..60,400;8..60,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/main.css?v=3">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <a href="/" class="logo">{$appName}</a>

            <button
                type="button"
                class="nav-toggle"
                aria-expanded="false"
                aria-controls="site-nav"
                aria-label="Открыть меню"
            >
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
                <span class="nav-toggle__bar"></span>
            </button>

            <nav id="site-nav" class="site-nav">
                <a href="/"{if $isHome} class="is-active" aria-current="page"{/if}>Главная</a>
                {foreach $menuCategories as $menuCategory}
                    <a href="/{$menuCategory.slug}"{if $activeCategorySlug == $menuCategory.slug} class="is-active" aria-current="page"{/if}>{$menuCategory.name}</a>
                {/foreach}
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

    <script>
        (function () {
            var toggle = document.querySelector('.nav-toggle');
            var nav = document.getElementById('site-nav');
            if (!toggle || !nav) return;

            toggle.addEventListener('click', function () {
                var open = !nav.classList.contains('is-open');
                nav.classList.toggle('is-open', open);
                toggle.classList.toggle('is-open', open);
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                toggle.setAttribute('aria-label', open ? 'Закрыть меню' : 'Открыть меню');
            });

            nav.querySelectorAll('a').forEach(function (link) {
                link.addEventListener('click', function () {
                    nav.classList.remove('is-open');
                    toggle.classList.remove('is-open');
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.setAttribute('aria-label', 'Открыть меню');
                });
            });
        })();
    </script>
</body>
</html>
