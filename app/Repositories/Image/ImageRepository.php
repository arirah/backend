<?php


namespace App\Repositories\Image;

class ImageRepository implements ImageRepositoryInterface
{

    public function upload($request)
    {
        $file = $request->file('file');
        $user = \Auth::user();
        if (!in_array(strtolower($file->getClientOriginalExtension()), ['jpg', 'bmp', 'png', 'svg'])) {
            return response()->json(json_encode(['error' => ['Not valid format ! ']]), 400);
        }
        $imageName = time() . '.' . $file->getClientOriginalExtension();
        $filePath = public_path('/images/' . $user->id);
        $publicPath = '/images/' . $user->id;
        $file->move($filePath, $imageName);
        return response()->json(['image' => $publicPath . '/' . $imageName], 200);
    }
}
