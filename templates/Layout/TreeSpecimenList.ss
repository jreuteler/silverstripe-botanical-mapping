<h1> Tree specimen </h1>
<ul>
    <% loop $Records %>
        <li>
            <a href="{$Top.ControllerPath}/{$Link}">
                $Species.Title
            </a>
        </li>
    <% end_loop %>
</ul>

