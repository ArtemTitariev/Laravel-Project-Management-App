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
