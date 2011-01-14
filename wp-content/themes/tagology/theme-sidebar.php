<?php
if (trough_show_sidebar()) : ?>
  <div id="sidebar" class="column <?php trough_styles('sidebar'); ?>" role="complementary">
    <div class="inner"><div class="inner2">
      <ul>
        <?php dynamic_sidebar('Sidebar'); ?>
      </ul>
    </div></div>
  </div>
<?php endif; ?>
