<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $eloQuery = [];
        $safeParams = [
            'title',
            'year',
            "categories"
        ];
        $categories = [];
        foreach ($safeParams as $param) {
            $query = request()->query($param);
            if (!isset($query)) continue;
            if ($param === "title") {
                $eloQuery[] = [$param, "like", "%$query%"];
            } else if ($param === "year") {
                $eloQuery[] = [$param, ">=", $query];
            } else if ($param === "categories") {
                $categories = explode(",", request()->query('categories'));
            }
        }
        $query = Book::where($eloQuery);
        foreach ($categories as $category) {
            $query->whereHas("categories", function ($q) use ($category) {
                $q->where('name', $category);
            });
        }
        return BookResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $validated = $request->validated();
        // array<int>
        $categories = $this->setCategories($request);
        $book = Book::create([
            'title' => $validated['title'],
            'stocks' => $validated['stocks'],
            'year' => $validated['year'],
            'cover_url' => $validated['coverUrl'],
        ]);
        $book->categories()->attach($categories);

        return response()
            ->json(
                [
                    'message' => 'New book added successfully',
                    'book' => new BookResource($book),
                ]
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        if (is_null($book)) return response(['message' => 'Book not found'], 404);
        return response(['book' => new BookResource($book)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, string $id)
    {
        $existingBook = Book::find($id);
        if (is_null($existingBook)) return response(['message' => 'Book not found'], 404);
        $validated = $request->validated();
        $existingBook->title = $validated["title"];
        $existingBook->cover_url = $validated["coverUrl"];
        $existingBook->year = $validated["year"];
        $existingBook->stocks = $validated["stocks"];
        $existingBook->save();
        $categories = $this->setCategories($request);
        $existingBook->categories()->sync($categories);
        return response(['message' => 'Book updated successfully', 'book' => new BookResource($existingBook)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if (is_null($book)) return response(['message' => 'Book not found']);
        $book->delete();
        return response(['message' => 'Book deleted']);
    }

    private function setCategories(Request $request)
    {
        // array<int>
        $categories = [];
        foreach ($request->categories as $category) {
            $existingCategory = Category::where('name', strtolower($category))->first();
            if (is_null($existingCategory)) {
                $newCategory = Category::create(['name' => strtolower($category)]);
                array_push($categories, $newCategory->id);
            } else {
                array_push($categories, $existingCategory->id);
            }
        }
        return $categories;
    }
}
