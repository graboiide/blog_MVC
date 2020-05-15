<?php

namespace App\Src\Service\Renderer;

interface RendererInterface
{
    public function render($path, $params = []);

    public function addGlobal($key, $value);
}