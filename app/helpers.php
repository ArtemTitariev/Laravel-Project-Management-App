<?php
if (!function_exists('sendErrorNotification')) {
    function sendErrorNotification(string $source, string $inUseBy, $field = null)
    {
        \Filament\Notifications\Notification::make()
            ->danger()
            ->title("$source  " . ($field ?? '') . " is in use")
            ->body("$source source is in use by $inUseBy")
            ->send();
    }
}


if (!function_exists('checkDateFieldWhenFinished')) {

    function checkDateFieldWhenFinished(
        string $attribute,
        $value,
        $checkedAttribute,
        \Closure $fail,
        $get,
        $model
    ) {
        if (
            $get('status_id') ===
            $model::where(
                'name',
                $model::FINISHED
            )->first()->id
            &&  $value > now()
        ) {
            $fail("The $attribute field must be a date before or equal to today when $checkedAttribute is " . $model::FINISHED . ".");
        }
    }
}

if (!function_exists('myPasswordFiled')) {
    function myPasswordField()
    {
        return
            \Filament\Forms\Components\TextInput::make('password')
            ->password()
            ->minLength(8)
            ->regex('/[a-z]/') // must contain at least one lowercase letter
            ->regex('/[A-Z]/') // must contain at least one uppercase letter
            ->regex('/[0-9]/') // must contain at least one digit
            ->regex('/[0-9]/') // must contain a special character
            ->regex('/[@$!%*#?&]/')
            ->validationMessages([
                'regex' => 'The :attribute must contain at least one lowercase letter, one uppercase letter, one digit and one character.',
            ])
            ->dehydrateStateUsing(fn ($state) => \Illuminate\Support\Facades\Hash::make($state))
            ->dehydrated(fn ($state) => filled($state))
            ->required(fn (string $context): bool => $context === 'create');
    }
}
