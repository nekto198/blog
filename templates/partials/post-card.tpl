<article class="post-card">
    {if $post.image}
        <a href="/{$categorySlug}/{$post.slug}" class="post-card__image">
            <img src="{$post.image}" alt="{$post.title}">
        </a>
    {/if}
    <div class="post-card__body">
        <h3 class="post-card__title">
            <a href="/{$categorySlug}/{$post.slug}">{$post.title}</a>
        </h3>
        {if $post.description}
            <p class="post-card__desc">{$post.description}</p>
        {/if}
        <div class="post-card__meta">
            <time datetime="{$post.created_at}">{$post.created_at|date_format:"%d.%m.%Y"}</time>
            <span>{$post.views} просмотров</span>
        </div>
    </div>
</article>
