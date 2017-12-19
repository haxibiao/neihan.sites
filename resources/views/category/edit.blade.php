{{-- @extends('layouts.app')

@section('title')编辑分类@stop

@section('keywords')
  编辑分类
@stop

@section('description')
  编辑分类
@stop

@section('content')
  <div class="container">
      <ol class="breadcrumb">
        <li><a href="/">{{ config('app.name') }}</a></li>
        <li><a href="/category">分类列表</a></li>
        <li class="active">编辑分类</li>
      </ol>
    <div class="col-md-6">
      {!! Form::open(['method' => 'put', 'route' => ['category.update', $category->id], 'class' => 'form-horizontal']) !!}
  
          <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
              {!! Form::label('name', '分类名称') !!}
              {!! Form::text('name', $category->name, ['class' => 'form-control', 'required' => 'required']) !!}
              <small class="text-danger">{{ $errors->first('name') }}</small>
          </div>

          <div class="form-group{{ $errors->has('name_en') ? ' has-error' : '' }}">
              {!! Form::label('name_en', '分类英文名') !!}
              {!! Form::text('name_en', $category->name_en, ['class' => 'form-control', 'required' => 'required']) !!}
              <small class="text-danger">{{ $errors->first('name_en') }}</small>
          </div>

          <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
              {!! Form::label('type', '系统分类') !!}
              {!! Form::select('type',['article'=>'文章','video'=>'视频'], Request::get('type'), ['id' => 'type', 'class' => 'form-control', 'required' => 'required']) !!}
              <small class="text-danger">{{ $errors->first('type') }}</small>
          </div>
 
           <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
               {!! Form::label('parent_id', '上级分类') !!}
               {!! Form::select('parent_id',$categories, null, ['id' => 'parent_id', 'class' => 'form-control', 'required' => 'required']) !!}
               <small class="text-danger">{{ $errors->first('category_id') }}</small>
           </div>

          <div class="form-group{{ $errors->has('order') ? ' has-error' : '' }}">
              {!! Form::label('order', '权重(越大越靠前)') !!}
              {!! Form::text('order', $category->order, ['class' => 'form-control', 'required' => 'required']) !!}
              <small class="text-danger">{{ $errors->first('order') }}</small>
          </div>

          <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
              {!! Form::label('description', '分类描述') !!}
              {!! Form::textarea('description', $category->description, ['class' => 'form-control']) !!}
              <small class="text-danger">{{ $errors->first('description') }}</small>
          </div>

          <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
              {!! Form::label('logo', '分类图标') !!}
              {!! Form::file('logo') !!}
              <p class="help-block">请选择少于2M的图片，格式必须是jpg,png</p>
              <small class="text-danger">{{ $errors->first('logo') }}</small>
          </div>
      
          <div class="btn-group pull-right">
              {!! Form::reset("重置", ['class' => 'btn btn-warning']) !!}
              {!! Form::submit("保存", ['class' => 'btn btn-success']) !!}
          </div>

          {!! Form::hidden('user_id', $user->id) !!}
      
      {!! Form::close() !!}
    </div>
  </div>
@stop --}}

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
                                <img src="{{ $category->logo }}" id=category_logo/>
                            </div>
                        </td>
                        <td>
                            <a class="btn_hollow" href="javascript:;">
                                {{-- <input class="hide" type="file" unselectable="on" name="logo"/> --}}
                            {{-- <div class="form-group{{ $errors->has('photo') ? ' has-error' : '' }}"> --}}
                                {!! Form::label('logo', ' 上传专题封面') !!}
                                {!! Form::file('logo', []) !!}
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
                            <div class="user_add">
                                {!! Form::text('is_admin', topAdmins_json($category->topAdmins()), ['class' => 'form-control','placeholder'=>"输入用户名"]) !!}
                                <ul class="dropdown-menu">
                                </ul>
                            </div>
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
                {!! Form::submit("编辑专题", ['class' => 'btn_success']) !!}
                    </div>
                  {!! Form::close() !!}
      
        </div>
    </div>
</div>

<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
   <small class="text-danger">{{ $errors->first('name') }}</small>
</div>
@stop

{{-- @push('scripts')
   <script type="text/javascript">
      function uploadlogo(){
          $('#upload_logo').on('submit',function(e){
            e.preventDefault();
            $(this).ajaxSubmit({
              type:"POST",
              url: "{{ route('image.store') }}",

              contentType:false,
              cache: false,
                processData: false,
                success:function(data){
                    var logo_url=data;
                    console.log(logo_url);
                    var category_log=document.getElementById("upload_logo");
                       category_log.val(image_url);
                },
                error:function(data){
                 $("#error")
                    .text("上传失败!")
                    .css("color", "#FFA500");
                }

            });
          });
      }
   </script>
@endpush --}}
