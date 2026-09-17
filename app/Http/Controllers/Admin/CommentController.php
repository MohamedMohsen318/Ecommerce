<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(): View
    {
        return view('admin.comments.index', [
            'comments' => ProductComment::with(['item.translations', 'user', 'parent'])->latest()->paginate(15),
        ]);
    }

    public function destroy(ProductComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment removed.');
    }
}
