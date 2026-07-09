<?php

namespace App\Http\Controllers;

use App\Models\HomepageReview;
use Illuminate\Http\Request;

class HomepageReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:view_all_website_pages'])->only(['index', 'updateStatus', 'updateSettings']);
        $this->middleware(['permission:add_website_page'])->only(['create', 'store', 'duplicate']);
        $this->middleware(['permission:edit_website_page'])->only(['edit', 'update', 'updateStatus', 'updateSettings']);
        $this->middleware(['permission:delete_website_page'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = HomepageReview::orderBy('created_at', 'desc')->get();
        return view('backend.website_settings.homepage_reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.website_settings.homepage_reviews.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'type' => 'required|in:text,image',
        ];

        if ($request->type == 'text') {
            $rules['name'] = 'required|string|max:255';
            $rules['rating'] = 'required|integer|min:1|max:5';
            $rules['review_text'] = 'required|string';
            $rules['review_date'] = 'required|date';
            $rules['image'] = 'nullable|string';
            $rules['category_tag'] = 'nullable|string|max:255';
        } else {
            $rules['image'] = 'required|string';
        }

        $request->validate($rules);

        $review = new HomepageReview();
        $review->type = $request->type;
        $review->status = $request->has('status') ? 1 : 0;
        $review->image = $request->image;

        if ($request->type == 'text') {
            $review->name = $request->name;
            $review->rating = $request->rating;
            $review->review_text = $request->review_text;
            $review->review_date = $request->review_date;
            $review->category_tag = $request->category_tag;
        } else {
            $review->name = null;
            $review->rating = 5;
            $review->review_text = null;
            $review->review_date = null;
            $review->category_tag = null;
        }

        $review->save();

        flash(translate('Homepage review has been added successfully'))->success();
        return redirect()->route('homepage-reviews.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $review = HomepageReview::findOrFail($id);
        return view('backend.website_settings.homepage_reviews.edit', compact('review'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $review = HomepageReview::findOrFail($id);

        $rules = [
            'type' => 'required|in:text,image',
        ];

        if ($request->type == 'text') {
            $rules['name'] = 'required|string|max:255';
            $rules['rating'] = 'required|integer|min:1|max:5';
            $rules['review_text'] = 'required|string';
            $rules['review_date'] = 'required|date';
            $rules['image'] = 'nullable|string';
            $rules['category_tag'] = 'nullable|string|max:255';
        } else {
            $rules['image'] = 'required|string';
        }

        $request->validate($rules);

        $review->type = $request->type;
        $review->status = $request->has('status') ? 1 : 0;
        $review->image = $request->image;

        if ($request->type == 'text') {
            $review->name = $request->name;
            $review->rating = $request->rating;
            $review->review_text = $request->review_text;
            $review->review_date = $request->review_date;
            $review->category_tag = $request->category_tag;
        } else {
            $review->name = null;
            $review->rating = 5;
            $review->review_text = null;
            $review->review_date = null;
            $review->category_tag = null;
        }

        $review->save();

        flash(translate('Homepage review has been updated successfully'))->success();
        return redirect()->route('homepage-reviews.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $review = HomepageReview::findOrFail($id);
        $review->delete();

        flash(translate('Homepage review has been deleted successfully'))->success();
        return back();
    }

    /**
     * Toggle status.
     */
    public function updateStatus(Request $request)
    {
        $review = HomepageReview::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        return 1;
    }

    /**
     * Update configuration settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'section_status' => 'required|in:0,1',
            'desktop_slider' => 'required|in:0,1',
        ]);

        \App\Models\BusinessSetting::updateOrCreate(
            ['type' => 'homepage_reviews_section_status'],
            ['value' => $request->section_status]
        );

        \App\Models\BusinessSetting::updateOrCreate(
            ['type' => 'homepage_reviews_desktop_slider'],
            ['value' => $request->desktop_slider]
        );

        \Illuminate\Support\Facades\Cache::forget('business_settings');

        flash(translate('Homepage reviews settings updated successfully'))->success();
        return back();
    }

    /**
     * Duplicate a review.
     */
    public function duplicate($id)
    {
        $review = HomepageReview::findOrFail($id);
        $duplicatedReview = $review->replicate();
        $duplicatedReview->status = 0; // set duplicate copy to inactive initially
        $duplicatedReview->save();

        flash(translate('Homepage review has been duplicated successfully'))->success();
        return back();
    }
}
