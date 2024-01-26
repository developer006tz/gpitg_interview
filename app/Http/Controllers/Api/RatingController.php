<?php

namespace App\Http\Controllers\Api;

use App\Models\UserRating;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserRatingResource;
use App\Http\Resources\UserRatingCollection;
use App\Http\Requests\UserRatingStoreRequest;
use App\Http\Requests\UserRatingUpdateRequest;

class RatingController extends Controller
{
    public function index(Request $request): UserRatingCollection
    {


        $userRatings = UserRating::latest()->get();

        return new UserRatingCollection($userRatings);
    }

    public function store(Request $request)
    {

        $rules = [
            'product_id' => ['required', 'exists:products,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()->toArray()]);

        }
        $data = [
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'rating' => trim($request->rating),
            'rating_datetime' => now(),
        ];
        try {
            $checkRating = UserRating::where('product_id', $request->product_id)->where('user_id', $request->user_id)->first();
            if ($checkRating) {
                $checkRating->update($data);
                return response()->json(['status' => 200, 'message' => 'Rating updated successfully']);
            }else{
                UserRating::create($data);
                return response()->json(['status' => 200, 'message' => 'Rating added successfully']);
            }

            
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Something went wrong']);
        }

        
    }

    public function show(Request $request, UserRating $userRating): UserRatingResource
    {
        return new UserRatingResource($userRating);
    }


    public function update(
        Request $request,
        UserRating $userRating
    ) {

        $rules = [
            'product_id' => ['required', 'exists:products,id'],
            'user_id' => ['required', 'exists:users,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
        ];

        $validator = validator()->make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['status' => 422, 'errors' => $validator->errors()->toArray()]);

        }

        try {
            $userRating->update($request->all());
            return response()->json(['status' => 200, 'message' => 'Rating updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Something went wrong']);
        }
    }

    public function destroy(Request $request, UserRating $userRating)
    {

        $userRating->delete();

        return response()->json(['status' => 200, 'message' => 'Rating deleted successfull']);
    }

    //list of all products with average rating

    public function productRating(Request $request)
    {
        $products = \App\Models\Product::with('userRatings')->get();
        $authId = auth()->user()->id;
        $data = [];
        foreach ($products as $key => $product) {
            $data[$key]['product_name'] = $product->name;
            $data[$key]['rating'] = $product->userRatings->avg('rating');
            $data[$key]['user_rating'] = $product->userRatings->where('user_id', $authId)->first()->rating ?? null;
            $data[$key]['time_passed'] = $product->userRatings->where('user_id', $authId)->first() ? $product->userRatings->where('user_id', $authId)->first()->rating_datetime->diffForHumans() : null;
            $data[$key]['active_time'] = $product->userRatings->where('user_id', $authId)->first() ? $product->userRatings->where('user_id', $authId)->first()->rating_datetime->diffInMinutes(now()) > 30 ? 'active' : 'inactive' : null;
        }
        return response()->json(['status' => 200, 'data' => $data]);
    }
}
