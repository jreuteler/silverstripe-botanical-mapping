<div class="breadcrumb">
    <% loop $Breadcrumb.Reverse %>

        <% if $Title %>
            <a href="$Link" class="$Class">$Title</a> /
        <% end_if %>

    <% end_loop %>
</div>
