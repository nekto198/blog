{extends file='layouts/main.tpl'}

{block name='content'}
    <h1 class="page-title">Категории</h1>

    {foreach $sections as $section}
        <section class="category-section">
            <div class="category-section__header">
                <div>
                    <h2 class="category-section__title">{$section.category.name}</h2>
                    {if $section.category.description}
                        <p class="category-section__desc">{$section.category.description}</p>
                    {/if}
                </div>
                <a class="btn" href="/category/{$section.category.id}">Все статьи</a>
            </div>

            <div class="posts-grid">
                {foreach $section.posts as $post}
                    {include file='partials/post-card.tpl' post=$post}
                {/foreach}
            </div>
        </section>
    {foreachelse}
        <p class="empty">Пока нет опубликованных статей.</p>
    {/foreach}
{/block}
