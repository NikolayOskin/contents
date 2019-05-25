<?php

namespace com\bitshammer\NikolayOskin\Contents;

final class TextHandler
{
    private $tags;
    private $headersParser;

    /**
     * Exctracting provided headers (or default) tags from text
     * @param string $text
     * @param array $tags
     * @param int $minLength
     * @return array
     */
    public function getHeadersFromTextByTags(string $text, array $tags, int $minLength) : array
    {
        if ($this->isTextTooShort($text, $minLength)) {
            return [];
        }
        $tags = $this->filterUnusedHeadersTags($tags, $text);
        $this->headersParser = new HeadersParser($tags, $text);
        return $this->headersParser->getParsedHeaders();
    }

    public function getProcessedText(string $text, array $tags, int $minLength) : string
    {
        if ($this->isTextTooShort($text, $minLength)) {
            return $text;
        }
        $this->tags = $this->filterUnusedHeadersTags($tags, $text);
        return $this->addIdAttributesToHeadersInText($text);
    }

    private function addIdAttributesToHeadersInText(string $text) : string
    {
        $tagCount = 0;
        $prevTagLevel = '';
        $handledText = preg_replace_callback(
            $this->getPatternFromTags(),
            function ($matchedHeader) use (&$tagCount, &$prevTagLevel) {
                return $this->handleHeader($prevTagLevel, $tagCount, $matchedHeader);
            },
            $text
        );
        return $handledText;
    }

    private function handleHeader(&$prevTagLevel, int &$tagCount, array $matchedHeader)
    {
        $currTagLevel =  array_search($matchedHeader[1], $this->tags);
        if ($prevTagLevel === '' || $currTagLevel - $prevTagLevel <= 1) {
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

    private function isTextTooShort(string $text, int $minLength) : bool
    {
        return strlen($text) < $minLength;
    }

    private function getPatternFromTags() : string
    {
        $patternString = '(' . implode('|', $this->tags) . ')';
        $pattern = "/<{$patternString}([^>]*)>/i";
        return $pattern;
    }
}
