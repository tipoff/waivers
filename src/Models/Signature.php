<?php

declare(strict_types=1);

namespace Tipoff\Waivers\Models;

use Tipoff\Support\Models\BaseModel;
use Tipoff\Support\Traits\HasPackageFactory;
use Tipoff\Waivers\Database\Factories\SignatureFactory;

class Signature extends BaseModel
{
    use HasPackageFactory;

    protected $casts = [
        'emailed_at' => 'datetime',
        'minors_names' => 'array',
        'dob' => 'date',
    ];

    protected static function newFactory()
    {
        return SignatureFactory::new();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($signature) {
            if (empty($signature->minors)) {
                $signature->minors = 0;
            }
        });
    }

    public function email()
    {
        return $this->belongsTo(app('email_address'), 'email_address_id');
    }

    public function participant()
    {
        return $this->belongsTo(app('participant'), 'participant_id');
    }

    public function room()
    {
        return $this->belongsTo(app('room'), 'room_id');
    }

    public function zip()
    {
        return $this->belongsTo(app('zip'), 'zip_code');
    }
}
