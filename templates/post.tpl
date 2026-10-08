{extends file='layouts/main.tpl'}

{block name='content'}
    <article class="post-page">
        {if $post.image}
            <div class="post-page__cover">
                <img src="{$post.image}" alt="{$post.title}">
            </div>
        {/if}

        <h1 class="page-title">{$post.title}</h1>

        <div class="post-page__meta">
            <time datetime="{$post.created_at}">{$post.created_at|date_format:"%d.%m.%Y %H:%M"}</time>
            <span>{$post.views} просмотров</span>
            {if $categories|@count > 0}
                <span class="post-page__cats">
                    {foreach $categories as $cat}
                        <a href="/category/{$cat.id}">{$cat.name}</a>{if !$cat@last}, {/if}
                    {/foreach}
                </span>
            {/if}
        </div>

        {if $post.description}
            <p class="lead">{$post.description}</p>
        {/if}

        <div class="post-page__content">{$post.content}</div>
    </article>

    {if $related|@count > 0}
        <section class="related">
            <h2>Похожие статьи</h2>
            <div class="posts-grid">
                {foreach $related as $post}
                    {include file='partials/post-card.tpl' post=$post}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
