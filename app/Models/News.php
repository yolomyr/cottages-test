<?php

namespace App\Models;

use App\Casts\NewsTitle;
use App\Casts\StorageUrl;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Helpers\Uploader;

class News extends Model
{
    use HasFactory;

    public const FOLDER = 'news/';
    public const DEFAULT_OFFSET = 0;
    public const DEFAULT_LIMIT = 8;

    protected $fillable = ['logo', 'subtitle', 'content'];

    protected $casts = [
        'logo' => StorageUrl::class,
        'created_at' => NewsTitle::class
    ];


    /**
     * Get news_attachments records associated with the news.
     */
    public function attachments() {
        return $this->hasMany(NewsAttachment::class);
    }

    final public static function get($offset, $limit) {
        $offset = $offset ?? self::DEFAULT_OFFSET;
        $limit = $limit ?? self::DEFAULT_LIMIT;

        return self::offset($offset)
            ->limit($limit)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    final public function setLogo($path) {
        $this->logo = $path;
        $this->save();
    }

    final public static function add(array $news_data): void {

        $news = self::create([
            'logo' => 'logo',
            'subtitle' => $news_data['subtitle'],
            'content' => $news_data['content']
        ]);

        $path = self::FOLDER . $news->id;
        $uploader = new Uploader($news_data['logo'], $path);
        $uploader->save();

        $news->setLogo($uploader->full_path);

        self::addAttachments($news->id, $news_data['files'] ?? null);
    }

    final public static function addAttachments(int $news_id, $files): void {
        if (empty($files)) {
            return;
        }

        if (is_array($files)) {
            foreach ($files as $file) {
                NewsAttachment::put($news_id, $file);
            }
        } else {
            NewsAttachment::put($news_id, $files);
        }
    }

    final public function deletePostWithFiles(): void {
        Uploader::deleteFolder(self::FOLDER . $this->id);
        $this->delete();
    }

    final public function updatePost(array $news_data): void {
        if (isset($news_data['logo'])) {
            Uploader::deleteFile($this->logo);

            $path = self::FOLDER . $this->id;
            $uploader = new Uploader($news_data['logo'], $path);
            $uploader->save();

            $news_data['logo'] = $uploader->full_path;
        }

        $this->fill($news_data);
        $this->save();

        self::addAttachments($this->id, $news_data['files'] ?? null);

        if (isset($news_data['deleted_files'])) {
            foreach ($news_data['deleted_files'] as $deleted_file) {
                NewsAttachment::findOrFail($deleted_file)->deletePostWithFile();
            }
        }
    }
}
