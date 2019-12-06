<?php

namespace App\Http\Controllers;

use App\Genre;
use Facades\App\Helpers\Json;
use App\Record;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function genre()
    {
        return $this->belongsTo('App\Genre')->withDefault();   // a record belongs to a genre
    }
    // Master Page: http://vinyl_shop.test/shop or http://localhost:3000/shop
    public function index(Request $request)
    {
        $genre_id = $request->input('genre_id') ?? '%'; //OR $genre_id = $request->genre_id ?? '%';
        $artist_title = '%' . $request->input('artist') . '%'; //OR $artist_title = '%' . $request->artist . '%';
        $records = Record::with('genre')
            ->where(function ($query) use ($artist_title, $genre_id) {
                $query->where('artist', 'like', $artist_title)
                    ->where('genre_id', 'like', $genre_id);
            })
            ->orWhere(function ($query) use ($artist_title, $genre_id) {
                $query->where('title', 'like', $artist_title)
                    ->where('genre_id', 'like', $genre_id);
            })
            ->orderBy("artist")
            ->paginate(12)
            ->appends(['artist'=> $request->input('artist'), 'genre_id' => $request->input('genre_id')]);
        //OR ->appends(['artist' => $request->artist, 'genre_id' => $request->genre_id]);
        foreach ($records as $record) {
            $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-250.jpg";
        }
        $genres = Genre::orderBy("name")            // short version of orderBy('name', 'asc')
        ->has('records')
            ->withCount('records')
            ->get()
            ->transform(function ($item, $key) {
                $item->name = ucfirst($item->name);
                // Set first letter of name to uppercase and add the counter
                $item->Genrename = $item->name . ' (' . $item->records_count . ')';
                // Remove all fields that you don't use inside the view
                unset($item->created_at, $item->updated_at, $item->records_count);
                return $item;
            });
        $result = compact('genres', 'records');     // $result = ['genres' => $genres, 'records' => $records]
        Json::dump($result);
        return view('shop.index', $result);
    }

    public function show($id)
    {

        $record = Record::with('genre')->findOrFail($id);
        // dd($record);
        // Real path to cover image
        $record->cover = $record->cover ?? "https://coverartarchive.org/release/$record->title_mbid/front-250.jpg";
        // Combine artist + title
        $record->title = $record->artist . ' - ' . $record->title;
        // Links to MusicBrainz API (used by jQuery)
        // https://wiki.musicbrainz.org/Development/JSON_Web_Service
        $record->artistUrl = 'https://musicbrainz.org/ws/2/artist/' . $record->artist_mbid . '?inc=url-rels&fmt=json';
        $record->recordUrl = 'https://musicbrainz.org/ws/2/release/' . $record->title_mbid . '?inc=recordings+url-rels&fmt=json';
        // If stock > 0: button is green, otherwise the button is red
        $record->btnClass = $record->stock > 0 ? 'btn-outline-success' : 'btn-outline-danger';
        // You can't overwrite the attribute genre (object) with a string, so we make a new attribute
        $record->genreName = $record->genre->name;
        // Remove attributes you don't need for the view
        $result = compact('record');
        Json::dump($result);
        return view('shop.show', $result);  // Pass $result to the view
    }

    public function index_alt(){

        $genres = Genre::orderBy("name")            // short version of orderBy('name', 'asc')
            ->has('records')
            ->with('records')
            ->get()
            ->transform(function ($item) {
                $item->name = ucfirst($item->name);
                // Set first letter of name to uppercase and add the counter
                unset($item->created_at, $item->updated_at, $item->records_count);
                return $item;
            });

        $result = compact('genres');
        Json::dump($result);
        return view('shop.alternative',$result);
    }

    public function records()
    {
        return $this->hasMany('App\Record');   // a genre has many records
    }

}
