<?php

namespace DH\Mvc\ViewHelpers;


class Dropdown implements IView
{
    const TAG_NAME = 'select';
    private $options;
    private $attributes;
    private $selectedOption;

    public function __construct($name, $options = [])
    {
        $this->name = $name;
        $this->options = array_map(function($ar) {
            $res = [];
            foreach($ar as $row) {
                $res[] = $row;
            }

            return $res;
        }, $options);
    }

    public function setSelectedOption($value)
    {
        $this->selectedOption = $value;

        return $this;
    }

    public function __get($name)
    {
        return $this->attributes[$name];
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    protected function getAttributesAsString()
    {
        $result = '';
        foreach ($this->attributes as $name => $value) {
            $result .= $name . ($value !== null ? '="' . $value . '" ' : '');
        }

        return trim($result);
    }

    public function render()
    {
        $output = '<' . self::TAG_NAME . ' ' . $this->getAttributesAsString() . '>';
        foreach ($this->options as $option) {

            $output .= PHP_EOL . '    <option value="'.$option[0].'" ';
            if($this->selectedOption == $option[0]) {
                $output .= 'selected ';
            }

            $output .= '>' . $option[1] . '</option>';
        }

        $output .= PHP_EOL . '</' . self::TAG_NAME . '>';
        return $output;
    }
}