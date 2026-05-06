<?php

namespace App\Filament\Resources\Reminders\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReminderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required()
                    ->helperText('Fill suitable title'),
                DateTimePicker::make('remind_at')
                    ->required()->native(false)->minDate(now())->seconds(false)->helperText('Time is in 24-hour format'),
                Textarea::make('note')
                    ->helperText('Optional')
                    ->columnSpanFull(),
            ]);
    }
}
