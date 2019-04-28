<?php

namespace NikolayOskin\Contents;

class Contents
{
    private $tags = ['h2', 'h3'];
    private $textHandler;
    private $contentsGenerator;
    private $text;

    public function __construct(string $text, array $tags = [], int $minLength = 0)
    {
        $this->text = $text;
        $this->contentsGenerator = new ContentsFromHeadersGenerator();
        if (!empty($tags)) {
            $this->tags = (new TagsValidator())->validate($tags);
        }
        $this->textHandler = new TextHandler($this->text, $this->tags, $minLength);
    }

    /**
     * returns handled text with id attributes added to headers tags or
     * unhandled text if text's length lower than minLength.
     * @return null|string|string[]
     */
    public function getHandledText()
    {
        return $this->textHandler->getProcessedText();
    }

    /**
     * Generates TableOfContents in multi-demensional array.
     * @return array
     */
    public function getContents()
    {
        $headers = $this->textHandler->getHeaders();
        print_r($headers);
        return $headers ? $this->contentsGenerator->generateFromHeaders($headers) : [];
    }
}
