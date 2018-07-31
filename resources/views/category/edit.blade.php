@extends('layouts.app')

@section('title')编辑专题@stop

@section('keywords')
  编辑专题
@stop

@section('description')
  编辑专题
@stop

@section('content')
  <div id="category-editor">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 main">
        <h3>编辑专题</h3>
      {!! Form::open(['method' => 'PUT', 'route' => ['category.update', $category->id], 'class' => 'form-horizontal', 'files'=>1]) !!}
        <table class="form-menu">
            <tbody>
                <tr>
                    <td>
                        <div class="avatar-category"><img src="{{ $category->logo() ? : '/images/dissertation_04.jpg' }}" alt="" id="previewImage"></div>
                    </td>
                    <td>
                        <a rel="noreferrer" class="btn-base btn-hollow btn-md btn-file">
                            <input id="logo" unselectable="on" name="logo" type="file"> 上传专题封面
                        </a>
                    </td>
                </tr>
                 <tr style="display: none;" id="upload_app_logo">
                        <td>
                            <div class="avatar-category"><img src="{{ $category->logo_app ? $category->logo_app : '/images/dissertation_04.jpg' }}" alt="" id="preview_app_ogo"></div>
                        </td>
                        <td>
                            <a rel="noreferrer" class="btn-base btn-hollow btn-md btn-file">
                                <input id="logo_app" unselectable="on" name="logo_app" type="file"> 上传APP图标
                            </a>
                        </td>
                    </tr>
                <tr>
                    <td class="setting-title">名称</td>
                    <td>
                        <input type="text" class="input-style" name="name" placeholder="填写名称，不超过50字" value="{{ $category->name }}">
                    </td>
                </tr>
                <tr>
                    <td class="setting-title">英文名称</td>
                    <td>
                        <input type="text" class="input-style" name="name_en" placeholder="填写英文名称，建议用中文全拼，英语单词建议用-隔开" value="{{ $category->name_en }}">
                    </td>
                </tr>
                <tr>
                    <td class="setting-title pull-left">描述</td>
                    <td>
                        <textarea placeholder="填写描述" name="description">{{ $category->description }}</textarea>
                    </td>
                </tr>
                <tr class="add-manager">
                    <td class="setting-title pull-left">其他管理员</td>
                    <td>                       
                        <user-select users="{{ json_encode($category->admins->pluck('name','id')) }}"></user-select>
                    </td>
                </tr>
                <tr>
                    <td class="setting-title setting-verticle">是否允许投稿</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="radio" name="status" {{ $category->status == 1 ? 'checked="checked"' : '' }} value="1"><span>允许</span></div>
                            <div class="col-xs-18">
                                <input type="radio" name="status" {{ $category->status == 0 ? 'checked="checked"' : '' }} value="0"><span>不允许</span></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="setting-title setting-verticle">投稿是否需要审核</td>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="radio" name="request_status" {{ $category->request_status == 1 ? 'checked="checked"' : '' }} checked="checked" value="1" {{ $category->status == 0 ? 'disabled':'' }}> <span>需要</span></div>
                            <div class="col-xs-18">
                                <input type="radio" name="request_status" {{ $category->request_status == 0 ? 'checked="checked"' : '' }} value="0" {{ $category->status == 0 ? 'disabled':'' }}> <span>不需要</span></div>
                        </div>
                    </td>
                </tr>

                <tr>
                        <td class="setting-title setting-verticle">是否为官方专题</td>
                        <td>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="radio" name="is_official" {{ $category->is_official== 1 ?'checked="checked"': '' }} value="1"> <span>是</span>
                                </div>
                                <div class="col-xs-18">
                                    <input type="radio" name="is_official" {{ $category->is_official == 0 ? 'checked="checked"' : '' }} value="0"> <span>否</span>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr class="disabled">
                        <td class="setting-title setting-verticle">是否上App</td>
                        <td>
                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="radio" name="is_for_app" {{ $category->is_for_app==1 ?'checked="checked"': '' }} value="1" disabled> <span>是</span>
                                </div>
                                <div class="col-xs-18">
                                    <input type="radio" name="is_for_app" {{ $category->is_for_app==0 ?'checked="checked"': '' }} value="0" disabled> <span>否</span>
                                </div>
                            </div>
                        </td>
                    </tr>
            </tbody>
        </table>
        {!! Form::hidden('user_id', Auth::id()) !!}
        {!! Form::hidden('type', $category->type) !!}
        <button type="submit" class="btn-base btn-handle btn-bold">保存专题</button>
      {!! Form::close() !!}
    </div>
  </div>
@stop


@push('scripts')
  <script>
   

    function toggleUploadAppLogo(target) {
        if($(target).val()>0) {
            $('[name="is_for_app"]').prop('disabled',false).parents('tr').removeClass('disabled');
            if($('[name="is_for_app"]:checked').val()>0){
                $('#upload_app_logo').fadeIn();
            }
        }else {
            $('[name="is_for_app"]').prop('disabled','disabled').parents('tr').addClass('disabled');
            $('#upload_app_logo').fadeOut();
        }
    }

        function preview(input,target) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    target.attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(function() {
            toggleUploadAppLogo($('[name="is_official"]'));

            $("#logo").change(function(){
                preview(this,$('#previewImage'));
            });

            $("#logo_app").change(function(){
                preview(this,$('#preview_app_ogo'));
            });

            $('[name="status"]').on('change',function(){
                if($(this).val()>0) {
                    $('[name="request_status"]').prop('disabled',false).parents('tr').removeClass('disabled');
                }else {
                    $('[name="request_status"]').prop('disabled','disabled').parents('tr').addClass('disabled');
                }
            });

            $('[name="is_official"]').on('change',function(){
                toggleUploadAppLogo(this);
            });
            
            $('[name="is_for_app"]').on('change',function(){
                if($(this).val()>0) {
                    $('#upload_app_logo').fadeIn();
                }else {
                    $('#upload_app_logo').fadeOut();
                }
            });
        });

  </script>
@endpush