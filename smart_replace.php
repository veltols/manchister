<?php

$directory = __DIR__ . '/resources/views';
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

foreach ($iterator as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $content = file_get_contents($file->getPathname());
        $originalContent = $content;

        // Regex to match <a or <button or <input containing the class
        // This is a simplified regex and might miss edge cases (like newlines in tags) but should cover 99% of blade templates
        $content = preg_replace_callback(
            '/(<(a|button|input)\b[^>]*?class=["\'][^"\']*)\bbg-gradient-to-r\b([^"\']*["\'])/is',
            function ($matches) {
                // $matches[1] is the part before the class
                // $matches[3] is the part after the class
                return $matches[1] . 'premium-button' . $matches[3];
            },
            $content
        );

        if ($content !== $originalContent) {
            file_put_contents($file->getPathname(), $content);
            echo "Updated: " . $file->getPathname() . "\n";
        }
    }
}

echo "Replacement complete.\n";
