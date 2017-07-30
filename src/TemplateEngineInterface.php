<?php
namespace ArrayConverter;

interface TemplateEngineInterface
{
    function render($context);

    function setTemplate($template): TemplateEngineInterface;
}
