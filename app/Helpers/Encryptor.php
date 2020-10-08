<?php


namespace App\Helpers;

use Illuminate\Support\Str;
use Throwable;

class Encryptor
{
    /**
     * Delimiter to explode decrypted string
     */
    public const DELIMITER = '&';

    /**
     * Random bytes length parameter for tokens
     */
    public const TOKEN_GENERATOR_SIZE = 32;

    /**
     * Token hash algorithm
     */
    public const TOKEN_ALGO = 'snefru256';

    /**
     * OpenSSL options parameter
     * @var int
     */
    public int $openssl_options;

    /**
     * Cipher method for OpenSSL function
     * @var string
     */
    public string $ciphering_method;

    /**
     * Cipher method for OpenSSL function
     * @var string
     */
    public string $secret_iv;

    /**
     * Encryptor constructor.
     * @param string|null $secret_iv
     * @param string $ciphering_method
     * @param int $openssl_options
     * @throws Throwable
     */
    public function __construct(string $secret_iv = null, string $ciphering_method = 'BF-CBC', int $openssl_options = 0) {
        $this->ciphering_method = $ciphering_method;
        $this->openssl_options = $openssl_options;
        $this->secret_iv = $secret_iv ?? $this->generateSecretIv();
    }

    /**
     * Get IV length based on cypher method
     * @return int
     */
    private function getIvLength(): int {
        return openssl_cipher_iv_length($this->ciphering_method);
    }

    /**
     * Generate random IV method
     * @return string
     * @throws Throwable
     */
    private function generateSecretIv(): string {
        return random_bytes( $this->getIvLength() );
    }

    /**
     * Encryption function
     * @param string $string_to_encrypt
     * @return false|string
     */
    final public function encrypt(string $string_to_encrypt): string {
        $encryption_key = openssl_digest(php_uname(), 'MD5', TRUE);

        return openssl_encrypt($string_to_encrypt,
            $this->ciphering_method,
            $encryption_key,
            $this->openssl_options,
            $this->secret_iv);
    }

    /**
     * Decryption function
     * @param string $string_to_decrypt
     * @return false|string
     */
    final public function decrypt(string $string_to_decrypt): string {
        $decryption_key = openssl_digest(php_uname(), 'MD5', TRUE);

        return openssl_decrypt($string_to_decrypt,
            $this->ciphering_method,
            $decryption_key,
            $this->openssl_options,
            $this->secret_iv);
    }

    /**
     * Parse decrypted string to array of parameters
     * @param string $decrypted_string
     * @return false|string[]
     */
    final public function parseDecryptedString(string $decrypted_string): array {
        return explode(self::DELIMITER, $decrypted_string);
    }

    /**
     * Generate password reset token
     * @param string|null $user_email
     * @return string
     */
    final public static function getPasswordResetToken(string $user_email = null): string {
        return Str::random(self::TOKEN_GENERATOR_SIZE) . (!empty($user_email) ? hash(self::TOKEN_ALGO, $user_email) : '');
    }
}
