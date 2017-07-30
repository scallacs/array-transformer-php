<?php

namespace ArrayConverter;

/**
 * Description of Converter
 *
 * @author Stephane
 */
class ConverterEngine
{

    /**
    * @var TemplateEngineInterfaceInterface
    */
    protected $templateEngine;

    protected $templateMap;

    protected $commands = [];
    
    function __construct()
    {
    }

    public function addCommand($id, $cmd)
    {
        $cmd->setConverterEngine($this);
        $this->commands[$id] = $cmd;
        return $this;
    }

    public function setTemplateMap(array $map): ConverterEngine
    {
        $this->templateMap = $map;
        return $this;
    }

    public function setTemplateEngine(TemplateEngineInterface $templateEngine): ConverterEngine
    {
        $this->templateEngine = $templateEngine;
        return $this;
    }
    /**
     * return array
     */
    public function render(array $data): array
    {
        return $this->_render($data, $this->templateMap);
    }

    public function _render(array $data, array $templateMap): array
    {
        $results = [];
        foreach ($templateMap as $key => $template) {
            // echo "Parsing key: ", $key, '\r\n';
            if ($key[0] === '@') { // TODO cst
                // Extracting cmd id
                $i = 1;
                while ($i < strlen($key) && $key[$i] != '(') {
                    $i++;
                }
                $cmdId = substr($key, 1, $i-1);
                if (!isset($this->commands[$cmdId])){
                    throw new \ArrayConverter\Exception\InvalidCommandException($cmdId);
                }
                // Extracting parameters
                $params = $this->extractParameters(\substr($key, $i+1));
                $this->commands[$cmdId]->eval($results, $params, $data, $template);
            } else {
                if (is_array($template)) {
                    $results[$key] = $this->_render($data, $template);
                } elseif (is_callable($template)) {
                    $results[$key] = $template($data);
                } else {
                    $results[$key] = $this->templateEngine->setTemplate($template)->render($data);
                }
            }
            // $results[$key] = $this->templateEngine->render($data);
        }
        return $results;
    }

    public function extractParameters($str){
        $results = [];
        $i = 0;
        while ($i < strlen($str) && $str[$i] != ')'){
            $value = '';
            while ($i < strlen($str) && $str[$i] != ',' && $str[$i] != ')'){
                $value .= $str[$i];
                $i++;
            }
            $results[] = $value;
        }
        return $results;
    }
}
