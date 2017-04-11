<h1> Tree specimen </h1>
<% include BreadCrumb %>

<ul>
    <% loop $Records %>
        <li>
            <a href="{$EditLink}">
                $Species.Title
            </a>
        </li>
    <% end_loop %>
</ul>

