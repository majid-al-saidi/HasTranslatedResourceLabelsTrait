<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Columns\ImageColumn;

trait HasTranslatedResourceLabels
{
    protected static function getTranslationKeyPrefix(): string
    {
        $modelName = class_basename(static::getModel());
        return 'resources.' . Str::kebab(Str::pluralStudly($modelName));
    }

    public static function transField(string $field): string
    {
        return __(static::getTranslationKeyPrefix() . ".fields.{$field}");
    }

    public static function transPage(string $page, string $key = 'title'): string
    {
        return __(static::getTranslationKeyPrefix() . ".pages.{$page}.{$key}");
    }

    public static function transNav(string $key = 'label'): string
    {
        return __(static::getTranslationKeyPrefix() . ".navigation.{$key}");
    }

    public static function getLabel(): string
    {
        return __(static::getTranslationKeyPrefix() . '.label');
    }

    public static function getPluralLabel(): string
    {
        return __(static::getTranslationKeyPrefix() . '.plural');
    }

    public static function getNavigationLabel(): string
    {
        return static::transNav();
    }

    public static function __callStatic($method, $args)
    {
        if (str_starts_with($method, '__')) {
            $component = substr($method, 2);
            $field = $args[0] ?? 'field';

            $map = [
                'TextInput' => TextInput::class,
                'Textarea' => Textarea::class,
                'Select' => Select::class,
                'DatePicker' => DatePicker::class,
                'Toggle' => Toggle::class,
                'FileUpload' => FileUpload::class,
                'TextColumn' => TextColumn::class,
                'SelectColumn' => SelectColumn::class,
                'ToggleColumn' => ToggleColumn::class,
                'ImageColumn' => ImageColumn::class,
            ];

            if (!array_key_exists($component, $map)) {
                throw new \Exception("Unsupported translated component: {$component}");
            }

            if (
                str_contains($component, 'Column') &&
                ! str_contains($field, '.') &&
                method_exists(static::getModel(), $field)
            ) {
                logger()->warning("[Filament Translation] It looks like '{$field}' is a relationship. Did you mean '{$field}.name'?");
            }

            return $map[$component]::make($field)
                ->label(static::transField($field));
        }

        throw new \BadMethodCallException("Method {$method} does not exist.");
    }
}
