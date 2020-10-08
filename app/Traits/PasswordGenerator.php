<?php


namespace App\Traits;

trait PasswordGenerator
{
    public static function randomPassword(int $length = 0): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!-.[]?*()^&$%_';
        $password = '';
        $characterListLength = mb_strlen($characters, '8bit') - 1;
        foreach(range(1, $length) as $i){
            $password .= $characters[random_int(0, $characterListLength)];
        }
        return $password;
    }
}
