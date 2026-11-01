# Kirby Template Hooks

Kirby template hooks allow you to manage hooks for page and file templates and user roles more easily.

Instead of checking the template in every `page.update:after` hook, you can register a hook only for the pages with the `product` template: `page.product.update:after`.

## Requirements

- Kirby 5 or 6
- PHP 8.2 or newer

For Kirby 3 and 4, use version 1.x.

## Installation

### Installation with composer

```ssh
composer require afbora/kirby-template-hooks
```

### Add as git submodule

```ssh
git submodule add https://github.com/afbora/kirby-template-hooks.git site/plugins/kirby-template-hooks
```

## Usage

### Pages

**Usage:** `page.TEMPLATE_NAME.ACTION_NAME:before/after`

```php
<?php

return [
    'hooks' => [
        'page.product.update:after' => function ($newPage, $oldPage) {
            // your code goes here
        }
    ]
];

```

The template name is the intended template of the page, i.e. the name of its content file (`product.txt`).

### Files

**Usage:** `file.TEMPLATE_NAME.ACTION_NAME:before/after`

```php
<?php

return [
    'hooks' => [
        'file.cover.update:after' => function ($newFile, $oldFile) {
            // your code goes here
        }
    ]
];

```

The template name is the `template` field of the file. Files without a template use `default`, like their `files/default` blueprint:

```php
'file.default.create:after' => function ($file) {
    // runs for uploads without a file template
}
```

User avatars are files with the `avatar` template, so `file.avatar.create:after` runs for new avatars.

### Users

**Usage:** `user.ROLE_NAME.ACTION_NAME:before/after`

```php
<?php

return [
    'hooks' => [
        'user.editor.create:after' => function ($user) {
            // your code goes here
        }
    ]
];

```

## Arguments

Template hooks receive exactly the same arguments as the core hooks, e.g. `$page`, `$values`, `$strings` and `$languageCode` for `page.update:before`. You can find them in the [hooks reference](https://getkirby.com/docs/reference/plugins/hooks). The `$event` argument is the template event (`page.product.update:after`).

Like core hooks, template hooks can return a modified model to change the result of the action:

```php
'page.product.create:before' => function ($page) {
    return $page->clone([
        'content' => [
            ...$page->content()->toArray(),
            'sku' => uniqid()
        ]
    ]);
}
```

## Actions

| Type | Actions |
| --- | --- |
| `page` | `changeNum`, `changeSlug`, `changeStatus`, `changeTemplate`, `changeTitle`, `create`, `delete`, `duplicate`, `move`, `update` |
| `file` | `changeName`, `changeSort`, `changeTemplate`, `create`, `delete`, `replace`, `update` |
| `user` | `changeEmail`, `changeLanguage`, `changeName`, `changePassword`, `changeRole`, `changeSecret` (Kirby 6), `changeTotp` (Kirby 5), `create`, `createAvatar` (Kirby 5.4+), `delete`, `deleteAvatar` (Kirby 5.4+), `replaceAvatar` (Kirby 5.4+), `update` |

`changeTemplate` and `changeRole` run the `before` hook for the old template or role and the `after` hook for the new one:

```php
'page.product.changeTemplate:before' => function ($page, $template) {
    // a product page becomes another page
},
'page.product.changeTemplate:after' => function ($newPage, $oldPage) {
    // a page became a product page
}
```

## Upgrading from 1.x

- Template hooks get all arguments of the core hook. The names of the arguments didn't change, so existing hooks keep working.
- Values returned by template hooks are now used like the ones of core hooks. Before, they were ignored.
- Files without a template run `file.default.*` hooks instead of `file..*`.
- New: `page.move`, `file.changeTemplate` and user role hooks.
