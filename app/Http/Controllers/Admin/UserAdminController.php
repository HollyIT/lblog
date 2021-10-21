<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserSaveRequest;
use App\Models\User;
use App\Queries\UserQuery;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;

class UserAdminController extends Controller
{
    public function index(): Factory|View|Application
    {
        return view('admin.users.manage', [
            'pageTitle' => 'Users',
            'users' => UserQuery::start()->withCount(['posts'])->with('avatar')->paginate(10),
            'sorted' => '-updated_at',
        ]);
    }

    protected function userForm(User $user): Factory|View|Application
    {
        return view('admin.users.form', [
            'user' => $user,
            'roles' => [
                '' => 'None',
                'contributor' => 'Contributor',
                'editor' => 'Editor',
                'admin' => 'Administrator',
            ],
            'pageTitle' => '',
        ]);
    }

    public function create(): Factory|View|Application
    {
        return $this->userForm(new User());
    }

    public function store(UserSaveRequest $request): Redirector|Application|RedirectResponse
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect(route('admin.users.edit', [
            'user' => $request->persist(new User()),
        ]))->withSuccess('User created');
    }

    public function edit(User $user): View|Factory|Application
    {
        return $this->userForm($user);
    }

    public function update(User $user, UserSaveRequest $request): Redirector|Application|RedirectResponse
    {
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect(route('admin.users.index', [
            'user' => $request->persist($user),
        ]))->withSuccess('User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();
        /** @noinspection PhpUndefinedMethodInspection */
        return redirect(route('admin.users.index'))->withSuccess('The user has been deleted');
    }
}
