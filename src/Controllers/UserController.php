<?php

namespace App\Controllers;

use App\Request;
use App\Models\User;
use App\TwigResponse;

class UserController
{
    public function index(): TwigResponse
    {
        $users = User::all();
        return new TwigResponse('user.index.html.twig', ['users' => $users]);
    }

    public function show(Request $request): TwigResponse
    {
        $id = intval($request->getParams()['id']);
        $user = User::find($id);
        return new TwigResponse('user.show.html.twig', ['user' => $user]);
    }

    # View that calls 'store' method
    public function create(): TwigResponse
    {
        $url = '/users/store'; // temporary solution
        return new TwigResponse('user.create.html.twig', ['url' => $url]);
    }

    public function store(Request $request): void
    {
        $errors = $request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
        ]);

        if (empty($errors)) {
            $user = new User();
            $params = $request->getParams();
            $user->properties = $params;
            /* or
            foreach ($params as $key => $value) {
            $user->$key = $value;
            }
            */
            $user->save();
            var_dump($user);
        # redirect na success neki
        } else {
        }
    }

    # View that calls 'update' method
    public function edit(Request $request): TwigResponse
    {
        $url = '/users/update'; // temporary solution
        $id = intval($request->getParams()['id']);
        $user = User::find($id);
        return new TwigResponse('user.edit.html.twig', ['user' => $user, 'url' => $url]);
    }

    public function update(Request $request): void
    {
        $id = intval($request->getParams()['id']);
        $params = $request->getParams();
        $user = User::find($id);
        $user->properties= $params;
        $user->update();
    }
}
