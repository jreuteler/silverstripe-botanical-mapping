<h1>$Action</h1>

<ul>
    <% loop $Projects %>
        <a href="project/edit/$Link">
            <li>
                $Title
            </li>
        </a>
    <% end_loop %>
</ul>

$MyForm