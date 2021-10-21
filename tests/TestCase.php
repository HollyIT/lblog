<?php

namespace Tests;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use LazilyRefreshDatabase;
    protected string $tempDir = __DIR__ . '/tempfs';
    protected function setUp(): void
    {
        parent::setUp();
        Config::set('filesystems.disks.public.root', $this->tempDir);
    }

    protected function tearDown(): void
    {
        File::cleanDirectory($this->tempDir);
        parent::tearDown();
    }
    public function asAdmin(): Model|User
    {
        $user = $this->makeUser('admin');
        $this->actingAs($user);

        return $user;
    }

    protected function makeUser($role): Model|User
    {
        return User::create([
            'name' => $role ? "$role user" : 'user',
            'email' => $role ? $role.'@example.com' : 'user@example.com',
            'password' => \Hash::make('secret'),
            'role' => $role !== 'regular' ? $role : null,
        ]);
    }

    public function asEditor(): Model|User
    {
        $user = $this->makeUser('editor');
        $this->actingAs($user);

        return $user;
    }

    public function asContributor(): Model|User
    {
        $user = $this->makeUser('contributor');
        $this->actingAs($user);

        return $user;
    }

    public function asRegularUser(): Model|User
    {
        $user = $this->makeUser('regular');
        $this->actingAs($user);

        return $user;
    }
}
