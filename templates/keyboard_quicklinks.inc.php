<nav id="skip-link" role="navigation">
  <ul role="menubar">
    
    <?php if (drupal_is_front_page()) { ?>
      <li><a role="menuitem" href="#front-video" class="element-invisible element-focusable"><?php print t('Jump to Video'); ?></a></li> 
      <li><a role="menuitem" href="#block-block-366" class="element-invisible element-focusable"><?php print t('Jump to Content'); ?></a></li>
    <?php } else { ?>
      <li><a role="menuitem" href="#page-content" class="element-invisible element-focusable"><?php print t('Jump to Content'); ?></a></li>
    <?php } ?>
    <?php if ( isset($page['sidebar_first']) && count($page['sidebar_first']) > 0) { ?>
    <li><a role="menuitem" href="#sidebar-first" class="element-invisible element-focusable"><?php print t('Jump to First Sidebar'); ?></a></li>
    <?php }; if ( isset($page['sidebar_second']) && count($page['sidebar_second']) > 0) { ?>
    <li><a role="menuitem" href="#sidebar-second" class="element-invisible element-focusable"><?php print t('Jump to Second Sidebar'); ?></a></li>
    <?php } ?>
    <li><a role="menuitem" href="#footer" class="element-invisible element-focusable"><?php print t('Jump to Footer'); ?></a></li>
  </ul>
</nav>