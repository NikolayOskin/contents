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
        $this->tags = $this->filterHeadersTags($tags, $this->text);
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

    /**
     * @return null|string|string[]
     */
    public function getProcessedText() : string
    {
        if ($this->isTextTooShort()) {
            return $this->text;
        }
        if ($this->handledText) {
            return $this->handledText;
        }
        $this->handledText = $this->addIdsToHeaders();
        return $this->handledText;
    }

    /**
     * @return null|string|string[]
     */
    private function addIdsToHeaders()
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

    /**
     * @param $prevTagLevel
     * @param int $tagCount
     * @param $matchedHeader
     * @return string
     */
    private function handleHeader(&$prevTagLevel, int &$tagCount, $matchedHeader)
    {
        $currTagLevel =  array_search($matchedHeader[1], $this->tags);
        if ($prevTagLevel === ''|| $currTagLevel - $prevTagLevel <= 1) {
            $tagCount++;
            $prevTagLevel = $currTagLevel;
            return $this->getFormattedHeader($tagCount, $matchedHeader);
        }
        return $matchedHeader[0];
    }

    /**
     * @param int $tagCount
     * @param $matchedHeader
     * @return string
     */
    private function getFormattedHeader(int $tagCount, $matchedHeader)
    {
        return "<{$matchedHeader[1]} " . 'id="header-'
            . $tagCount . '"' . $matchedHeader[2] . '>';
    }

    /**
     * @param array $tags
     * @param string $text
     * @return array
     */
    private function filterHeadersTags(array $tags, string $text) : array
    {
        $tags = array_filter($tags, function ($tag) use ($text) {
            return preg_match("|<{$tag}([^>]*)>|i", $text);
        });
        return $tags;
    }

    /**
     * @return bool
     */
    private function isTextTooShort() : bool
    {
        return strlen($this->text) < $this->minLength;
    }

    /**
     * @return string
     */
    private function getPatternFromTags() : string
    {
        $patternString = '(' . implode('|', $this->tags) . ')';
        $pattern = "/<{$patternString}([^>]*)>/i";
        return $pattern;
    }
}
