<div class="row">
    <div class="col-lg-12">
        <div class="wt-location wt-tabsinfo">
            <div class="wt-tabscontenttitle">
                <h2>{{{ trans('lang.select_genre') }}}</h2>
            </div>
            <div class="wt-settingscontent">
        
                <div class="wt-formtheme wt-userform">
                    <div class="form-group">
                        {!! Form::select('genre[]', $genre, $user_genre ,array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_genre'))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="wt-location wt-tabsinfo">
            <div class="wt-tabscontenttitle">
                <h2>{{{ trans('lang.select_type') }}}</h2>
            </div>
            <div class="wt-settingscontent">
        
                <div class="wt-formtheme wt-userform">
                    <div class="form-group">
                        {!! Form::select('type[]', $type, $user_type ,array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_type'))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="wt-location wt-tabsinfo">
            <div class="wt-tabscontenttitle">
                <h2>{{{ trans('lang.select_lang') }}}</h2>
            </div>
            <div class="wt-settingscontent">
        
                <div class="wt-formtheme wt-userform">
                    <div class="form-group">
                        {!! Form::select('languages[]', $languages, $user_languages ,array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_lang'))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="wt-location wt-tabsinfo">
            <div class="wt-tabscontenttitle">
                <h2>{{{ trans('lang.experience') }}}</h2>
            </div>
            <div class="wt-settingscontent">
        
                <div class="wt-formtheme wt-userform">
                    <div class="form-group">
                        {!! Form::number( 'experience1', $user_experience, ['id'=>'experience1','class' =>'form-control', 'placeholder' => trans('lang.experience'), "maxlength"=>"2", 'pattern'=>"/^-?\d+\.?\d*$/", 'onkeypress'=>"return IsNumeric(event);"  ] ) !!}
                        <span id="number_exp_error" class="text-danger"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12">
        <div class="wt-location wt-tabsinfo">
            <div class="wt-tabscontenttitle">
                <h2>{{{ trans('lang.select_education') }}}</h2>
            </div>
            <div class="wt-settingscontent">
        
                <div class="wt-formtheme wt-userform">
                    <div class="form-group">
                        {!! Form::select('education1[]', $education, $user_education ,array('class' => 'chosen-select', 'multiple', 'data-placeholder' => trans('lang.select_education'))) !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function numberOnly(id) {
    var element = document.getElementById(id);
    element.value = element.value.replace(/[^0-9]/gi, "");
}
function edValueKeyPress() {
    var edValue = document.getElementById("experience1");
    s = edValue.value;
    if(s>=75){
        document.getElementById("number_exp_error").innerHTML = "Experience should not be above 75 years";
        document.getElementById("submit-profile").disabled = true;
    } else if(s<=75) {
        document.getElementById("number_exp_error").innerHTML = "";
        document.getElementById("submit-profile").disabled = false;

    }
}
</script>