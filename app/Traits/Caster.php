<?php


namespace App\Traits;


use Carbon\Carbon;

trait Caster
{
    public static function castPhoneToDbFormat(string $phone_number): string {
        return preg_replace('/[^\d+]/', '', $phone_number);
    }

    public static function castPhoneToHuman(string $phone_number): string {
        return preg_replace('/(.+)(\d{3})(\d{3})(\d{2})(\d{2})$/', '$1 ($2) $3-$4-$5', $phone_number);
    }

    public static function checkPhoneFormat(string $phone_number): bool {
        return preg_match('/^\+[78]\d{10}$/', $phone_number);
    }

    public static function getFormattedDate(Carbon $date): string {
        return $date->format('Y-m-d');
    }

    public static function castBookingCreatedToHuman(string $datetime, bool $only_date = false): string {
        $format = $only_date ? 'dd, D MMMM' : 'D MMMM, HH:mm';
        return Carbon::createFromDate($datetime)->isoFormat($format);
    }
}
