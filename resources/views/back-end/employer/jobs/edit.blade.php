@extends(file_exists(resource_path('views/extend/back-end/master.blade.php')) ? 'extend.back-end.master' : 'back-end.master')
@section('content')
<style>
.project_cost::-webkit-input-placeholder { padding-left:15px!important; }
.project_cost::placeholder { padding-left:15px!important; }
.project_cost:focus{padding-left:35px!important;}
.project_cost{padding: 12px 20px!important;}

</style>
    <div class="wt-haslayout wt-dbsectionspace">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 float-left" id="post_job">
                @if (Session::has('error'))
                    <div class="flash_msg">
                        <flash_messages :message_class="'danger'" :time='5' :message="'{{{ Session::get('error') }}}'" v-cloak></flash_messages>
                    </div>
                @endif
                <div class="preloader-section" v-if="loading" v-cloak>
                    <div class="preloader-holder">
                        <div class="loader"></div>
                    </div>
                </div>
                <div class="wt-haslayout wt-post-job-wrap">
                    {!! Form::open(['url' => 'job/update-job', 'class' =>'post-job-form wt-haslayout', 'id' => 'job_edit_form', '@submit.prevent'=>'updateJob("'.$job->id.'")']) !!}
                        <div class="wt-dashboardbox">
                            <div class="wt-dashboardboxtitle">
                                <h2>{{ trans('lang.edit_job') }}</h2>
                            </div>
                            <div class="wt-dashboardboxcontent">
                                <div class="wt-jobdescription wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_desc') }}</h2>
                                    </div>
                                    
                                    <div class="wt-formtheme wt-userform wt-userformvtwo la-job-details-form">
                                        <fieldset>
                                            <div class="form-group">
                                                {!! Form::text('title', $job->title, array('class' => 'form-control', 'placeholder' => trans('lang.job_title'))) !!}
                                            </div>
                                            <div class="form-group form-group-half wt-formwithlabel">
                                            <span class="wt-select">
                                                {!! Form::select('genre', $genre, e($job->genre), array('class' => '', 'placeholder' => 'Genre', )) !!}
                                            </span>
                                        </div>
                                        <div class="form-group form-group-half wt-formwithlabel job-cost-input inputWithIcon">
                                            {!! Form::text('total_no_word', e($job->total_no_word), array('class' => 'total_no_word', 'placeholder' => 'Total Number of words', 'onkeypress'=>'return IsNumeric(event);')) !!}
                                            
                                        </div>
                                            <!--<div class="form-group form-group-half wt-formwithlabel">
                                                <span class="wt-select">
                                                        {!! Form::select('project_levels', $project_levels , e($job->project_level)) !!}
                                                    </span>
                                            </div>
                                            <div class="form-group form-group-half wt-formwithlabel">
                                                <span class="wt-select">
                                                    {!! Form::select('job_duration', $job_duration , e($job->duration)) !!}
                                                </span>
                                            </div>
                                            <div class="form-group form-group-half wt-formwithlabel">
                                                <span class="wt-select">
                                                    {!! Form::select('freelancer_type', $freelancer_level_list, e($job->freelancer_type)) !!}
                                                </span>
                                            </div>
                                            <div class="form-group form-group-half wt-formwithlabel">
                                                <span class="wt-select">
                                                    {!! Form::select('english_level', $english_levels, e($job->english_level)) !!}
                                                </span>
                                            </div>-->
                                            <div class="form-group form-group-half wt-formwithlabel job-cost-input">
                                                <i class="fa fa-usd" aria-hidden="true" style="position:absolute;margin-top:17px;margin-left:20px;font-size:13px;"></i>
                                                {!! Form::text('project_cost', $job->price, array('style'=>'padding: 12px 20px!important;', 'class' => 'form-control', 'placeholder' => trans('lang.project_cost'), 'onkeypress'=>'return IsNumeric(event);')) !!}
                                            </div>
                                            <job-expiry 
                                                :db_expiry_date="'{{$job->expiry_date}}'" 
                                                :ph_expiry_date="trans('lang.project_expiry')"
                                                :weekdays="'{{json_encode($weekdays)}}'"
                                                :months="'{{json_encode($months)}}'">
                                            </job-expiry>
                                            <div class="form-group form-group-half">
                                                <div class="">
                                                    <div class="wt-title mb-2">Job Type</div>

                                                    <span class="wt-radio">
                                                        <input type="radio" name="project_type" id="project_type-flexible" value="flexible" {{ $job->project_type == 'flexible' ? 'checked' : '' }}>
                                                        <label for="project_type-flexible">Flexible</label>
                                                    </span>
                                                    <span class="wt-radio mt-1">
                                                        <input type="radio" name="project_type" id="project_type-fixed" value="fixed" {{ $job->project_type == 'fixed' ? 'checked' : '' }}>
                                                        <label for="project_type-fixed">Fixed</label>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="form-group form-group-half wt-formwithlabel d-none">
                                                {!! Form::text('flexible_days_req', $job->flexible_days_req, array('class' => 'form-control flexible_days_req', 'placeholder' => 'No of days required to complete the Job', 'onkeypress'=>'return IsNumeric(event);')) !!}
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>
                                <div class="wt-jobskills wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_cats') }}</h2>
                                    </div>
                                    <div class="wt-divtheme wt-userform wt-userformvtwo">
                                        <div class="form-group">
                                            <span class="wt-select">
                                                {!! Form::select('categories[]', $categories, $job->categories, array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_job_cats'))) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="wt-jobskills wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.langs') }}</h2>
                                    </div>
                                    <div class="wt-divtheme wt-userform wt-userformvtwo">
                                        <div class="form-group">
                                            <span class="wt-select">
                                                {!! Form::select('languages[]', $languages, $job->languages, array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_lang'))) !!}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="wt-jobdetails wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.job_dtl') }}</h2>
                                    </div>
                                    <div class="wt-formtheme wt-userform wt-userformvtwo">
                                        {!! Form::textarea('description', $job->description, ['class' => 'wt-tinymceeditor', 'id' => 'wt-tinymceeditor', 'placeholder'
                                        => trans('lang.job_dtl_note')]) !!}
                                    </div>
                                </div>
                                <div class="wt-jobskills wt-tabsinfo la-jobedit">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.skills_req') }}</h2>
                                    </div>
                                    <div class="la-jobedit-content">
                                        <job_skills :placeholder="'select skills'"></job_skills>
                                    </div>
                                </div>
                                <!--<div class="wt-joblocation wt-tabsinfo la-location-edit">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.your_loc') }}</h2>
                                    </div>
                                    <div class="wt-formtheme wt-userform">
                                        <fieldset>
                                            <div class="form-group form-group-half">
                                                <span class="wt-select">
                                                        {!! Form::select('locations', $locations, $job->location_id, array('class' => 'skill-dynamic-field', 'placeholder' => trans('lang.select_locations'))) !!}
                                                    </span>
                                            </div>
                                            <div class="form-group form-group-half">
                                                {!! Form::text( 'address', $job->address, ['id'=>"pac-input", 'class' =>'form-control', 'placeholder' => trans('lang.your_address')] ) !!}
                                            </div>
                                            <div class="form-group wt-formmap">
                                                @include('includes.map')
                                            </div>
                                            <div class="form-group form-group-half">
                                                {!! Form::text( 'longitude', $job->longitude, ['id'=>"lng-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_logitude')])
                                                !!}
                                            </div>
                                            <div class="form-group form-group-half">
                                                {!! Form::text( 'latitude', $job->latitude, ['id'=>"lat-input", 'class' =>'form-control', 'placeholder' => trans('lang.enter_latitude')]) !!}
                                            </div>
                                        </fieldset>
                                    </div>
                                </div>-->
                                <div class="wt-featuredholder wt-tabsinfo">
                                    <div class="wt-tabscontenttitle">
                                        <h2>{{ trans('lang.is_featured') }}</h2>
                                        <div class="wt-rightarea">
                                            <div class="wt-on-off float-right">
                                                <switch_button v-model="is_featured">{{{ trans('lang.make_job_featured') }}}</switch_button>
                                                <input type="hidden" :value="is_featured" name="is_featured">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="wt-attachmentsholder">
                                    <div class="lara-attachment-files">
                                        <div class="wt-tabscontenttitle">
                                            <h2>{{ trans('lang.attachments') }}</h2>
                                            <div class="wt-rightarea">
                                                <div class="wt-on-off float-right">
                                                    <switch_button v-model="show_attachments">{{{ trans('lang.attachments_note') }}}</switch_button>
                                                    <input type="hidden" :value="show_attachments" name="show_attachments">
                                                </div>
                                            </div>
                                        </div>
                                        <job_attachments :temp_url="'{{url('job/upload-temp-image')}}'"></job_attachments>
                                        <div class="form-group input-preview">
                                            <ul class="wt-attachfile dropzone-previews">

                                            </ul>
                                        </div>
                                        @if (!empty($attachments))
                                            @php $count = 0; @endphp
                                            <div class="form-group input-preview">
                                                <ul class="wt-attachfile">
                                                    @foreach ($attachments as $key => $attachment)
                                                        <li id="attachment-item-{{$key}}">
                                                            <span>{{{Helper::formateFileName($attachment)}}}</span>
                                                            <em>
                                                                @if (Storage::disk('local')->exists('uploads/jobs/'.$job->user_id.'/'.$attachment))
                                                                    {{ trans('lang.file_size') }} {{{Helper::bytesToHuman(Storage::size('uploads/jobs/'.$job->user_id.'/'.$attachment))}}}
                                                                @endif
                                                                <a href="{{{route('getfile', ['type'=>'jobs','attachment'=>$attachment,'id'=>$job->user_id])}}}"><i class="lnr lnr-download"></i></a>
                                                                <a href="#" v-on:click.prevent="deleteAttachment('attachment-item-{{$key}}')"><i class="lnr lnr-cross"></i></a>
                                                            </em>
                                                            <input type="hidden" value="{{{$attachment}}}" class="" name="attachments[{{$key}}]">
                                                        </li>
                                                        @php $count++; @endphp
                                                    @endforeach
                                                    <div class="dropzone-previews"></div>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="wt-updatall">
                            <i class="ti-announcement"></i>
                            <span>{{{ trans('lang.save_changes_note') }}}</span> {!! Form::submit(trans('lang.btn_save_update'), ['class' => 'wt-btn',
                            'id'=>'submit-profile']) !!}
                        </div>
                    {!! form::close(); !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script type="text/javascript">
    function toggleFlexibleDaysField() {
        if($('[name="project_type"]:checked').val() == 'flexible') {
            $('.form-group:has([name="flexible_days_req"])').removeClass('d-none');
        } else {
            $('.form-group:has([name="flexible_days_req"])').addClass('d-none');
        }
    }

    $(document).on('change', '[name="project_type"]', function() {
        toggleFlexibleDaysField();
    });

    $(document).ready(function() {
        toggleFlexibleDaysField();
    });
</script>
@endpush
