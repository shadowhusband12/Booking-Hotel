<?php

namespace App\Enums;

enum BookingStatus: string
{
    // ... case enum (PENDING, CONFIRMED, dll) ...
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case CHECKED_IN = 'checked-in';
    case CHECKED_OUT = 'checked-out';
    case CANCELLED = 'cancelled';


    // Method untuk label yang ramah
    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::CONFIRMED => 'Confirmed',
            self::CHECKED_IN => 'Checked-In',
            self::CHECKED_OUT => 'Checked-Out',
            self::CANCELLED => 'Cancelled',
        };
    }

    // Method untuk warna BADGE status
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
            self::CONFIRMED => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
            self::CHECKED_IN => 'bg-green-100 text-white dark:bg-green-900 dark:text-green-300',
            self::CHECKED_OUT => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
            self::CANCELLED => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        };
    }

    // METHOD BARU: Untuk warna BACKGROUND KARTU
    public function cardBgColor(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-50 dark:bg-gray-800',
            self::CONFIRMED => 'bg-blue-50 dark:bg-gray-800',
            self::CHECKED_IN => 'bg-green-50 dark:bg-gray-800',
            self::CHECKED_OUT => 'bg-gray-50 dark:bg-gray-800',
            self::CANCELLED => 'bg-red-50 dark:bg-gray-800',
        };
    }

    // METHOD BARU: Untuk warna BORDER KARTU
    public function cardBorderColor(): string
    {
        return match ($this) {
            self::PENDING => 'border-yellow-300 dark:border-yellow-600',
            self::CONFIRMED => 'border-blue-300 dark:border-blue-600',
            self::CHECKED_IN => 'border-green-300 dark:border-green-600',
            self::CHECKED_OUT => 'border-gray-300 dark:border-gray-600',
            self::CANCELLED => 'border-red-300 dark:border-red-600',
        };
    }
}
