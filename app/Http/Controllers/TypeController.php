<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Type;
use Session;
use View;
use App\Helper;

class TypeController extends Controller
{
    protected $type;

    public function __construct(Type $type)
    {
        $this->type = $type;
    }
    public function index(Request $request)
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $types = $this->type::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $type->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $types = $this->type->paginate(10);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/type/index.blade.php'))) {
            return View::make(
                'extend.back-end.admin.type.index',
                compact('types')
            );
        } else {
            return View::make(
                'back-end.admin.type.index',
                compact('types')
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
                'type_title' => 'required',
            ]
        );
        $this->type->saveType($request);
        Session::flash('message', trans('lang.save_type'));
        return Redirect::back();
    }
    public function edit($id)
    {
        if (!empty($id)) {
            $types = $this->type::find($id);
            if (!empty($types)) {
                if (file_exists(resource_path('views/extend/back-end/admin/type/edit.blade.php'))) {
                    return View::make(
                        'extend.back-end.admin.type.edit', compact('id', 'types')
                    );
                } else {
                    return View::make(
                        'back-end.admin.type.edit', compact('id', 'types')
                    );
                }
                Session::flash('message', trans('lang.type_updated'));
                return Redirect::to('admin/type');
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
                'type_title' => 'required',
            ]
        );
        $this->type->updateType($request, $id);
        Session::flash('message', trans('lang.type_updated'));
        return Redirect::to('admin/type');
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
            $this->type::where('id', $id)->delete();
            $json['type'] = 'success';
            $json['message'] = trans('lang.type_deleted');
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
            $this->type::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['type'] = 'success';
            $json['message'] = trans('lang.type_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
