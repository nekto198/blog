{extends file='layouts/main.tpl'}

{block name='content'}
    <div class="category-page" data-category-ajax>
        {include file='partials/breadcrumbs.tpl'}
        <h1 class="page-title">{$category.name}</h1>
        {if $category.description}
            <p class="lead">{$category.description}</p>
        {/if}

        <div class="toolbar" data-category-toolbar>
            <span class="toolbar__label">Сортировка:</span>
            <a class="toolbar__link{if $sort == 'date'} is-active{/if}"
               href="/{$category.slug}?sort=date">По дате</a>
            <a class="toolbar__link{if $sort == 'views'} is-active{/if}"
               href="/{$category.slug}?sort=views">По просмотрам</a>
        </div>

        <div data-category-content aria-live="polite">
            {if $total == 0}
                <p class="empty">В этой категории пока нет статей.</p>
            {else}
                <div class="posts-grid posts-grid--three">
                    {foreach $posts as $post}
                        {include file='partials/post-card.tpl' post=$post categorySlug=$category.slug}
                    {/foreach}
                </div>
            {/if}

            {if $totalPages > 1}
                <nav class="pagination" aria-label="Пагинация">
                    {if $page > 1}
                        <a href="/{$category.slug}?sort={$sort}&amp;page={$page-1}">← Назад</a>
                    {/if}

                    {for $i=1 to $totalPages}
                        {if $i == $page}
                            <span class="is-current">{$i}</span>
                        {else}
                            <a href="/{$category.slug}?sort={$sort}&amp;page={$i}">{$i}</a>
                        {/if}
                    {/for}

                    {if $page < $totalPages}
                        <a href="/{$category.slug}?sort={$sort}&amp;page={$page+1}">Вперёд →</a>
                    {/if}
                </nav>
            {/if}
        </div>
    </div>
{/block}

{block name='scripts'}
    <script src="/assets/js/category.js?v=1" defer></script>
{/block}
