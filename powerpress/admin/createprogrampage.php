<!--This page is not used -->
<h2>Create Program Page</h2>
<div>
    Page Title: <input type="text" name="Page Title" placeholder="Your Page Title"><br>
    Page Slug: <input type="text" name="Page Slug" placeholder="your-page-title"><br>
</div>
<br>
<button class="" title="Create Program Page" onclick="createPage(<?php echo esc_js($props[$i]['program_id']); ?>, 'Programs')" style="float:left">Create Program Page</button>
