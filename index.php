<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('afbora/kirby-template-hooks', [
    'hooks' => [
        'file.changeName:after' => function ($newFile, $oldFile) {
            $template = $newFile->template();
            kirby()->trigger("file.{$template}.changeName:after", $newFile, $oldFile);
        },

        'file.changeName:before' => function ($file, $name) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.changeName:before", $file, $name);
        },

        'file.changeSort:after' => function ($newFile, $oldFile) {
            $template = $newFile->template();
            kirby()->trigger("file.{$template}.changeSort:after", $newFile, $oldFile);
        },

        'file.changeSort:before' => function ($file, $position) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.changeSort:before", $file, $position);
        },

        'file.create:after' => function ($file) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.create:after", $file);
        },

        'file.create:before' => function ($file, $upload) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.create:before", $file, $upload);
        },

        'file.delete:after' => function ($status, $file) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.delete:after", $status, $file);
        },

        'file.delete:before' => function ($file) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.delete:before", $file);
        },

        'file.replace:after' => function ($newFile, $oldFile) {
            $template = $newFile->template();
            kirby()->trigger("file.{$template}.replace:after", $newFile, $oldFile);
        },

        'file.replace:before' => function ($file, $upload) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.replace:before", $file, $upload);
        },

        'file.update:after' => function ($newFile, $oldFile) {
            $template = $newFile->template();
            kirby()->trigger("file.{$template}.update:after", $newFile, $oldFile);
        },

        'file.update:before' => function ($file, $input) {
            $template = $file->template();
            kirby()->trigger("file.{$template}.update:before", $file, $input);
        },

        'page.changeNum:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.changeNum:after", $newPage, $oldPage);
        },

        'page.changeNum:before' => function ($page, $num) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.changeNum:before", $page, $num);
        },

        'page.changeSlug:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.changeSlug:after", $newPage, $oldPage);
        },

        'page.changeSlug:before' => function ($page, $slug) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.changeSlug:before", $page, $slug);
        },

        'page.changeStatus:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.changeStatus:after", $newPage, $oldPage);
        },

        'page.changeStatus:before' => function ($page, $status, $position) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.changeStatus:before", $page, $status, $position);
        },

        'page.changeTemplate:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.changeTemplate:after", $newPage, $oldPage);
        },

        'page.changeTemplate:before' => function ($page, $template) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.changeTemplate:before", $page, $template);
        },

        'page.changeTitle:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.changeTitle:after", $newPage, $oldPage);
        },

        'page.changeTitle:before' => function ($page, $title) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.changeTitle:before", $page, $title);
        },

        'page.create:after' => function ($page) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.create:after", $page);
        },

        'page.create:before' => function ($parent, $input) {
            $template = $parent->intendedTemplate();
            kirby()->trigger("page.{$template}.create:before", $parent, $input);
        },

        'page.delete:after' => function ($status, $page) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.delete:after", $status, $page);
        },

        'page.delete:before' => function ($page) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.delete:before", $page);
        },

        'page.duplicate:after' => function ($duplicatePage) {
            $template = $duplicatePage->intendedTemplate();
            kirby()->trigger("page.{$template}.duplicate:after", $duplicatePage);
        },

        'page.duplicate:before' => function ($originalPage, $input) {
            $template = $originalPage->intendedTemplate();
            kirby()->trigger("page.{$template}.duplicate:before", $originalPage, $input);
        },

        'page.update:after' => function ($newPage, $oldPage) {
            $template = $newPage->intendedTemplate();
            kirby()->trigger("page.{$template}.update:after", $newPage, $oldPage);
        },

        'page.update:before' => function ($page, $values, $strings) {
            $template = $page->intendedTemplate();
            kirby()->trigger("page.{$template}.update:before", $page, $values, $strings);
        }
    ]
]);
