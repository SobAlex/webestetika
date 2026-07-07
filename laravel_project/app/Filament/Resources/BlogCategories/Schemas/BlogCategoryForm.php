<?php

namespace App\Filament\Resources\BlogCategories\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class BlogCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('slug', \Illuminate\Support\Str::slug($state));
                    }),
                TextInput::make('slug')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (callable $set, $state) => $set('slug', \Illuminate\Support\Str::slug($state))),
                Textarea::make('description')
                    ->columnSpanFull(),
                ColorPicker::make('color')
                    ->required()
                    ->default('#06b6d4')
                    ->hex()
                    ->formatStateUsing(fn ($state) => $state ?: '#06b6d4')
                    ->live()
                    ->helperText('Выберите цвет для категории блога'),
                TextInput::make('icon')
                    ->required()
                    ->default('article')
                    ->live()
                    ->helperText('Введите название иконки Material Icons (например: article, business, star)')
                    ->placeholder('article'),
                Placeholder::make('icon_preview')
                    ->label('Превью иконки')
                    ->content(function ($get) {
                        $icon = $get('icon') ?: 'article';
                        $color = $get('color') ?: '#06b6d4';

                        return new \Illuminate\Support\HtmlString(
                            '<div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg border border-gray-200">' .
                            '<div class="inline-flex items-center justify-center w-10 h-10 rounded-lg shadow-sm border" style="background-color: ' . $color . '20; border-color: ' . $color . '40;">' .
                            '<i class="material-icons text-xl" style="color: ' . $color . '">' . $icon . '</i>' .
                            '</div>' .
                            '<div class="flex flex-col">' .
                            '<span class="text-sm font-medium text-gray-900">' . $icon . '</span>' .
                            '<span class="text-xs text-gray-500">' . $color . '</span>' .
                            '</div>' .
                            '</div>'
                        );
                    })
                    ->hidden(fn ($get) => empty($get('icon'))),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('sort_order')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
