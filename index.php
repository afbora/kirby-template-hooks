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
    ],
    'user' => [
        'changeEmail',
        'changeLanguage',
        'changeName',
        'changePassword',
        'changeRole',
        'changeSecret', // Kirby 6
        'changeTotp',   // Kirby 5
        'create',
        'createAvatar',
        'delete',
        'deleteAvatar',
        'replaceAvatar',
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
                            'file' => $model->template() ?? 'default',
                            'page' => $model->intendedTemplate()->name(),
                            'user' => $model->role()->name()
                        };

                        // apply the hook like core does, so that
                        // template hooks can return a modified model
                        return $this->apply($type . '.' . $template . '.' . $action . ':' . $state, $arguments);
                    }
                }
            };
        }
    }
}

Kirby::plugin('afbora/template-hooks', [
    'hooks' => $hooks
]);
