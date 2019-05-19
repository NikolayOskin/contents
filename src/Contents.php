<?php

namespace NikolayOskin\Contents;

class Contents
{
    private $tags = ['h2', 'h3', 'h4', 'h5', 'h6'];
    private $minLength = 0;
    private $text = '';

    private $textHandler;
    private $tagsValidator;
    private $contentsGenerator;

    public function __construct()
    {
        $this->contentsGenerator = new ContentsFromHeadersGenerator();
        $this->tagsValidator = new TagsValidator();
        $this->textHandler = new TextHandler();
    }

    public function fromText(string $text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * returns handled text with id html attributes added to headers tags or
     * unhandled text if text's length lower than minLength.
     * @param string $text
     * @return string
     */
    public function getHandledText() : string
    {
        return $this->textHandler->getProcessedText($this->text, $this->tags, $this->minLength);
    }

    /**
     * Generates TableOfContents in multi-demensional array.
     * @return array
     */
    public function getContentsArray() : array
    {
        $headers = $this->textHandler->getHeadersFromTextByTags($this->text, $this->tags, $this->minLength);
        return $headers ? $this->contentsGenerator->generateFromHeaders($headers) : [];
    }

    public function setTags(array $tags)
    {
        if (!empty($tags)) {
            $this->tags = $this->tagsValidator->validate($tags);
        }
        return $this;
    }

    public function setMinLength(int $length)
    {
        $this->minLength = $length;
        return $this;
    }
}
