{extends file='layouts/main.tpl'}

{block name='content'}
    <article class="post-page">
        {include file='partials/breadcrumbs.tpl'}

        {if $post.image}
            <div class="post-page__cover">
                <img src="{$post.image}" alt="{$post.title}">
            </div>
        {/if}

        <h1 class="page-title">{$post.title}</h1>

        <div class="post-page__meta">
            <time datetime="{$post.created_at}">{$post.created_at|date_format:"%d.%m.%Y %H:%M"}</time>
            <span>{$post.views} просмотров</span>
            {foreach $categories as $cat}
                {if $cat@first}<span class="post-page__cats">{/if}
                <a href="/{$cat.slug}">{$cat.name}</a>{if !$cat@last}, {/if}
                {if $cat@last}</span>{/if}
            {/foreach}
        </div>

        {if $post.description}
            <p class="lead">{$post.description}</p>
        {/if}

        <div class="content-with-sidebar post-page__body">
            <div class="content-with-sidebar__main">
                <div class="post-page__content">{$post.content nofilter}</div>
            </div>

            {include file='partials/sidebar.tpl'}
        </div>
    </article>

    {if $prevPost || $nextPost}
        <nav class="post-adjacent" aria-label="Статьи в категории">
            {if $prevPost}
                <a class="post-adjacent__link post-adjacent__link--prev" href="/{$category.slug}/{$prevPost.slug}">
                    <span class="post-adjacent__arrow" aria-hidden="true">←</span>
                    <span class="post-adjacent__text">
                        <span class="post-adjacent__label">Предыдущая</span>
                        <span class="post-adjacent__title">{$prevPost.title}</span>
                    </span>
                </a>
            {else}
                <span class="post-adjacent__link post-adjacent__link--empty"></span>
            {/if}

            {if $nextPost}
                <a class="post-adjacent__link post-adjacent__link--next" href="/{$category.slug}/{$nextPost.slug}">
                    <span class="post-adjacent__text">
                        <span class="post-adjacent__label">Следующая</span>
                        <span class="post-adjacent__title">{$nextPost.title}</span>
                    </span>
                    <span class="post-adjacent__arrow" aria-hidden="true">→</span>
                </a>
            {else}
                <span class="post-adjacent__link post-adjacent__link--empty"></span>
            {/if}
        </nav>
    {/if}

    {foreach $related as $relatedPost}
        {if $relatedPost@first}
            <section class="related">
                <h2>Похожие статьи</h2>
                <div class="posts-grid posts-grid--three">
        {/if}
        {include file='partials/post-card.tpl' post=$relatedPost categorySlug=$relatedPost.category_slug}
        {if $relatedPost@last}
                </div>
            </section>
        {/if}
    {/foreach}
{/block}
