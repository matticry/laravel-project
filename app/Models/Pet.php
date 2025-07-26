<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pet extends Model
{
    use HasFactory;

    protected $table = 'tbl_pet';
    protected $primaryKey = 'id_pet';

    public $timestamps = false;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'name_pet',
        'species_pet',
        'breed_pet',
        'age_pet',
        'image_pet',        // Ahora guarda el path o URL
        'image_source',     // 'upload' o 'api'
        'status_pet',
        'id_usu'
    ];

    protected $casts = [
        'age_pet' => 'integer',
        'id_usu' => 'integer',
        'created_at' => 'datetime'
    ];

    /**
     * Relación con Usuario
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usu', 'us_id');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status_pet', 'A');
    }

    public function scopeInactive($query)
    {
        return $query->where('status_pet', 'I');
    }

    public function scopeOfSpecies($query, $species)
    {
        return $query->where('species_pet', $species);
    }

    /**
     * Accessors para imágenes
     */
    public function getImageUrlAttribute()
    {
        if (!$this->image_pet) {
            return null;
        }

        // Si es imagen subida, crear URL completa
        if ($this->image_source === 'upload') {
            return asset('storage/' . $this->image_pet);
        }

        // Si es de API, retornar URL directamente
        return $this->image_pet;
    }

    public function getHasImageAttribute()
    {
        return !empty($this->image_pet);
    }

    public function getIsUploadedImageAttribute()
    {
        return $this->image_source === 'upload';
    }

    public function getIsApiImageAttribute()
    {
        return $this->image_source === 'api';
    }

    /**
     * Métodos útiles
     */
    public function activate()
    {
        $this->status_pet = 'A';
        return $this->save();
    }

    public function deactivate()
    {
        $this->status_pet = 'I';
        return $this->save();
    }
}
