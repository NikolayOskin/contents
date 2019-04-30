<?php

function getContentsHTMLTemplate($contents)
{
    echo '<ul>';
    foreach ($contents as $header) {
        echo '<li><a href="#">' . $header['header'] . '</a></li>';
        if ($header['childs']) {
            getContentsHTMLTemplate($header['childs']);
        }
    }
    echo '</ul>';
}