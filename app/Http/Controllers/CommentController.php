<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Services\CommentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    public function store(Request $request, Item $item): RedirectResponse
    {
        $data = $request->validate([
            'body' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'integer', 'exists:product_comments,id'],
        ]);

        $this->commentService->create(auth()->id(), $item->id, $data['body'], $data['parent_id'] ?? null);

        return back()->with('success', 'Comment posted.');
    }
}
