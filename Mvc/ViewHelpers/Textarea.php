<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 3.10.2015 ã.
 * Time: 22:36
 */

namespace DH\Mvc\ViewHelpers;


class Textarea extends BaseView
{
    const TAG_NAME = 'textarea';
    private $value;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function render()
    {
        $output = '<'.self::TAG_NAME.' '.$this->getAttributesAsString().'>'.$this->value.'</'.self::TAG_NAME.'>';

        return $output;
    }
}