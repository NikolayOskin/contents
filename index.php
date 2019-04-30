<?php

require_once __DIR__ . '/vendor/autoload.php';
include __DIR__ . '/example/text.php';
include __DIR__ . '/templates/contents.php';

$tableOfContents = new \NikolayOskin\Contents\Contents($text, ['h2', 'h3', 'h4'], 20);
$handledText = $tableOfContents->getHandledText();
$contentsArray = $tableOfContents->getContents();

?>

<html lang="en">
<body>
<?php
getContentsHTMLTemplate($contentsArray);
echo $handledText;
?>
</body>
</html>




