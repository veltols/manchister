<?php

$directory = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

$tagsToRevert = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div', 'li', 'ul', 'ol', 'nav', 'header', 'footer', 'section', 'article', 'aside'];

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Regex to find premium-button on non-button tags
        // We match <TAG ... class="...premium-button..."
        $content = preg_replace_callback(
            '/(<(' . implode('|', $tagsToRevert) . ')\b[^>]*?class=["\'][^"\']*)\bpremium-button\b([^"\']*["\'])/is',
            function ($matches) {
                // Revert to bg-gradient-to-r
                return $matches[1] . 'bg-gradient-to-r' . $matches[3];
            },
            $content
        );

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Reverted in: " . $file->getPathname() . "\n";
        }
    }
}

echo "Correction complete.\n";
