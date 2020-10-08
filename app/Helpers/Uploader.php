<?php


namespace App\Helpers;


use Illuminate\Support\Facades\Storage;

class Uploader
{
    public const DEFAULT_DISK = 'public';

    public $file;
    public string $name;
    public array $meta;
    public string $path_to_save;
    public string $full_path;

    public function __construct($file, string $path_to_save) {
        $this->file = $file;
        $this->name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        $this->meta = [
            'extension' => pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION),
            'mime' =>  $file->getMimeType(),
            'size' => $this->getFileSize(),
        ];

        $this->path_to_save = $path_to_save;
    }

    final public function save(): array {
        $this->full_path = Storage::disk(self::DEFAULT_DISK)->putFile($this->path_to_save, $this->file);

        return $this->meta;
    }

    final public function getFileSize(): string {
        return self::bytesToHuman( $this->file->getSize() );
    }

    public static function bytesToHuman(int $bytes): string {
        $units = ['Б', 'Кб', 'Мб'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    final public static function deleteFolder(string $path): void {
        Storage::disk(self::DEFAULT_DISK)->deleteDirectory($path);
    }

    final public static function deleteFile(string $path): void {
        Storage::disk(self::DEFAULT_DISK)->delete($path);
    }
}
