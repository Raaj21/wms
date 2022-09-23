@extends(file_exists(resource_path('views/extend/front-end/master.blade.php')) ? 
'extend.front-end.master':
 'front-end.master', ['body_class' => 'wt-innerbgcolor'] )
 @push('stylesheets')
    <link href="{{ asset('css/chosen.css') }}" rel="stylesheet">
    <link href="{{ asset('css/basictable.css') }}" rel="stylesheet">
@endpush
@section('title'){{ $job_list_meta_title }} @stop
@section('description', $job_list_meta_desc)
@section('content')
 @php $breadcrumbs = Breadcrumbs::generate('searchResults'); @endphp
    @if (file_exists(resource_path('views/extend/front-end/includes/inner-banner.blade.php')))
        @include('extend.front-end.includes.inner-banner', 
            ['title' => trans('lang.jobs'), 'inner_banner' => $job_inner_banner, 'show_banner' => $show_job_banner]
        )
    @else 
        @include('front-end.includes.inner-banner', 
            ['title' =>  trans('lang.jobs'), 'inner_banner' => $job_inner_banner, 'show_banner' => $show_job_banner ]
        )
    @endif  
   
    <div class="wt-haslayout wt-main-section" id="jobs">
        @if (Session::has('payment_message'))
            @php $response = Session::get('payment_message') @endphp
            <div class="flash_msg">
                <flash_messages :message_class="'{{{$response['code']}}}'" :time ='5' :message="'{{{ $response['message'] }}}'" v-cloak></flash_messages>
            </div>
        @endif
        <div class="wt-haslayout">
            <div class="container">
                <div class="row">
                    <div id="wt-twocolumns" class="wt-twocolumns wt-haslayout">
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 float-left">
                            @if (file_exists(resource_path('views/extend/front-end/jobs/filters.blade.php'))) 
                            
                                @include('extend.front-end.jobs.filters')
                            @else 
                           
                                @include('front-end.jobs.filters')
                            @endif
                        </div>
                       
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8 float-left">
                            <div class="wt-userlistingholder wt-haslayout">
                                @if (!empty($keyword))
                                    <div class="wt-userlistingtitle">
                                        <span>{{ trans('lang.01') }} {{$jobs->count()}} of {{$Jobs_total_records}} results for <em>"{{{$keyword}}}"</em></span>
                                    </div>
                                @endif
                                @if (!empty($jobs) && $jobs->count() > 0)
                                    @foreach ($jobs as $job)
                                        @php
                                       
                                            $job = \App\Job::find($job->id);
                                           //  dd($job);
                                            //$description = strip_tags(stripslashes($job->description));
                                            $description = strip_tags(stripslashes($job->description));
                                            $featured_class = $job->is_featured == 'true' ? 'wt-featured' : '';
                                            $user = Auth::user() ? \App\User::find(Auth::user()->id) : '';
                                            $project_type  = Helper::getProjectTypeList($job->project_type);
                                            $project_type = is_array($project_type) ? null : $project_type;
                                           $flag = !empty($job->location->flag) ? Helper::getLocationFlag($job->location->flag) :
                                                    '/images/img-01.png';
                                            $project_level = Helper::getProjectLevel($job->project_level);
                                            $project_level = is_array($project_level) ? null : $project_level;
                                        @endphp
                                        <div class="wt-userlistinghold wt-userlistingholdvtwo {{$featured_class}}">
                                            @if ($job->is_featured == 'true')
                                                <span class="wt-featuredtag"><img src="images/featured.png" alt="{{{ trans('ph.is_featured') }}}" data-tipso="Plus Member" class="template-content tipso_style"></span>
                                            @endif
                                            <div class="wt-userlistingcontent">
                                                <div class="wt-contenthead">
                                                    <div class="wt-title">
                                                        @if (!empty($job->employer->slug))
                                                            <a href="{{ url('profile/'.$job->employer->slug) }}"><i class="fa fa-check-circle"></i> {{{ Helper::getUserName($job->employer->id) }}}</a>
                                                        @endif
                                                        <h2><a href="{{ url('job/'.$job->slug) }}">{{{$job->title}}}</a></h2>
                                                    </div>
                                                    <div class="wt-description">
                                                        <p>{{ str_limit(html_entity_decode($description), 200) }}</p>
                                                    </div>
                                                    
                                                    
                                                    @if (!empty($job->skills))
                                                        <div class="wt-tag wt-widgettag">
                                                            @foreach ($job->skills as $skill )
                                                                <a href="{{{url('search-results?type=job&skills%5B%5D='.$skill->slug)}}}">{{$skill->title}}</a>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                      <ul class="wt-userlisting-breadcrumb">
                                                          
                                                            @if (!empty($job->location))
                                                            <li><span><img src="{{{ asset($flag)}}}" alt="Flag"> {{{ !empty($job->location->title) ? $job->location->title : '' }}}</span></li>
                                                        @endif
                                                            @if (!empty($job->price))
                                                            <li><span><i class="far fa-money-bill-alt"></i>
                                                                {{ (!empty($symbol['symbol'])) ? $symbol['symbol'] : '$' }}{{{ $job->price}}} {{ trans('lang.per_hour') }}</span>
                                                            </li>
                                                        @endif
                                                     @if (!empty($job->profile->type))
                                             @php
                                                         $search_type =$job->profile->type; 
                                                         if (!empty($search_type)) {
                                                         $filters = json_decode($search_type);
                                                         $type  = array();
                                                         if(isset($filters)){
                                                         $types = \App\Type::whereIn('id',$filters)->get();
                                                        }
                                                         
                                                         }
                                                         
                                                         @endphp
                                                    
                                                  
                                                                
                                                              
                                                                      @foreach( $types as $typesdata)
                                                                    <li><span><i class="far fa-folder"></i>
                                                                {{ (!empty($typesdata->title)) ? $typesdata->title : '' }}</span></li>
                                                                 @endforeach
                                                                  @endif
                                                                
                                                                </ul>
                                                    
                                                 
                                                    
                                                    
                                                    
                                                </div>
                                                <div class="wt-viewjobholder">
                                                    <ul>
                                                        @if (!empty($project_level))
                                                            <li><span><i class="fa fa-dollar-sign wt-viewjobdollar"></i>{{{$project_level}}}</span></li>
                                                        @endif
                                                        {{-- @if (!empty($job->location->title))
                                                            <li><span><img src="{{{asset(Helper::getLocationFlag($job->location->flag))}}}" alt="{{{ trans('lang.location') }}}"> {{{ $job->location->title }}}</span></li>
                                                        @endif --}}
                                                        <li><span><i class="far fa-keyboard"></i>{{{ $job->total_no_word }}} Words</span></li>
                                                        <li><span><i class="far fa-folder wt-viewjobfolder"></i>{{{ trans('lang.type') }}}: {{{$project_type}}} @if($job->project_type == 'flexible') ({{ $job->flexible_days_req }} Days) @endif</span></li>
                                                        {{--<li><span><i class="far fa-clock wt-viewjobclock"></i>{{{ Helper::getJobDurationList($job->duration)}}}</span></li>--}}
                                                        <li><span><i class="fa fa-tag wt-viewjobtag"></i>{{{ trans('lang.job_id') }}} {{{$job->code}}}</span></li>
                                                        @if (!empty($user->profile->saved_jobs) && in_array($job->id, unserialize($user->profile->saved_jobs)))
                                                            <li style=pointer-events:none;><a href="javascript:void(0);" class="wt-clicklike wt-clicksave"><i class="fa fa-heart"></i> {{trans("lang.saved")}}</a></li>
                                                        @else
                                                            <li>
                                                                <a href="javascrip:void(0);" class="wt-clicklike" id="job-{{$job->id}}" @click.prevent="add_wishlist('job-{{$job->id}}', {{$job->id}}, 'saved_jobs', '{{trans("lang.saved")}}')" v-cloak>
                                                                    <i class="fa fa-heart"></i>
                                                                    <span class="save_text">{{ trans('lang.click_to_save') }}</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                        @if(!empty($job->expiry_date))
                                                            <li><span><i class="far fa-hourglass"></i> {{{ Helper::calculateElapsedTime(strtotime($job->expiry_date)) }}}</span></li>
                                                        @endif
                                                        <li class="wt-btnarea"><a href="{{url('job/'.$job->slug)}}" class="wt-btn">{{{ trans('lang.view_job') }}}</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                  
                                    @if ( method_exists($jobs,'links') )
                                        {{ $jobs->links('pagination.custom') }}
                                    @endif
                                @else
                                    @if (file_exists(resource_path('views/extend/errors/no-record.blade.php'))) 
                                        @include('extend.errors.no-record')
                                    @else 
                                        @include('errors.no-record')
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
    
   
