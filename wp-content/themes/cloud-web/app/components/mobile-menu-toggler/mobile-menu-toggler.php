<?php
wp_register_component('Mobile Menu Toggler', function($data) {
?>
    <button
        id="mobile-menu-toggler"
        class="js-mobile-menu-toggler"
        title="Open menu"
        aria-haspopup="true"
        aria-label="Main menu Button. Press Enter or Space to toggle menu and navigate using Arrow Keys. Escape key closes the menu and Tab jumps to next item."
        aria-controls="mobile-menu"
        aria-expanded="false"
        >
        <span class="icon open"><?php print_svg($data['open_icon']) ?></span>
        <span class="icon close"><?php print_svg($data['close_icon']) ?></span>
    </button>
<?php
}, [
    'open_icon' => '',
    'close_icon' => ''
]);