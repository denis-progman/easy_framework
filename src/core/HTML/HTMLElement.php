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
     * @param ?array $tagData
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

    public function getDOMDocument(): DOMDocument
    {
        return $this->DOMDocument;
    }

    /**
     * @return DOMElement
     */
    public function getTag(): DOMElement
    {
        return $this->tag;
    }

    /**
     * @param array $tagData
     * @return self
     * @throws DOMException
     * @throws Exception
     */
    public function build(array $tagData): DOMElement
    {
        $tag = $this->DOMDocument->createElement($tagData[HTMLConstants::TAG]);
        if (!$this->tag) {
            $this->tag = $tag;
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
