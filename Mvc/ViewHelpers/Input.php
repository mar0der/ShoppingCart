<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 3.10.2015 ã.
 * Time: 22:16
 */

namespace DH\Mvc\ViewHelpers;


class Input extends BaseView
{
    const TAG_NAME = 'input';
    public function __construct($type, $name)
    {
        $this->setAttribute('type', $type);
        $this->setAttribute('name', $name);
    }

    public function render()
    {
        $output = '<'.self::TAG_NAME.' '.$this->getAttributesAsString().' />';
        return $output;
    }
}