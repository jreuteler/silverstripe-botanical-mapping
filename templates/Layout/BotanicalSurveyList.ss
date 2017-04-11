<h1> Mapping survey </h1>
<ul>
    <% loop $Records %>
        <li>
            <a href="{$Top.ControllerPath}/{$Link}">
                $Title
            </a>
        </li>
    <% end_loop %>
</ul>

