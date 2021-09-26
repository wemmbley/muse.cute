<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class TracksController extends Controller
{
    /**
     * POST /admin/upload-track/
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function uploadTrack(Request $request)
    {
        $requiredParams = ['title','name','theme','image'];
        $isRequiredParamsExists = !empty(array_diff($requiredParams, array_keys($request->all())));

        if($isRequiredParamsExists) {
            return response('Not enough params ', 422);
        }

        $socialLinks = ['soundcloud','spotify','youtube-music','itunes'];
        $socialLinksIncluded = 0;

        foreach ($request->all() as $key => $value) {
            if (in_array($key, $socialLinks) && !is_null($value)) {
                $socialLinksIncluded++;
            }
        }

        if($socialLinksIncluded === 0) {
            return response('0 social links included ', 422);
        }

        $track = DB::table('tracks')
            ->select('name')
            ->where('name', '=', $request->get('name'))
            ->first();

        if (!is_null($track)) {
            return response('Track with this name already uploaded', 422);
        }

        $imagePath = $this->uploadImage($request->file('image'));

        DB::table('tracks')->insert([
            'user_id' => Auth::id(),
            'title' => $request->get('title'),
            'name' => $request->get('name'),
            'page_theme' => $request->get('theme'),
            'image_url' => $imagePath,
            'social_links' => json_encode($request->get('social-links')),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        $trackLink = URL::to(Auth::user()->name . '/' . $request->get('name'));

        return redirect()->route('/admin/tracks')->with(['trackLink' => $trackLink]);
    }

    /**
     * POST /admin/getTrack/
     *
     * @param string $trackName
     * @return false|string
     */
    public function getTrack(string $trackName)
    {
        $track = DB::table('tracks')->select()->where('name', '=', $trackName)->first();

        return json_encode($track);
    }

    /**
     * GET /admin/tracks/
     */
    public function getAllTracks()
    {
        $perPage = 2;

        $tracks = DB::table('tracks')
            ->select('user_id','title','name','page_theme','image_url','social_links')
            ->where('user_id', '=', Auth::id())
            ->orderBy('updated_at', 'desc')
            ->paginate($perPage);

        return view('admin.tracks', ['tracks' => $tracks]);
    }

    /**
     * POST /admin/update-track/
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function updateTrack(Request $request)
    {
        $imagePath = $request->get('oldImageUrl');

        if($request->hasFile('image')) {
            if(!$request->file('image')->isValid()) {
                return response('Invalid file', 400);
            }

            $imagePath = $this->uploadImage($request->file('image'));
        }

        DB::table('tracks')
            ->where([
                ['user_id', '=', Auth::id()],
                ['name', '=', $request->get('oldName')],
            ])
            ->update([
                'name' => $request->get('name'),
                'title' => $request->get('title'),
                'image_url' => $imagePath,
                'page_theme' => $request->get('theme'),
                'social_links' => json_encode([
                    'SoundCloud' => $request->get('soundcloud'),
                    'Spotify' => $request->get('spotify'),
                    'iTunes' => $request->get('itunes'),
                    'YouTube' => $request->get('youtube-music'),
                ]),
                'updated_at' => now()
            ]);

        $trackLink = URL::to(Auth::user()->name . '/' . $request->get('name'));

        return redirect()->route('/admin/tracks')->with(['trackLink' => $trackLink]);
    }

    /**
     * POST /delete-track
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTrack(Request $request)
    {
        DB::table('tracks')
            ->where([
                ['name', '=', $request->get('trackName')],
                ['user_id', '=', Auth::id()]
            ])
            ->delete();

        return response(200);
    }

    /**
     * GET /{user}/{track}/
     */
    public function loadTrack($userName, $trackName)
    {
        $user = DB::table('users')
            ->select('id', 'name')
            ->where('name', '=', $userName)
            ->get();

        $track = DB::table('tracks')
            ->where('name', '=', $trackName)
            ->get();

        $isUserExists = !empty($track[0]);
        $isTrackExists = !empty($user[0]);

        if($isUserExists && $isTrackExists) {
            return view('track')
                ->with([
                    'title' => $track[0]->title,
                    'name' => $track[0]->name,
                    'image_url' => $track[0]->image_url,
                    'page_theme' => $track[0]->page_theme,
                    'social_links' => json_decode($track[0]->social_links),
                ]);
        }

        abort(404);

        return false;
    }

    /**
     * Upload image to server and return it url
     *
     * @param object $file
     * @return string
     * @throws \Exception
     */
    private function uploadImage(object $file): string
    {
        if(!method_exists($file, 'store')) {
            throw new \Exception('Not request image handed');
        }

        $filePath = 'public/images/' . Auth::user()->name;
        $uploadedFilePath = $file->store($filePath);
        $uploadedFilePathExplode = explode('/', $uploadedFilePath);
        $fileName = end($uploadedFilePathExplode);
        $imagePath = URL::asset('storage/images/' . Auth::user()->name . '/' . $fileName);

        return $imagePath;
    }
}
