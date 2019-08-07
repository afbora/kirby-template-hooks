<?php

use Kirby\Cms\App as Kirby;

Kirby::plugin('afbora/kirby-template-hooks', [
    'hooks' => [
        'file.changeName:after' => function ($newFile, $oldFile) {
            try {
                $template = $newFile->template();
                kirby()->trigger("file.{$template}.changeName:after", $newFile, $oldFile);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.changeName:before' => function ($file, $name) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.changeName:before", $file, $name);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.changeSort:after' => function ($newFile, $oldFile) {
            try {
                $template = $newFile->template();
                kirby()->trigger("file.{$template}.changeSort:after", $newFile, $oldFile);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.changeSort:before' => function ($file, $position) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.changeSort:before", $file, $position);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.create:after' => function ($file) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.create:after", $file);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.create:before' => function ($file, $upload) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.create:before", $file, $upload);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.delete:after' => function ($status, $file) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.delete:after", $status, $file);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.delete:before' => function ($file) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.delete:before", $file);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.replace:after' => function ($newFile, $oldFile) {
            try {
                $template = $newFile->template();
                kirby()->trigger("file.{$template}.replace:after", $newFile, $oldFile);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.replace:before' => function ($file, $upload) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.replace:before", $file, $upload);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.update:after' => function ($newFile, $oldFile) {
            try {
                $template = $newFile->template();
                kirby()->trigger("file.{$template}.update:after", $newFile, $oldFile);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'file.update:before' => function ($file, $input) {
            try {
                $template = $file->template();
                kirby()->trigger("file.{$template}.update:before", $file, $input);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeNum:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.changeNum:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeNum:before' => function ($page, $num) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.changeNum:before", $page, $num);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeSlug:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.changeSlug:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeSlug:before' => function ($page, $slug) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.changeSlug:before", $page, $slug);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeStatus:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.changeStatus:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeStatus:before' => function ($page, $status, $position = null) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.changeStatus:before", $page, $status, $position);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeTemplate:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.changeTemplate:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeTemplate:before' => function ($page, $template) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.changeTemplate:before", $page, $template);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeTitle:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.changeTitle:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.changeTitle:before' => function ($page, $title) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.changeTitle:before", $page, $title);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.create:after' => function ($page) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.create:after", $page);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.create:before' => function ($page, $input) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.create:before", $page, $input);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.delete:after' => function ($status, $page) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.delete:after", $status, $page);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.delete:before' => function ($page) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.delete:before", $page);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.duplicate:after' => function ($duplicatePage) {
            try {
                $template = $duplicatePage->intendedTemplate();
                kirby()->trigger("page.{$template}.duplicate:after", $duplicatePage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.duplicate:before' => function ($originalPage, $input) {
            try {
                $template = $originalPage->intendedTemplate();
                kirby()->trigger("page.{$template}.duplicate:before", $originalPage, $input);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.update:after' => function ($newPage, $oldPage) {
            try {
                $template = $newPage->intendedTemplate();
                kirby()->trigger("page.{$template}.update:after", $newPage, $oldPage);
            }
            catch (Exception $e) {
                throw $e;
            }
        },

        'page.update:before' => function ($page, $values, $strings) {
            try {
                $template = $page->intendedTemplate();
                kirby()->trigger("page.{$template}.update:before", $page, $values, $strings);
            }
            catch (Exception $e) {
                throw $e;
            }
        }
    ]
]);
