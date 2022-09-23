<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Genre;
use Session;
use View;
use App\Helper;

class GenreController extends Controller
{
    protected $genre;

    public function __construct(Genre $genre)
    {
        $this->genre = $genre;
    }
    public function index(Request $request)
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $genres = $this->genre::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $genre->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $genres = $this->genre->paginate(10);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/genre/index.blade.php'))) {
            return View::make(
                'extend.back-end.admin.genre.index',
                compact('genres')
            );
        } else {
            return View::make(
                'back-end.admin.genre.index',
                compact('genres')
            );
        }
    }
    public function store(Request $request)
    {
        $server_verification = Helper::worketicIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        $this->validate(
            $request, [
                'genre_title' => 'required',
            ]
        );
        $this->genre->saveGenre($request);
        Session::flash('message', trans('lang.save_genre'));
        return Redirect::back();
    }
    public function edit($id)
    {
        if (!empty($id)) {
            $genres = $this->genre::find($id);
            if (!empty($genres)) {
                if (file_exists(resource_path('views/extend/back-end/admin/genre/edit.blade.php'))) {
                    return View::make(
                        'extend.back-end.admin.genre.edit', compact('id', 'genres')
                    );
                } else {
                    return View::make(
                        'back-end.admin.genre.edit', compact('id', 'genres')
                    );
                }
                Session::flash('message', trans('lang.genre_updated'));
                return Redirect::to('admin/genre');
            }
        }
    }
    public function update(Request $request, $id)
    {
        $server_verification = Helper::worketicIsDemoSite();
        if (!empty($server_verification)) {
            Session::flash('error', $server_verification);
            return Redirect::back();
        }
        $this->validate(
            $request, [
                'genre_title' => 'required',
            ]
        );
        $this->genre->updateGenre($request, $id);
        Session::flash('message', trans('lang.genre_updated'));
        return Redirect::to('admin/genre');
    }
    public function destroy(Request $request)
    {
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $id = $request['id'];
        if (!empty($id)) {
            $this->genre::where('id', $id)->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.genre_deleted');
            return $json;
        }
    }
    public function deleteSelected(Request $request)
    {
        $server = Helper::worketicIsDemoSiteAjax();
        if (!empty($server)) {
            $json['type'] = 'error';
            $json['message'] = $server->getData()->message;
            return $json;
        }
        $json = array();
        $checked = $request['ids'];
        foreach ($checked as $id) {
            $this->genre::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.genre_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }

}
