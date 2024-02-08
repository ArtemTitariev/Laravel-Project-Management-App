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
