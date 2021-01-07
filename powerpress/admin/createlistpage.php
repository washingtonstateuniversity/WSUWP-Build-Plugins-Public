<!--This page is not used -->
<h2>Create List Page</h2>
<form id="createForm" action="#/" method="POST">
    <div>
        Page Title: <input type="text" name="Page Title" placeholder="Your Page Title"><br>
        Page Slug: <input type="text" name="Page Slug" placeholder="your-page-title"><br>
    </div>
    <br>
    <button class="" title="Create List Page" onclick="createPage(<?php echo esc_js($props[$i]['list_id']); ?>, 'Lists')" style="float:left">Create List Page</button>
</form>