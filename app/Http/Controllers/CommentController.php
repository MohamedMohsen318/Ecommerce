<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ProductComment;
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

        try {
            $this->commentService->create(auth()->id(), $item->id, $data['body'], $data['parent_id'] ?? null);
        } catch (\RuntimeException $e) {
            return back()->withErrors(['comment' => $e->getMessage()]);
        }

        return back()->with('success', 'Comment posted.');
    }

    public function update(Request $request, ProductComment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $data = $request->validate(['body' => ['required', 'string', 'max:2000']]);

        $this->commentService->update($comment, $data['body']);

        return back()->with('success', 'Comment updated.');
    }

    public function destroy(ProductComment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $this->commentService->delete($comment);

        return back()->with('success', 'Comment removed.');
    }
}
