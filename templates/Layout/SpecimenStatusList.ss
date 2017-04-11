<h1> Specimen status </h1>
<% include BreadCrumb %>

<ul>
    <% loop $Records %>
        <li>
            <a href="{$EditLink}">
                $Specimen.Title
                $Date
                $TotalHeight
                $CrownHeight
                $Diameter
                $Comment
            </a>
        </li>
    <% end_loop %>
</ul>

