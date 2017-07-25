@extends('layouts.app')

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
 
           <div class="form-group{{ $errors->has('parent_id') ? ' has-error' : '' }}">
               {!! Form::label('parent_id', '上级分类') !!}
               {!! Form::select('parent_id',$categories, null, ['id' => 'parent_id', 'class' => 'form-control', 'required' => 'required']) !!}
               <small class="text-danger">{{ $errors->first('category_id') }}</small>
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
@stop