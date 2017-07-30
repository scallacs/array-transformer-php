<?php

namespace ArrayConverter;
class TwigTemplateEngineAdapter implements TemplateEngineInterface
{

    public $template;

    public function __construct()
    {
    }
    

    public function setTemplate($template): TemplateEngineInterface
    {
        $this->template = $template;
        return $this;
    }

    public function render($context)
    {
        $loader = new \Twig_Loader_Array(array(
        'index' => $this->template
        ));
        $twig = new \Twig_Environment($loader);
        return $twig->render('index', $context);
    }
}
