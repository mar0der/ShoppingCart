<?php
/**
 * Created by PhpStorm.
 * User: Hadzhiew
 * Date: 3.10.2015 ã.
 * Time: 22:17
 */

namespace DH\Mvc\ViewHelpers;


abstract class BaseView implements IView
{
    protected $attributes;


    public function getAttribute($name)
    {
        return $this->attributes[$name];
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;

        return $this;
    }

    protected function getAttributesAsString()
    {
        $result = '';
        foreach ($this->attributes as $name => $value) {
            $result .= $name . ($value !== null ? '="' . $value . '" ' : '');
        }

        return trim($result);
    }

    public abstract function render();
}