<?php

use NikolayOskin\Contents\Contents;
use PHPUnit\Framework\TestCase;

class TableOfContentsTest extends TestCase
{
    public function test_tags_array_element_has_incorrect_string_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $str = 'Test';
        new Contents($str, ['h1', 'h2', 'h15']);
    }

    public function test_one_of_tags_array_element_has_incorrect_type_exception()
    {
        $this->expectException(InvalidArgumentException::class);
        $str = 'Test';
        new Contents($str, ['h1', 'h2', 15]);
    }

    public function test_returned_text_equals_passed_if_it_less_than_minlength()
    {
        $str = 'Passed text';
        $minLength = 1500;
        $tags = ['h2', 'h3'];
        $contents = new Contents($str, $tags, $minLength);
        $this->assertEquals($str, $contents->getHandledText());
    }
}