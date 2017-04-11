<h1> Specimen status </h1>
<ul>
    <% loop $Records %>
        <li>
            <a href="{$Top.ControllerPath}/{$Link}">
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

