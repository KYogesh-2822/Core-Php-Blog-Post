<?php
// This captures the page content and renders the full layout

function renderLayout($pageTitle, $contentCallback) {
    ob_start();
    $contentCallback();
    $content = ob_get_clean();
    
    include __DIR__ . '/header.php';
    echo $content;
    include __DIR__ . '/footer.php';
}