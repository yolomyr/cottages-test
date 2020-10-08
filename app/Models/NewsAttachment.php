<?php

namespace App\Models;

use App\Casts\StorageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uploader;

class NewsAttachment extends Model
{
    use HasFactory;

    public const FOLDER = 'attachments/';

    protected $fillable = ['news_id', 'name', 'path', 'meta', 'attachment_path'];

    protected $casts = [
        'path' => StorageUrl::class,
        'meta' => 'json',
    ];

    final public static function put(int $news_id, $file): void {
        $path = self::getFolder($news_id);

        $uploader = new Uploader($file, $path);

        $meta = $uploader->save();

        self::create([
            'news_id' => $news_id,
            'path' => $uploader->full_path,
            'name' => $uploader->name,
            'meta' => $meta
        ]);
    }

    final public function deletePostWithFile(): void {
        Uploader::deleteFile($this->path);
        $this->delete();
    }

    final public static function getFolder(int $news_id): string {
        return News::FOLDER . $news_id . '/' . self::FOLDER;
    }
}
