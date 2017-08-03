<?php
namespace ArrayConverter\TemplateEngineAdapter;

interface AdapterInterface
{
    function render($context);

    function setTemplate($template);
}
