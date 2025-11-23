<?php

namespace App\Enums;

enum CertificateStatus: string
{
    case ON_PROGRESS = 'on_progress';
    case COMPLETED = 'completed';

    /**
     * Get the human-readable label for the status.
     *
     * @return string
     */
    public function getLabel(): string
    {
        return match($this) {
            self::ON_PROGRESS => 'On Progress',
            self::COMPLETED => 'Completed',
        };
    }

    /**
     * Get the color for badge display in Filament.
     *
     * @return string
     */
    public function getColor(): string
    {
        return match($this) {
            self::ON_PROGRESS => 'warning',
            self::COMPLETED => 'success',
        };
    }

    /**
     * Get all status values as an associative array.
     *
     * @return array
     */
    public static function toArray(): array
    {
        return [
            self::ON_PROGRESS->value => self::ON_PROGRESS->getLabel(),
            self::COMPLETED->value => self::COMPLETED->getLabel(),
        ];
    }
}
