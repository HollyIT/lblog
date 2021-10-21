<?php
/**
 * @var  \App\Models\User $user
 */
?>
@extends('admin.layout')
@section('title')
    @if($user->exists)
        Edit user
    @else
        Create user
    @endif

@endsection
@section('content')


    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <form method="post"
              enctype="multipart/form-data"
              autocomplete="off"
              action="{{ $user->exists ? route('admin.users.update', ['user' => $user]) : route('admin.users.store') }}">
            @csrf
            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                    'type' => 'text',
                    'label' => 'Name',
                    'name' => 'name',
                    'placeholder' => 'Enter the user\'s name',
                    'value' => $user->name
                ])

            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                    'type' => 'email',
                    'label' => 'Email',
                    'name' => 'email',
                    'placeholder' => 'Enter the user\'s email',
                    'value' => $user->email
                ])

            </div>

            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                  'type' => 'password',
                  'label' => 'Password',
                  'name' => 'password',
                  'placeholder' => '',
                  'value' => ''
              ])
            </div>
            <div class="block text-sm mb-5">
                @include('admin.inputs.text', [
                  'type' => 'password',
                  'label' => 'Password again',
                  'name' => 'password_confirmation',
                  'placeholder' => '',
                  'value' => ''
              ])
            </div>

            @can('assignRole', $user)
                <div class="block text-sm mb-5">
                    @include('admin.inputs.select', [
                      'label' => 'Role',
                      'name' => 'role',
                      'options' =>$roles,
                      'value' => $user->role
                    ])
                </div>
            @endcan
            <div class="block text-sm mb-5">
                @include('admin.inputs.submit', ['label' => $user->exists ? 'Save' : 'Create'])
            </div>
        </form>
    </div>

@endsection
