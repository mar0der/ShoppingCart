<?php

namespace DH\Mvc\ViewHelpers;


class Form extends BaseView
{
    const TAG_NAME = 'form';
    private $elements = [];

    public function __construct($method = 'POST', $action = '')
    {
        $this->setAttribute('method', $method);
        $this->setAttribute('action', $action);
    }

    public function addElement(IView $element) {
        $this->elements[] = $element;

        return $this;
    }

    public function render()
    {
        $output = '<'.self::TAG_NAME.' '.$this->getAttributesAsString().'>';
        foreach($this->elements as $element) {
            $output .= PHP_EOL . '    '.$element->render();
        }

        $output .= PHP_EOL . '</'.self::TAG_NAME.'>';
        return $output;
    }
}