<?php

require_once __DIR__ . '/vendor/autoload.php';

$tableOfContents = new \NikolayOskin\Contents\Contents($text, ['h2', 'h3', 'h4'], 20);
$contents = $tableOfContents->getContents();

require_once __DIR__ . '/example/text.php';

function contents($contents)
{
    echo '<ul>';
    foreach ($contents as $header) {
        echo '<li>'.$header['header'].'</li>';
        if ($header['childs']) {
            contents($header['childs']);
            echo '</ul>';
        }
    }
}
