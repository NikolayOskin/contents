<?php

use NikolayOskin\Contents\Contents;
use PHPUnit\Framework\TestCase;

class TableOfContentsTest extends TestCase
{
    public function test_tags_array_element_has_incorrect_string_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $str = 'Test';
        $contents = new Contents();
        $contents->fromText($str)->setTags(['h1', 'h2', 'h15']);
    }

    public function test_one_of_tags_array_element_has_incorrect_type_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $str = 'Test';
        $contents = new Contents();
        $contents->fromText($str)->setTags(['h1', 'h2', 15]);
    }

    public function test_returned_text_equals_passed_if_it_less_than_minlength()
    {
        $str = 'Passed text';
        $minLength = 1500;
        $tags = ['h2', 'h3', 'h4', 'h5'];
        $contents = new Contents();
        $contents->fromText($str)->setTags($tags)->setMinLength($minLength);
        $this->assertEquals($str, $contents->getHandledText());
    }

    public function test_id_attributes_are_properly_added_to_text_headers()
    {
        $textsArray = include __DIR__ . './../testdata/texts.php';
        $inputText = $textsArray['bigText'];
        $outputText = $textsArray['handledBigText'];
        $contents = new Contents();
        $contents->fromText($inputText)->setMinLength(100);
        $this->assertEquals($outputText, $contents->getHandledText());
    }
}