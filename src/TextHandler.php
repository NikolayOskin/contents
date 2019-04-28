<?php

namespace NikolayOskin\Contents;

final class TextHandler
{
    private $text;
    private $tags;
    private $handledText;
    private $minLength = 1000;
    private $headersParser;

    public function __construct(string $text, array $tags, int $minLength)
    {
        if (!$text) {
            throw new \InvalidArgumentException('Text can not be empty');
        }
        $this->text = $text;
        $this->tags = $this->filterUnusedHeadersTags($tags, $this->text);
        $this->headersParser = new HeadersParser($this->tags, $this->text);
        if ($minLength) {
            $this->minLength = $minLength;
        }
    }

    /**
     * Exctracting provided headers (or default) tags from text
     * @return array
     */
    public function getHeaders() : array
    {
        if ($this->isTextTooShort()) {
            return [];
        }
        return $this->headersParser->getParsedHeaders();
    }

    public function getProcessedText() : string
    {
        if ($this->isTextTooShort()) {
            return $this->text;
        }
        if ($this->handledText) {
            return $this->handledText;
        }
        $this->handledText = $this->addIdAttributesToHeaders();
        return $this->handledText;
    }

    private function addIdAttributesToHeaders() : string
    {
        $tagCount = 0;
        $prevTagLevel = '';
        $handledText = preg_replace_callback(
            $this->getPatternFromTags(),
            function ($matchedHeader) use (&$tagCount, &$prevTagLevel) {
                $this->handleHeader($prevTagLevel, $tagCount, $matchedHeader);
            },
            $this->text
        );
        return $handledText;
    }

    private function handleHeader(&$prevTagLevel, int &$tagCount, array $matchedHeader)
    {
        $currTagLevel =  array_search($matchedHeader[1], $this->tags);
        if ($prevTagLevel === ''|| $currTagLevel - $prevTagLevel <= 1) {
            $tagCount++;
            $prevTagLevel = $currTagLevel;
            return $this->getFormattedHeader($tagCount, $matchedHeader);
        }
        return $matchedHeader[0];
    }

    private function getFormattedHeader(int $tagCount, array $matchedHeader) : string
    {
        return "<{$matchedHeader[1]} " . 'id="header-'
            . $tagCount . '"' . $matchedHeader[2] . '>';
    }

    private function filterUnusedHeadersTags(array $tags, string $text) : array
    {
        $tags = array_filter($tags, function ($tag) use ($text) {
            return preg_match("|<{$tag}([^>]*)>|i", $text);
        });
        return $tags;
    }

    private function isTextTooShort() : bool
    {
        return strlen($this->text) < $this->minLength;
    }

    private function getPatternFromTags() : string
    {
        $patternString = '(' . implode('|', $this->tags) . ')';
        $pattern = "/<{$patternString}([^>]*)>/i";
        return $pattern;
    }
}
