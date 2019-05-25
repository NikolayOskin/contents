<?php

namespace NikolayOskin\Contents;

final class HeadersParser
{
    private $tags;
    private $text;
    private $parsedHeaders = [];

    public function __construct(array $tags, string $text)
    {
        $this->tags = $tags;
        $this->text = $text;
    }

    public function getParsedHeaders()
    {
        $patternString = '(' . implode('|', $this->tags) . ')';
        $pattern = "~<{$patternString}([^>]*)>(.*)</\s*\g1\s*>~i";
        preg_replace_callback($pattern, [$this, 'handleMatchedTag'], $this->text);
        $this->validateHeadersStructure();
        $this->addHeaderId();
        return $this->parsedHeaders;
    }

    /**
     * Callback function. Adding 'levels' to tags. If <h2> tag is presented in
     * text then it will be 'level 0', <h3> tags next to <h2> will be with
     * 'level 1' etc.
     * @param $matchedTag
     */
    private function handleMatchedTag($matchedTag)
    {
        $header = [];
        if (trim($matchedTag[3])) {
            $header['level'] = array_search($matchedTag[1], $this->tags);
            $header['header'] = trim($matchedTag[3]);
            $this->parsedHeaders[] = $header;
        }
        return;
    }

    private function validateHeadersStructure() : void
    {
        foreach ($this->parsedHeaders as $key => $header) {
            $this->deleteUnstructuredHeader($key, $this->parsedHeaders);
        }
    }

    private function deleteUnstructuredHeader(int $key, &$headers) : void
    {
        if ($key > 0 && ($headers[$key]['level'] - $headers[$key - 1]['level'] > 1)) {
            unset($headers[$key]);
        }
    }

    private function addHeaderId()
    {
        foreach ($this->parsedHeaders as $key => &$header) {
            $header['id'] = $key + 1;
        }
    }
}
