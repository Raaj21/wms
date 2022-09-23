<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Education extends Model
{
    use HasFactory;

    protected $fillable = array(
        'title', 'slug', 'description',
    );

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function saveEducation($request)
    {
        if (!empty($request)) {
            $this->title = filter_var($request['education_title'], FILTER_SANITIZE_STRING);
            $this->slug = filter_var($request['education_title'], FILTER_SANITIZE_STRING);
            $this->description = filter_var($request['education_desc'], FILTER_SANITIZE_STRING);
            return $this->save();
        }
    }
    public function updateEducation($request, $id)
    {
        if (!empty($request)) {
            $langs = self::find($id);
            if ($langs->title != $request['education_title']) {
                $langs->slug = filter_var($request['education_title'], FILTER_SANITIZE_STRING);
            }
            $langs->title = filter_var($request['education_title'], FILTER_SANITIZE_STRING);
            $langs->description = filter_var($request['education_desc'], FILTER_SANITIZE_STRING);
            $langs->save();
        }
    }
}
