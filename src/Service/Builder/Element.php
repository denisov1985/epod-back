<?php

namespace App\Service\Builder;

use JetBrains\PhpStorm\ArrayShape;

class Element implements ElementInterface
{
    /**
     * @param string $tag
     * @param array $input
     * @param array $children
     */
    public function __construct(
        private string $tag,
        private array $input = [],
        private array $children = []
    )
    {
    }

    /**
     * @return array
     */
    #[ArrayShape(['tag' => "string", 'input' => "array", 'children' => "array"])]
    public function toArray(): array
    {
        return [
            'tag' => $this->tag,
            'input' => $this->input,
            'children' => array_map(function (ElementInterface $child) {
                return $child->toArray();
            }, $this->children),
        ];
    }

    /**
     * @return string
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * @param int $index
     * @return Element
     */
    public function getChild(int $index): Element
    {
        return $this->children[$index];
    }

    /**
     * @param Element $element
     * @return $this
     */
    public function appendChild(Element $element): static
    {
        $this->children[] = $element;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     * @return $this
     */
    public function setProp(string $key, string $value): static
    {
        $this->input['props'][$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @return string|array
     */
    public function getProp(string $key): string|array
    {
        return $this->input['props'][$key];
    }
}