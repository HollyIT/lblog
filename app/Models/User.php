<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\UploadedFile;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property Image $avatar;
 * @property int $avatar_id;
 * @property Carbon $created_at;
 * @property Carbon $updated_at;
 * @mixin \Eloquent
 * @noinspection PhpFullyQualifiedNameUsageInspection
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar_id',
        'role',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        static::deleted(function (User $user) {
            if ($user->avatar) {
                $user->avatar->delete();
            }
        });

        static::saving(function (User $user) {
            if ($user->isDirty('avatar_id') && $old = $user->getOriginal('avatar_id')) {
                $original = Image::find($old);
                $original?->delete();
            }
        });
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function hasRole($roles): bool
    {
        $roles = is_array($roles) ? $roles : [$roles];

        return $this->role && in_array($this->role, $roles);
    }

    public function avatar(): HasOne
    {
        return $this->hasOne(Image::class, 'id', 'avatar_id');
    }

    public function attachAvatar(UploadedFile $file)
    {
        $avatar = Image::createFromUpload($file, 'avatars');
        $this->avatar_id = $avatar->id;

        return $this;
    }

    public function getInitialsAttribute()
    {
        if (! str_contains($this->name, ' ')) {
            return Str::upper(Str::substr($this->name, 0, 2));
        }
        $parts = explode(' ', $this->name);

        return Str::upper(Str::substr($parts[0], 0, 1)) . Str::upper(Str::substr($parts[count($parts) - 1], 0, 1));
    }

    public function hasAdminAccess(): bool
    {
        return $this->hasRole(['admin', 'editor', 'contributor']);
    }
}
