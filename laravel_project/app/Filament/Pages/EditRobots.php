<?php

namespace App\Filament\Pages;

use App\Support\RobotsFile;
use BackedEnum;
use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use RuntimeException;
use Throwable;

class EditRobots extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static ?string $navigationLabel = 'Служебные файлы';

    protected static ?string $title = 'Служебные файлы';

    protected static ?string $slug = 'robots';

    protected static ?int $navigationSort = 11;

    /**
     * @var array<string, string>|null
     */
    public ?array $data = [];

    public function mount(): void
    {
        try {
            $content = RobotsFile::read();
        } catch (RuntimeException $exception) {
            $content = '';

            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        }

        $this->form->fill([
            'content' => $content,
        ]);

        $count = session('sitemap_count');

        if (is_int($count)) {
            Notification::make()
                ->title('Sitemap собран')
                ->body('Страниц: '.$count.'. Карта открывается по адресу '.route('sitemap'))
                ->success()
                ->send();
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('robots.txt')
                    ->description('Этот текст отдаётся по адресу /robots.txt. Пустой файл разрешает обход всего сайта.')
                    ->schema([
                        Textarea::make('content')
                            ->hiddenLabel()
                            ->rows(18)
                            ->extraInputAttributes(['class' => 'font-mono'])
                            ->rules([
                                fn (): Closure => function (string $attribute, mixed $value, Closure $fail): void {
                                    if (! is_string($value)) {
                                        $fail('Содержимое должно быть текстом.');

                                        return;
                                    }

                                    if (str_contains($value, "\0")) {
                                        $fail('В файле не должно быть служебных символов.');
                                    }

                                    if (strlen($value) > RobotsFile::MAX_BYTES) {
                                        $fail('Файл больше 100 КБ.');
                                    }
                                },
                            ]),
                    ])
                    ->footerActions([
                        Action::make('save')
                            ->label('Сохранить')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
            ])
            ->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    EmbeddedSchema::make('form'),
                ])
                    ->id('form')
                    ->livewireSubmitHandler('save'),
                Section::make('sitemap.xml')
                    ->description('Отдаётся по адресу /sitemap.xml и собирается при открытии.')
                    ->schema([
                        View::make('filament.pages.sitemap-link'),
                    ])
                    ->footer([
                        View::make('filament.pages.sitemap-generate'),
                    ]),
            ]);
    }

    public function save(): void
    {
        $state = $this->form->getState();

        try {
            RobotsFile::write((string) ($state['content'] ?? ''));
        } catch (Throwable $exception) {
            Notification::make()
                ->title($exception instanceof RuntimeException ? $exception->getMessage() : 'Не удалось сохранить robots.txt.')
                ->danger()
                ->send();

            return;
        }

        $this->form->fill([
            'content' => RobotsFile::read(),
        ]);

        Notification::make()
            ->title('robots.txt сохранён')
            ->success()
            ->send();
    }
}
