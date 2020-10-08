<?php

namespace App\Models;

use App\Casts\StorageUrl;
use App\Helpers\Uploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public const FOLDER = 'services/';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'status_id', 'logo', 'unavailability_reason'
    ];

    protected $casts = [
        'logo' => StorageUrl::class,
    ];

    public $timestamps = false;

    /**
     * Get service_statuses records associated with the services.
     */
    final public function status() {
        return $this->hasOne(ServiceStatus::class, 'id', 'status_id');
    }

    final public function setLogo($file): void {
        $path = self::FOLDER . $this->id;
        $uploader = new Uploader($file, $path);
        $uploader->save();

        $this->logo = $uploader->full_path;
        $this->save();
    }

    final public function deleteLogo(): void {
        Uploader::deleteFile($this->logo);
    }

    final public static function createService(array $service_data): void {
        $service = self::create([
            'title' => $service_data['title'],
            'status_id' => $service_data['status_id'] ?? ServiceStatus::STATUSES['active'],
            'logo' => 'logo'
        ]);

        $service->setLogo($service_data['logo']);
    }

    final public function updateService(): void {
        if ($this->isDirty('logo')) {
            $this->deleteLogo();
            $this->setLogo($this->logo);
        }

        if ($this->isDirty()) {
            $this->save();
        }
    }
}
