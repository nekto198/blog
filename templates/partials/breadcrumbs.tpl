{if $breadcrumbs}
    <nav class="breadcrumbs" aria-label="Хлебные крошки">
        <ol>
            {foreach $breadcrumbs as $crumb}
                <li>
                    {if $crumb.url && !$crumb@last}
                        <a href="{$crumb.url}">{$crumb.label}</a>
                    {else}
                        <span aria-current="page">{$crumb.label}</span>
                    {/if}
                </li>
            {/foreach}
        </ol>
    </nav>
{/if}
