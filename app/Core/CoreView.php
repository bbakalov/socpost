<?php
namespace Bdn\Socpost\Core;

class CoreView
{
    private $data = [];

    public function render($template)
    {
        if (!empty($this->data)) {
            extract($this->data);
        }
        require_once __DIR__ . "/../View/" . $template;
    }

    public function assign($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function getData()
    {
        return $this->data;
    }
}