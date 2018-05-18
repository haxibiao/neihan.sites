
@extends('layouts.app')

@section('title')
    爱你城
@stop
@section('content')
<div id="category_new">
    <div class="container">
        <div class="row">
            <h3>
                新建专题
            </h3>
         {!! Form::open(['method' => 'PUT', 'route' => ['category.update',$category->id], 'class' => 'form-horizontal','enctype' => "multipart/form-data"]) !!}
            <table>
                <thead>
                    <tr>
                        <th class="setting_head">
                        </th>
                        <th>
                        </th>
                    </tr>
                </thead>
           
                <tbody class="base">
                    <tr>
                        <td>
                            <div class="avatar_collection">
                                <img src="{{ $category->logo }}" id="previewImage" />
                            </div>
                        </td>
                        <td>
                            <a class="btn_base btn_hollow btn_follow_lg" href="javascript:;">
                                {{-- <input class="hide" type="file" unselectable="on" name="logo"/> --}}
                            {{-- <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}"> --}}
                                {!! Form::label('logo', ' 上传专题封面') !!}
                                {!! Form::file('logo', ['id'=>'logo','unselectable'=>"on"]) !!}
                            {{-- </div> --}}
                            </a>

                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title">
                            {!! Form::label('name', '名称') !!}  
                        </td>
                        <td>
                            {!! Form::text('name', $category->name, ['class' => 'form-control', 'required' => 'required','placeholder'=>"填写名称，不超过50字"]) !!}
                        </td>
                    </tr>

                    <tr>
                        <td class="setting_title">
                            {!! Form::label('name_en', '英文名称') !!}  
                        </td>
                        <td>
                            {!! Form::text('name_en', $category->name_en, ['class' => 'form-control', 'required' => 'required','placeholder'=>"填写名称，不超过50字"]) !!}
                        </td>
                    </tr>

                    <tr>
                        <td class="setting_title">
                            {!! Form::label('order', '权重(越大越靠前)') !!}  
                        </td>
                        <td>
                            {!! Form::text('order', $category->order, ['class' => 'form-control', 'required' => 'required','placeholder'=>"填写"]) !!}
                        </td>
                    </tr>

                    <tr>
                        <td class="setting_title pull-left">
                             {!! Form::label('description', '描述') !!}
                        </td>
                        <td>
                            {!! Form::textarea('description', $category->description, ['class' => 'form-control', 'required' => 'required','placeholder'=>"填写描述"]) !!}
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title pull-left">
                            {!! Form::label('is_admin', '其他管理员') !!} 
                        </td>
                        <td>
                            <user-select users="{{ json_encode($category->admins->pluck('name','id')) }}"></user-select>
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title">
                            是否允许投稿
                        </td>
                        <td>
                            <div>
                                <label for="submission">
                                   {!! Form::radio('submission', true,  true, ['id' => 'radio_id']) !!} 允许
                                </label> 
                            </div>
                            <div>
                                <label for="submission">
                                   {!! Form::radio('submission', false,  null, ['id' => 'radio_id']) !!} 不允许
                                </label> 
                            </div>
                        </td>
                    </tr>
{{--                     <tr>
                        <td class="setting_title">
                            投稿是否需要审核
                        </td>
                        <td>
                            <div>
                                <label for="examine">
                                   {!! Form::radio('examine', true,  true, ['id' => 'radio_id']) !!} 需要
                                </label>   
                            </div>
                            <div>
                                <label for="examine">
                                   {!! Form::radio('examine', true,  true, ['id' => 'radio_id']) !!} 不需要
                                </label>                                                                  
                            </div>
                        </td>
                    </tr> --}}
                </tbody>

                {!! Form::hidden('user_id', Auth::id()) !!}

            </table>
                       <div class="pull-right">
                {!! Form::submit("编辑专题", ['class' => 'btn_base btn_follow']) !!}
                    </div>
                  {!! Form::close() !!}
      
        </div>
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
   <small class="text-danger">{{ $errors->first('name') }}</small>
</div>
@stop

@push('scripts')
<script type="text/javascript">
        function preview(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#previewImage').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }


    $(function() {
        $("#logo").change(function(){
            preview(this);
        });
    });
</script>>
@endpush

