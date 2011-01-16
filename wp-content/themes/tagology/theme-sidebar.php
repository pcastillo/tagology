<?php
if (tagology_show_sidebar()) : ?>
  <div id="sidebar" role="complementary">
    <div class="inner"><div class="inner2">
      <ul>
        <?php dynamic_sidebar('Sidebar'); ?>
      </ul>
    </div></div>
  </div>
<?php endif; ?>
