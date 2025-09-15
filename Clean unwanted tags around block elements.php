/**
 * Clean unwanted <b> tags and duplicate <ul> tags (even with attributes)
 */

function custom_content_cleanup($content) {
    // --- Fix unwanted <b> tags ---
    $content = preg_replace('/<b>\s*(<(p|ul|ol|div|section|article|aside|header|footer|nav|h[1-6]|table)[^>]*>)/i', '$1', $content);
    $content = preg_replace('/(<\/(p|ul|ol|div|section|article|aside|header|footer|nav|h[1-6]|table)>)\s*<\/b>/i', '$1', $content);
    $content = preg_replace('/<b>\s*<\/b>/i', '', $content);

    // --- Fix duplicate/nested <ul> ---
    // Collapse <ul><ul> → <ul>, even if first has attributes
    $content = preg_replace('/<ul([^>]*)>\s*<ul([^>]*)>/i', '<ul$1>', $content);

    // Collapse </ul></ul> → </ul>
    $content = preg_replace('/<\/ul>\s*<\/ul>/i', '</ul>', $content);

    // --- Extra safeguard: multiple levels in a row ---
    while (preg_match('/<ul([^>]*)>\s*<ul([^>]*)>/i', $content)) {
        $content = preg_replace('/<ul([^>]*)>\s*<ul([^>]*)>/i', '<ul$1>', $content);
    }
    while (preg_match('/<\/ul>\s*<\/ul>/i', $content)) {
        $content = preg_replace('/<\/ul>\s*<\/ul>/i', '</ul>', $content);
    }

    return $content;
}

// 1. FRONTEND: Cleanup on output
add_filter('the_content', 'custom_content_cleanup');

// 2. EDITOR: Cleanup before saving (Gutenberg + Classic Editor)
add_filter('content_save_pre', 'custom_content_cleanup');
