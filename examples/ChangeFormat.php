<?php
$loader = require './vendor/autoload.php';

use \ArrayConverter\TemplateEngineAdapter\TwigAdapter;

$data = [
    'body' => [
        'message' => 'List of users',
        'data' => [
            [
                'id' => 34,
                'firstname' => 'Jack',
                'lastname' => 'O\'Neil',
            ],
            [
                'id' => 105,
                'firstname' => 'Bliss',
                'lastname' => 'Eso',
            ]
        ]
    ]
];

$converter = new \ArrayConverter\ConverterEngine();
$converter
    ->addCommand('iterate', new \ArrayConverter\Command\Iterate())
    ->setTemplateEngine(new TwigAdapter())
    ->setTemplateMap([
        '@iterate(body.data as user)' => [
            'user' => [
                'username' => '{{user.firstname}}.{{user.lastname}}',
                'id' => '{{user.id}}'
            ],
            'meta' => [
                'index' => '{{__original__.index}}'   // Variables defined by the command.
            ]
        ]
    ]);

$result = $converter->render($data);

print_r($result);
