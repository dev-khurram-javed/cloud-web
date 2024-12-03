(function ($) {
    $(document).ready(function () {
        // Allow only one level of submenus
        var maxDepth = 1;

        // Set global Depth to 1 Levels
        if (typeof wpNavMenu !== 'undefined') {
            wpNavMenu.options.globalMaxDepth = maxDepth;
        }
    });
})(jQuery);