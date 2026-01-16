<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrgController extends Controller
{
    public function index()
    {
        $organizations = [
            [
                'id' => 1,
                'name' => 'Google Developer Groups on Campus – PUP',
                'short_name' => 'GDG',
                'status' => 'ACTIVE',
                'year' => '2018',
                'description' => 'An organization is a group of people who work together, like a neighborhood association, a charity, a union, or a corporation.',
                'members' => 23
            ],
            [
                'id' => 2,
                'name' => 'Amazon Web Services – PUP',
                'short_name' => 'AWS',
                'status' => 'ACTIVE',
                'year' => '2020',
                'description' => 'Learn cloud computing and modern infrastructure with AWS technologies and tools.',
                'members' => 18
            ]
        ];

        return view('AfterLoginFolder.dashboard', compact('organizations'));
    }
}
