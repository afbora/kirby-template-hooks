<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\Event;
use Kirby\Cms\ModelWithContent;

$actions = [
    'file' => [
        'changeName',
        'changeSort',
        'changeTemplate',
        'create',
        'delete',
        'replace',
        'update'
    ],
    'page' => [
        'changeNum',
        'changeSlug',
        'changeStatus',
        'changeTemplate',
        'changeTitle',
        'create',
        'delete',
        'duplicate',
        'move',
        'update'
    ]
];

$hooks = [];

foreach ($actions as $type => $names) {
    foreach ($names as $action) {
        foreach (['before', 'after'] as $state) {
            $hooks[$type . '.' . $action . ':' . $state] = function (Event $event) use ($type, $action, $state) {
                $arguments = $event->arguments();

                foreach ($arguments as $model) {
                    if ($model instanceof ModelWithContent && $model::CLASS_ALIAS === $type) {
                        $template = match ($type) {
                            'file' => $model->template(),
                            'page' => $model->intendedTemplate()->name()
                        };

                        $this->trigger($type . '.' . $template . '.' . $action . ':' . $state, $arguments);
                        break;
                    }
                }
            };
        }
    }
}

Kirby::plugin('afbora/template-hooks', [
    'hooks' => $hooks
]);
