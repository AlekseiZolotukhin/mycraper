<?php

namespace App\Http\Controllers\Api\v1;

use App\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Goutte\Client;
use App\Lib\Scraper;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = $this->tokenCheck();

        return Settings::where('user_id', $user_id)->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $input['user_id'] = $this->tokenCheck();
        return Settings::create($input);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $settings = Settings::findOrFail($id);

        $this->tokenCheck($settings);

        return $settings;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $settings = Settings::findOrFail($id);

        $this->tokenCheck($settings);

        return $settings->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $settings = Settings::findOrFail($id);

        $this->tokenCheck($settings);

        $settings->delete();

        return true;
    }


    /**
     * scrape specific link
     *
     * @param int $id
     */
    public function scrape($id)
    {

        if(!$id)
            return response()->json([
                'success' => false,
                'error' => 'no required id'
            ]);

        $target = Settings::find($id);

        $this->tokenCheck($target);

        if(empty($target->url) || empty($target->selectors)) {
            return response()->json([
                    'success' => false,
                    'error' => 'no required target data'
                ]);
        }

        $scraper = new Scraper(new Client());

        $scraper->handle($target);

        if($scraper->status === '') {
            return response()->json(['success' => true]);
        }

        return response()->json([
            'success' => false,
            'error' => $scraper->status
        ]);
    }
}
