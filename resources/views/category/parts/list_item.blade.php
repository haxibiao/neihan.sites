<div class=" category-item top10 col-xs-12 clearfix">
    <img alt="" class="pull-left" src="{{ $category->logo() }}">
        <div class="pull-right">
            {!! Form::open(['method' => 'get', 'route' => ['category.edit', $category->id], 'class' => 'form-horizontal']) !!}
            <div class="btn-group pull-right right10">
                {!! Form::submit("编辑", ['class' => 'btn btn-sm btn-primary']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="pull-right">
            {!! Form::open(['method' => 'delete', 'route' => ['category.destroy', $category->id], 'class' => 'form-horizontal']) !!}
            <div class="btn-group pull-right" style="padding-right: 5px">
                {!! Form::submit("删除", ['class' => 'btn btn-sm btn-default']) !!}
            </div>
            {!! Form::close() !!}
        </div>
        <div class="item-info">
            <h5 class="category-name">
                {{ $category->name }} ({{ $category->name_en }})
            </h5>
            <span>
                创建人: {{ $category->user->name }}
            </span>
            <p class="small well" style="min-height: 100px">
                {{ $category->description }}
            </p>
        </div>
    </img>
</div>