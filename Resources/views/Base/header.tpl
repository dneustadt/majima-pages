{inherits "Base/header.tpl"}

{block "header-navigation"}
    {$dwoo.parent}
    {template menu data admin=false}
        {foreach $data entry}
            <li>
                <a href="{url "index_page" array('p' => $entry.id)}">
                    {$entry.name}
                    {if $admin}
                        <span
                            data-edit-page="true"
                            data-add-url="{url "admin_add_page"}"
                            data-edit-url="{url "admin_update_page"}"
                            data-delete-url="{url "admin_delete_page"}"
                            data-id="{$entry.id}"
                            class="button button-small">
                                <span class="typcn typcn-pencil"></span>
                        </span>
                    {/if}
                </a>
                {if $entry.children}
                    <ul class="header-navigation">
                        {menu $entry.children $admin}
                    </ul>
                {/if}
            </li>
        {/foreach}
    {/template}
    <ul class="header-navigation">
        {block "header-navigation-home"}
            <li>
                <a href="{url "index_index"}">Home</a>
            </li>
        {/block}
        {block "header-navigation-pages"}
            {menu $pages $.admin}
        {/block}
        {if $.admin}
            <li>
                <span data-add-page="true" data-target="{url "admin_add_page"}" data-parent-id="0" class="button"><span class="typcn typcn-plus"></span></span>
            </li>
        {/if}
    </ul>
{/block}