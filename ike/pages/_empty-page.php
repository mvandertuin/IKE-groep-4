<?php
//Empty example page
global $scripts;
//add names of JS files to $scripts array if needed

//Include helper functions for generating an HTML page
useLib('htmlpage');

//generate page header
fw_header('page_title');

//the page itself
?>
<h2>Content</h2>
<p>Add something here...</p>
<?php
//generate page footer
fw_footer();
?>