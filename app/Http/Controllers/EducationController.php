<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Education;
use Session;
use View;
use App\Helper;

class EducationController extends Controller
{
    protected $education;

    public function __construct(Education $education)
    {
        $this->education = $education;
    }
    public function index(Request $request)
    {
        if (!empty($_GET['keyword'])) {
            $keyword = $_GET['keyword'];
            $educations = $this->education::where('title', 'like', '%' . $keyword . '%')->paginate(7)->setPath('');
            $pagination = $education->appends(
                array(
                    'keyword' => $request->get('keyword')
                )
            );
        } else {
            $educations = $this->education->paginate(10);
        }
        if (file_exists(resource_path('views/extend/back-end/admin/education/index.blade.php'))) {
            return View::make(
                'extend.back-end.admin.education.index',
                compact('educations')
            );
        } else {
            return View::make(
                'back-end.admin.education.index',
                compact('educations')
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
                'education_title' => 'required',
            ]
        );
        $this->education->saveEducation($request);
        Session::flash('message', trans('lang.save_education'));
        return Redirect::back();
    }
    public function edit($id)
    {
        if (!empty($id)) {
            $educations = $this->education::find($id);
            if (!empty($educations)) {
                if (file_exists(resource_path('views/extend/back-end/admin/education/edit.blade.php'))) {
                    return View::make(
                        'extend.back-end.admin.education.edit', compact('id', 'educations')
                    );
                } else {
                    return View::make(
                        'back-end.admin.education.edit', compact('id', 'educations')
                    );
                }
                Session::flash('message', trans('lang.education_updated'));
                return Redirect::to('admin/education');
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
                'education_title' => 'required',
            ]
        );
        $this->education->updateEducation($request, $id);
        Session::flash('message', trans('lang.education_updated'));
        return Redirect::to('admin/education');
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
            $this->education::where('id', $id)->delete();
            $json['education'] = 'success';
            $json['message'] = trans('lang.education_deleted');
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
            $this->education::where("id", $id)->delete();
        }
        if (!empty($checked)) {
            $json['education'] = 'success';
            $json['message'] = trans('lang.education_deleted');
            return $json;
        } else {
            $json['type'] = 'error';
            $json['message'] = trans('lang.something_wrong');
            return $json;
        }
    }
}
