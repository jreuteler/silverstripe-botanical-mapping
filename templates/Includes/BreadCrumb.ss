<div class="breadcrumb">
    <% loop $Breadcrumb.Reverse %>
        <a href="$Link" class="$Class">$Title</a> /
    <% end_loop %>
</div>
