<?php

namespace Afbora\TemplateHooks\Tests;

use Closure;
use Kirby\Cms\App;
use Kirby\Cms\Event;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Cms\User;
use Kirby\Filesystem\Dir;
use Kirby\Filesystem\F;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TemplateHooksTest extends TestCase
{
    protected string $root;
    protected array $events = [];

    protected function setUp(): void
    {
        $this->root = sys_get_temp_dir() . '/kirby-template-hooks-' . bin2hex(random_bytes(4));

        // `App::destroy()` also removes all registered plugins
        if (App::plugin('afbora/template-hooks') === null) {
            require __DIR__ . '/../index.php';
        }
    }

    protected function tearDown(): void
    {
        $this->events = [];

        App::destroy();

        // models and the app reference each other, so free
        // them before the next test creates a new app
        gc_collect_cycles();
        Dir::remove($this->root);
    }

    protected function app(array $hooks = [], bool $languages = false, bool $record = true): App
    {
        $code = $languages === true ? '.en' : '';
        $png  = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');

        F::write($this->root . '/content/parent/parent' . $code . '.txt', 'Title: Parent');
        F::write($this->root . '/content/parent/1_note/note' . $code . '.txt', 'Title: Note');
        F::write($this->root . '/content/parent/1_note/image.png', $png);
        F::write($this->root . '/content/parent/1_note/image.png' . $code . '.txt', 'Template: image');
        F::write($this->root . '/content/parent/1_note/untemplated.png', $png);
        F::write($this->root . '/content/parent/_drafts/draft/note' . $code . '.txt', 'Title: Draft');
        F::write($this->root . '/content/other/parent' . $code . '.txt', 'Title: Other');
        F::write($this->root . '/upload.png', $png);

        if ($record === true) {
            $events = &$this->events;
            $recorder = function (Event $event) use (&$events) {
                $events[] = [$event->name(), $event->arguments()];
            };

            $hooks['*:before'][] = $recorder;
            $hooks['*:after'][]  = $recorder;
        }

        $props = [
            'roots' => [
                'index' => $this->root
            ],
            'blueprints' => [
                'pages/parent' => [
                    'sections' => [
                        'pages' => ['type' => 'pages', 'templates' => ['note', 'album']]
                    ]
                ],
                'pages/note' => [
                    'options'  => ['changeTemplate' => ['note', 'album']],
                    'sections' => [
                        'images' => ['type' => 'files', 'template' => 'image'],
                        'docs'   => ['type' => 'files', 'template' => 'doc']
                    ]
                ],
                'pages/album'  => ['title' => 'Album'],
                'files/image'  => ['title' => 'Image'],
                'files/doc'    => ['title' => 'Doc'],
                'users/editor' => ['name' => 'editor', 'title' => 'Editor'],
                'users/client' => ['name' => 'client', 'title' => 'Client']
            ],
            'hooks' => $hooks
        ];

        if ($languages === true) {
            $props['options']['languages'] = true;
            $props['languages'] = [
                ['code' => 'en', 'default' => true],
                ['code' => 'de']
            ];
        }

        $app = new App($props);
        $app->impersonate('kirby');

        // the last admin and the last user cannot be deleted
        foreach (['admin', 'editor'] as $role) {
            User::create([
                'email'    => $role . '@example.com',
                'password' => 'correct-horse-battery',
                'role'     => $role
            ]);
        }

        $this->events = [];

        return $app;
    }

    protected function skipUnsupportedAction(): void
    {
        if (
            str_contains($this->dataName(), 'Avatar') === true &&
            method_exists(User::class, 'createAvatar') === false
        ) {
            $this->markTestSkipped('The avatar actions need Kirby 5.4 or newer');
        }
    }

    /**
     * Names of the fired template events, e.g. `page.note.update:after`
     */
    protected function templateEvents(): array
    {
        $names = array_column($this->events, 0);
        $names = array_filter($names, fn ($name) => substr_count(explode(':', $name)[0], '.') > 1);

        return array_values($names);
    }

    protected function arguments(string $name): array
    {
        foreach ($this->events as [$event, $arguments]) {
            if ($event === $name) {
                return $arguments;
            }
        }

        $this->fail('The event ' . $name . ' was not fired');
    }

    /**
     * Checks the fired template events and that each one got
     * exactly the same arguments as the core event
     *
     * @param array $expected template event => core event
     */
    protected function assertTemplateEvents(array $expected): void
    {
        $this->assertSame(array_keys($expected), $this->templateEvents());

        foreach ($expected as $template => $core) {
            $arguments = $this->arguments($template);
            $match     = false;

            foreach ($this->events as [$name, $coreArguments]) {
                if ($name === $core && $coreArguments === $arguments) {
                    $match = true;
                    break;
                }
            }

            $this->assertTrue($match, $template . ' did not get the same arguments as ' . $core);
        }
    }

    /**
     * Template event => core event for the before and after state,
     * with the events of nested actions in between
     */
    protected static function events(string $type, string $template, string $action, array $nested = []): array
    {
        return [
            $type . '.' . $template . '.' . $action . ':before' => $type . '.' . $action . ':before',
            ...$nested,
            $type . '.' . $template . '.' . $action . ':after'  => $type . '.' . $action . ':after'
        ];
    }

    public static function pageActionProvider(): array
    {
        // publishing a page also changes its sorting number
        $publish = static::events('page', 'note', 'changeStatus', static::events('page', 'note', 'changeNum'));

        return [
            'page create' => [
                fn (App $app) => $app->page('parent')->createChild(['slug' => 'new', 'template' => 'note']),
                static::events('page', 'note', 'create')
            ],
            'page update' => [
                fn (App $app) => $app->page('parent/note')->update(['text' => 'Text']),
                static::events('page', 'note', 'update')
            ],
            'page changeTitle' => [
                fn (App $app) => $app->page('parent/note')->changeTitle('New title'),
                static::events('page', 'note', 'changeTitle')
            ],
            'page changeSlug' => [
                fn (App $app) => $app->page('parent/note')->changeSlug('new-slug'),
                static::events('page', 'note', 'changeSlug')
            ],
            'page changeNum' => [
                fn (App $app) => $app->page('parent/note')->changeNum(2),
                static::events('page', 'note', 'changeNum')
            ],
            'page changeStatus listed' => [
                fn (App $app) => $app->page('parent')->draft('draft')->changeStatus('listed'),
                $publish
            ],
            'page changeStatus unlisted' => [
                fn (App $app) => $app->page('parent/note')->changeStatus('unlisted'),
                $publish
            ],
            'page changeStatus draft' => [
                fn (App $app) => $app->page('parent/note')->changeStatus('draft'),
                static::events('page', 'note', 'changeStatus')
            ],
            'page changeTemplate' => [
                fn (App $app) => $app->page('parent/note')->changeTemplate('album'),
                [
                    'page.note.changeTemplate:before' => 'page.changeTemplate:before',
                    'page.album.changeTemplate:after' => 'page.changeTemplate:after'
                ]
            ],
            'page duplicate' => [
                fn (App $app) => $app->page('parent/note')->duplicate('copy'),
                static::events('page', 'note', 'duplicate')
            ],
            'page move' => [
                fn (App $app) => $app->page('parent/note')->move($app->page('other')),
                static::events('page', 'note', 'move')
            ],
            'page delete' => [
                fn (App $app) => $app->page('parent/note')->delete(),
                static::events('page', 'note', 'delete', [
                    ...static::events('file', 'image', 'delete'),
                    ...static::events('file', 'default', 'delete')
                ])
            ]
        ];
    }

    public static function fileActionProvider(): array
    {
        return [
            'file create' => [
                fn (App $app) => File::create([
                    'parent'   => $app->page('parent/note'),
                    'source'   => $app->root('index') . '/upload.png',
                    'filename' => 'new.png',
                    'template' => 'image'
                ]),
                static::events('file', 'image', 'create')
            ],
            'file update' => [
                fn (App $app) => $app->file('parent/note/image.png')->update(['alt' => 'Alt']),
                static::events('file', 'image', 'update')
            ],
            'file changeName' => [
                fn (App $app) => $app->file('parent/note/image.png')->changeName('renamed'),
                static::events('file', 'image', 'changeName')
            ],
            'file changeSort' => [
                fn (App $app) => $app->file('parent/note/image.png')->changeSort(2),
                static::events('file', 'image', 'changeSort')
            ],
            'file replace' => [
                fn (App $app) => $app->file('parent/note/image.png')->replace($app->root('index') . '/upload.png'),
                static::events('file', 'image', 'replace')
            ],
            'file changeTemplate' => [
                fn (App $app) => $app->file('parent/note/image.png')->changeTemplate('doc'),
                [
                    'file.image.changeTemplate:before' => 'file.changeTemplate:before',
                    'file.doc.changeTemplate:after'    => 'file.changeTemplate:after'
                ]
            ],
            'file delete' => [
                fn (App $app) => $app->file('parent/note/image.png')->delete(),
                static::events('file', 'image', 'delete')
            ],
            'file create without template' => [
                fn (App $app) => File::create([
                    'parent'   => $app->page('parent/note'),
                    'source'   => $app->root('index') . '/upload.png',
                    'filename' => 'new.png'
                ]),
                static::events('file', 'default', 'create')
            ],
            'file update without template' => [
                fn (App $app) => $app->file('parent/note/untemplated.png')->update(['alt' => 'Alt']),
                static::events('file', 'default', 'update')
            ]
        ];
    }

    public static function userActionProvider(): array
    {
        $user   = fn (App $app) => $app->user('editor@example.com');
        $avatar = fn (App $app) => $app->root('index') . '/upload.png';

        return [
            'user create' => [
                fn (App $app) => User::create(['email' => 'client@example.com', 'password' => 'correct-horse-battery', 'role' => 'client']),
                static::events('user', 'client', 'create')
            ],
            'user update' => [
                fn (App $app) => $user($app)->update(['city' => 'Berlin']),
                static::events('user', 'editor', 'update')
            ],
            'user changeEmail' => [
                fn (App $app) => $user($app)->changeEmail('new@example.com'),
                static::events('user', 'editor', 'changeEmail')
            ],
            'user changeLanguage' => [
                fn (App $app) => $user($app)->changeLanguage('de'),
                static::events('user', 'editor', 'changeLanguage')
            ],
            'user changeName' => [
                fn (App $app) => $user($app)->changeName('Editor'),
                static::events('user', 'editor', 'changeName')
            ],
            'user changePassword' => [
                fn (App $app) => $user($app)->changePassword('correct-horse-battery'),
                static::events('user', 'editor', 'changePassword')
            ],
            'user changeRole' => [
                fn (App $app) => $user($app)->changeRole('client'),
                [
                    'user.editor.changeRole:before' => 'user.changeRole:before',
                    'user.client.changeRole:after'  => 'user.changeRole:after'
                ]
            ],
            'user createAvatar' => [
                fn (App $app) => $user($app)->createAvatar($avatar($app), 'png'),
                static::events('user', 'editor', 'createAvatar', static::events('file', 'avatar', 'create'))
            ],
            'user replaceAvatar' => [
                fn (App $app) => $user($app)->replaceAvatar($avatar($app), 'png'),
                static::events('user', 'editor', 'replaceAvatar', static::events('file', 'avatar', 'replace')),
                fn (App $app) => $user($app)->createAvatar($avatar($app), 'png')
            ],
            'user deleteAvatar' => [
                fn (App $app) => $user($app)->deleteAvatar(),
                static::events('user', 'editor', 'deleteAvatar', static::events('file', 'avatar', 'delete')),
                fn (App $app) => $user($app)->createAvatar($avatar($app), 'png')
            ],
            'user delete' => [
                fn (App $app) => $user($app)->delete(),
                static::events('user', 'editor', 'delete')
            ]
        ];
    }

    public static function languageActionProvider(): array
    {
        return [
            'page update' => [
                fn (App $app) => $app->page('parent/note')->update(['text' => 'Text'], 'de'),
                static::events('page', 'note', 'update')
            ],
            'page changeTitle' => [
                fn (App $app) => $app->page('parent/note')->changeTitle('Notiz', 'de'),
                static::events('page', 'note', 'changeTitle')
            ],
            'page changeSlug' => [
                fn (App $app) => $app->page('parent/note')->changeSlug('notiz', 'de'),
                static::events('page', 'note', 'changeSlug')
            ],
            'file update' => [
                fn (App $app) => $app->file('parent/note/image.png')->update(['alt' => 'Alt'], 'de'),
                static::events('file', 'image', 'update')
            ]
        ];
    }

    #[DataProvider('pageActionProvider')]
    #[DataProvider('fileActionProvider')]
    #[DataProvider('userActionProvider')]
    public function testAction(Closure $action, array $expected, Closure|null $prepare = null): void
    {
        $this->skipUnsupportedAction();

        $app = $this->app();

        if ($prepare !== null) {
            $prepare($app);
            $this->events = [];
        }

        $action($app);

        $this->assertTemplateEvents($expected);
    }

    #[DataProvider('pageActionProvider')]
    #[DataProvider('fileActionProvider')]
    #[DataProvider('userActionProvider')]
    public function testActionWithoutTemplateHooks(Closure $action, array $expected, Closure|null $prepare = null): void
    {
        $this->skipUnsupportedAction();

        $app = $this->app(record: false);

        if ($prepare !== null) {
            $prepare($app);
        }

        $this->assertNotNull($action($app));
    }

    #[DataProvider('languageActionProvider')]
    public function testLanguageAction(Closure $action, array $expected): void
    {
        $app = $this->app(languages: true);

        $action($app);

        $this->assertTemplateEvents($expected);
        $this->assertSame('de', $this->arguments(array_key_first($expected))['languageCode']);
    }

    public function testBeforeHookCanReturnModifiedModel(): void
    {
        $app = $this->app([
            'page.note.changeTitle:before' => fn (Page $page) => $page->clone(['num' => 77])
        ]);

        $page = $app->page('parent/note')->changeTitle('New title');

        $this->assertSame(77, $page->num());
        $this->assertSame('New title', $page->title()->value());
    }

    public function testAfterHookCanReturnModifiedModel(): void
    {
        $app = $this->app([
            'page.note.changeTitle:after' => fn (Page $newPage) => $newPage->clone(['num' => 99])
        ]);

        $page = $app->page('parent/note')->changeTitle('New title');

        $this->assertSame(99, $page->num());
    }

    public function testHookWithoutReturnValue(): void
    {
        $calls = 0;

        $app = $this->app([
            'page.note.changeTitle:after' => function () use (&$calls) {
                $calls++;
            }
        ]);

        $page = $app->page('parent/note')->changeTitle('New title');

        $this->assertSame(1, $calls);
        $this->assertSame('New title', $page->title()->value());
    }

    public function testEventArgument(): void
    {
        $name = null;

        $app = $this->app([
            'page.note.update:after' => function (Event $event) use (&$name) {
                $name = $event->name();
            }
        ]);

        $app->page('parent/note')->update(['text' => 'Text']);

        $this->assertSame('page.note.update:after', $name);
    }

    public function testTemplateHookCanRunTheSameAction(): void
    {
        $calls = 0;

        $app = $this->app([
            'page.note.update:after' => function (Page $newPage) use (&$calls) {
                $calls++;
                $newPage->update(['text' => 'Nested']);
            }
        ]);

        $app->page('parent/note')->update(['text' => 'Text']);

        $this->assertSame(1, $calls);
    }

    public function testOtherEventsAreNotForwarded(): void
    {
        $app = $this->app([
            'page.note.render:after' => fn () => 'Template hook'
        ]);

        $html = $app->apply('page.render:after', [
            'contentType' => 'html',
            'data'        => [],
            'html'        => 'HTML',
            'page'        => $app->page('parent/note')
        ], 'html');

        $this->assertSame('HTML', $html);
        $this->assertSame([], $this->templateEvents());
    }

    public function testAllCoreActionsAreForwarded(): void
    {
        $app   = $this->app();
        $hooks = array_keys(App::plugin('afbora/template-hooks')->extends()['hooks']);
        $files = [
            'file' => 'FileActions.php',
            'page' => 'PageActions.php',
            'user' => 'UserActions.php'
        ];

        foreach ($files as $type => $file) {
            preg_match_all("/commit\(\s*(?:action:\s*)?'([a-zA-Z]+)'/", F::read($app->root('kirby') . '/src/Cms/' . $file), $matches);

            $this->assertNotEmpty($matches[1]);

            // `update` is defined in `ModelWithContent`
            foreach ([...$matches[1], 'update'] as $action) {
                $this->assertContains($type . '.' . $action . ':before', $hooks);
                $this->assertContains($type . '.' . $action . ':after', $hooks);
            }
        }
    }
}
