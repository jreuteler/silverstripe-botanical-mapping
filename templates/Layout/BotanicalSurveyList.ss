<h1> Mapping survey </h1>
<% include BreadCrumb %>

<ul>
    <% loop $Records %>
        <li>
            <a href="{$EditLink}">
                $Title
            </a>
        </li>
    <% end_loop %>
</ul>

