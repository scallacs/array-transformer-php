<?php

namespace ArrayConverter\Test;
use \ArrayConverter\TemplateEngineAdapter\TwigAdapter;
use PHPUnit\Framework\TestCase;

class ConverterEngineTest extends TestCase
{
    public function testConvert()
    {

        $converter = new \ArrayConverter\ConverterEngine();
        $converter
        ->addCommand('iterate', new \ArrayConverter\Command\Iterate())
        ->setTemplateEngine(new TwigAdapter())
        ->setTemplateMap([
            'message' => 'Hello {{name}} {{user.0.firstname}}',
            'from_fct' => function (array $data) {
                return 'Do whatever you want with data';
            },
            'users' => [
                0 => 'Hello all: {% for user in users %} {{ user.firstname }} {{ user.lastname }} {% endfor %}',
            ],
            'nested' => [
                'other' => [
                    'value' => '{{name}}'
                ]
            ],
            'user_2' => [
                '@iterate(users as user)' => [
                    'nested' => '{{hello_msg}} {{user.firstname }} {{user.lastname }}',
                    'recursivity' => [
                        '@iterate(users as user)' => '{{user.firstname }} {{user.lastname }}'
                    ]
                ]
            ]
        ]);

        echo '<pre>';
        print_r($converter->render([
            'hello_msg' => 'Hello',
            'name' => 'Stephy',
            'users' => [
                ['firstname' => 'Albert', 'lastname' => 'Enstein'],
                ['firstname' => 'Torstein', 'lastname' => 'Horgmo']
            ]
        ]));
    }

    
    public function testInvalidCommand()
    {
        $this->expectException(\ArrayConverter\Exception\InvalidCommandException::class);

        $converter = new \ArrayConverter\ConverterEngine();
        $converter
        ->setTemplateEngine(new TwigAdapter())
        ->setTemplateMap([
            '@invalidCmd()' => ''
        ]);
        $converter->render([]);
    }
}
