<?php

namespace NikolayOskin\Contents;

class Contents
{
    private $tags = ['h2', 'h3'];
    private $textHandler;
    private $tagsValidator;
    private $contentsGenerator;
    private $text;

    public function __construct(string $text, array $tags = [], int $minLength = 0)
    {
        $this->contentsGenerator = new ContentsFromHeadersGenerator();
        $this->tagsValidator = new TagsValidator();
        $this->text = $text;
        if (!empty($tags)) {
            $this->tags = $this->tagsValidator->validate($tags);
        }
        $this->textHandler = new TextHandler($this->text, $this->tags, $minLength);
    }

    /**
     * returns handled text with id html attributes added to headers tags or
     * unhandled text if text's length lower than minLength.
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
        return $headers ? $this->contentsGenerator->generateFromHeaders($headers) : [];
    }
}
