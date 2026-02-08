<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:120',
            'message' => 'required|string|max:2000',
        ]);

        $message = ContactMessage::create($validated);

        return response()->json(['message' => 'Saved', 'id' => $message->id], 201);
    }
}
