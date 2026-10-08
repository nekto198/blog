<aside class="sidebar" aria-label="Боковая панель">
    <a class="sidebar-banner" href="/">
        <img src="/assets/img/sidebar-banner.jpg" alt="Читайте блог">
        <span class="sidebar-banner__overlay">
            <span class="sidebar-banner__eyebrow">Подборка</span>
            <span class="sidebar-banner__title">Лучшие материалы недели</span>
            <span class="sidebar-banner__text">Свежие статьи о технологиях, путешествиях, книгах и спорте</span>
            <span class="sidebar-banner__cta">На главную</span>
        </span>
    </a>

    <section class="sidebar-block">
        <h2 class="sidebar-block__title">Популярное</h2>
        <ol class="sidebar-popular">
            {foreach $sidebarPopular as $item}
                <li>
                    <a href="/{$item.category_slug}/{$item.slug}">
                        <span class="sidebar-popular__index">{$item@iteration}</span>
                        <span class="sidebar-popular__body">
                            <span class="sidebar-popular__name">{$item.title}</span>
                            <span class="sidebar-popular__meta">{$item.views} просмотров</span>
                        </span>
                    </a>
                </li>
            {/foreach}
        </ol>
    </section>

    <section class="sidebar-block">
        <h2 class="sidebar-block__title">Разделы</h2>
        <ul class="sidebar-cats">
            {foreach $menuCategories as $menuCategory}
                <li>
                    <a href="/{$menuCategory.slug}"{if $activeCategorySlug == $menuCategory.slug} class="is-active"{/if}>
                        {$menuCategory.name}
                        <span>{$menuCategory.posts_count}</span>
                    </a>
                </li>
            {/foreach}
        </ul>
    </section>
</aside>
