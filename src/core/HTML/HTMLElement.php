<?php

namespace DenisPm\EasyFramework\core\HTML;

use DOMDocument;
use DOMElement;
use DOMException;
use Exception;

class HTMLElement
{
    private DOMDocument $DOMDocument;

    private ?DOMElement $tag = null;

    /**
     * @param array|null $tagData
     * @throws Exception
     */
    public function __construct(?array $tagData = null)
    {
        $this->DOMDocument = new DOMDocument();
        if ($tagData) {
            $this->build($tagData);
            $this->DOMDocument->appendChild($this->tag);
        }
    }

    /**
     * @return ?DOMElement
     */
    public function getTag(): ?DOMElement
    {
        return $this->tag;
    }

    public function getDOMDocument(): DOMDocument
    {
        return $this->DOMDocument;
    }

    /**
     * @param array $tagData
     * @return DOMElement
     * @throws DOMException
     */
    public function build(array $tagData): DOMElement
    {
        $tag = $this->DOMDocument->createElement($tagData[HTMLConstants::TAG]);
        if (!$this->tag) {
            $this->tag = $tag;
        }

        if ($tagData[HTMLConstants::TAG] == 'form' && isset($tagData[HTMLConstants::FORM_NAME])) {
            $nameTag = $this->DOMDocument->createElement('input');
            $nameTag->setAttribute('type', 'hidden');
            $nameTag->setAttribute('name', HTMLConstants::FORM_NAME);
            $nameTag->setAttribute('value', $tagData[HTMLConstants::FORM_NAME]);
            $tag->appendChild($nameTag);
        }

        if (isset($tagData[HTMLConstants::ATTRIBUTES])) {
            foreach ($tagData[HTMLConstants::ATTRIBUTES] as $attribute => $attributeValue) {
                if (!$attribute || !is_string($attribute)) {
                    throw new Exception("Attribute key must be string, for tag '{$tagData[HTMLConstants::TAG]}'");
                }
                if (is_array($attributeValue)) {
                    switch ($attribute) {
                        case 'style':
                            $style = "";
                            foreach ($attributeValue as $property => $propertyValue) {
                                $style .= "{$property}: {$propertyValue};";
                            }
                            $attributeValue = substr($style, 0, -1);
                            break;
                        case 'class':
                            $attributeValue = implode(chr(32), $attributeValue);
                            break;
                        default:
                            throw new Exception("Unknown attribute type '{$attribute}' with array value for tag '{$tagData[HTMLConstants::TAG]}'");
                    }

                }
                if ($attributeValue !== "") {
                    $tag->setAttribute($attribute, $attributeValue);
                }
            }
        }

        if (isset($tagData[HTMLConstants::PATTERN])) {
            $tag->setAttribute("pattern", $tagData[HTMLConstants::PATTERN]);
        }

        if (isset($tagData[HTMLConstants::INNER_HTML])) {
            $tag->append($tagData[HTMLConstants::INNER_HTML]);
        }

        if (isset($tagData[HTMLConstants::CHILDREN])) {
            foreach ($tagData[HTMLConstants::CHILDREN] as $tagSubData) {
                $tag->appendChild($this->build($tagSubData));
            }
        }

        return $tag;
    }

    /**
     * @throws Exception
     */
    public function getHTML(): string
    {
        $html = $this->DOMDocument->saveHTML();
        if ($html === false) {
            throw new Exception("Some error while HTML building");

        }
        return $html;
    }
}
