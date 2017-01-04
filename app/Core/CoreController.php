<?php
namespace Bdn\Socpost\Core;

class CoreController
{
    public $view;

    public function __construct()
    {
        $this->view = new CoreView();
    }
}