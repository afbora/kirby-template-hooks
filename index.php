<?php

use Kirby\Cms\App as Kirby;
use Kirby\Cms\File;
use Kirby\Cms\Page;
use Kirby\Filesystem\File as BaseFile;

Kirby::plugin('afbora/template-hooks', [
    'hooks' => [
        'file.changeName:after' => function (File $newFile, File $oldFile) {
            Kirby::instance()->trigger('file.' . $newFile->template() . '.changeName:after', compact('newFile', 'oldFile'));
        },

        'file.changeName:before' => function (File $file, string $name) {
            Kirby::instance()->trigger('file.' . $file->template() . '.changeName:before', compact('file', 'name'));
        },

        'file.changeSort:after' => function (File $newFile, File $oldFile) {
            Kirby::instance()->trigger('file.' . $newFile->template() . '.changeSort:after', compact('newFile', 'oldFile'));
        },

        'file.changeSort:before' => function (File $file, int $position) {
            Kirby::instance()->trigger('file.' . $file->template() . '.changeSort:before', compact('file', 'position'));
        },

        'file.create:after' => function (File $file) {
            Kirby::instance()->trigger('file.' . $file->template() . '.create:after', compact('file'));
        },

        'file.create:before' => function (File $file, BaseFile $upload) {
            Kirby::instance()->trigger('file.' . $file->template() . '.create:before', compact('file', 'upload'));
        },

        'file.delete:after' => function (bool $status, File $file) {
            Kirby::instance()->trigger('file.' . $file->template() . '.delete:after', compact('status', 'file'));
        },

        'file.delete:before' => function (File $file) {
            Kirby::instance()->trigger('file.' . $file->template() . '.delete:before', compact('file'));
        },

        'file.replace:after' => function (File $newFile, File $oldFile) {
            Kirby::instance()->trigger('file.' . $newFile->template() . '.replace:after', compact('newFile', 'oldFile'));
        },

        'file.replace:before' => function (File $file, BaseFile $upload) {
            Kirby::instance()->trigger('file.' . $file->template() . '.replace:before', compact('file', 'upload'));
        },

        'file.update:after' => function (File $newFile, File $oldFile) {
            Kirby::instance()->trigger('file.' . $newFile->template() . '.update:after', compact('newFile', 'oldFile'));
        },

        'file.update:before' => function (File $file, ?array $input) {
            Kirby::instance()->trigger('file.' . $file->template() . '.update:before', compact('file', 'input'));
        },

        'page.changeNum:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.changeNum:after', compact('newPage', 'oldPage'));
        },

        'page.changeNum:before' => function (Page $page, int $num) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.changeNum:before', compact('page', 'num'));
        },

        'page.changeSlug:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.changeSlug:after', compact('newPage', 'oldPage'));
        },

        'page.changeSlug:before' => function (Page $page, string $slug, ?string $languageCode = null) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.changeSlug:before', compact('page', 'slug', 'languageCode'));
        },

        'page.changeStatus:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.changeStatus:after', compact('newPage', 'oldPage'));
        },

        'page.changeStatus:before' => function (Page $page, string $status, ?int $position = null) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.changeStatus:before', compact('page', 'status', 'position'));
        },

        'page.changeTemplate:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.changeTemplate:after', compact('newPage', 'oldPage'));
        },

        'page.changeTemplate:before' => function (Page $page, string $template) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.changeTemplate:before', compact('page', 'template'));
        },

        'page.changeTitle:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.changeTitle:after', compact('newPage', 'oldPage'));
        },

        'page.changeTitle:before' => function (Page $page, string $title) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.changeTitle:before', compact('page', 'title'));
        },

        'page.create:after' => function (Page $page) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.create:after', compact('page'));
        },

        'page.create:before' => function (Page $page, array $input) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.create:before', compact('page', 'input'));
        },

        'page.delete:after' => function (bool $status, Page $page) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.delete:after', compact('status', 'page'));
        },

        'page.delete:before' => function (Page $page, bool $force) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.delete:before', compact('page', 'force'));
        },

        'page.duplicate:after' => function (Page $duplicatePage, Page $originalPage) {
            Kirby::instance()->trigger('page.' . $duplicatePage->intendedTemplate()->name() . '.duplicate:after', compact('duplicatePage', 'originalPage'));
        },

        'page.duplicate:before' => function (Page $originalPage, array $input, array $options) {
            Kirby::instance()->trigger('page.' . $originalPage->intendedTemplate()->name() . '.duplicate:before', compact('originalPage', 'input', 'options'));
        },

        'page.update:after' => function (Page $newPage, Page $oldPage) {
            Kirby::instance()->trigger('page.' . $newPage->intendedTemplate()->name() . '.update:after', compact('newPage', 'oldPage'));
        },

        'page.update:before' => function (Page $page, array $values, array $strings) {
            Kirby::instance()->trigger('page.' . $page->intendedTemplate()->name() . '.update:before', compact('page', 'values', 'strings'));
        }
    ]
]);
