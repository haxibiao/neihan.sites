<div class="col-md-12 top10">
    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li class="active" role="presentation">
            <a aria-controls="list_create" data-toggle="tab" href="#list_create" role="tab">
                创建关联文章集合
            </a>
        </li>
        <li role="presentation">
            <a id="list_select_link" aria-controls="list_select" data-toggle="tab" href="#list_select" role="tab">
                关联已建文章集合
            </a>
        </li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="list_create" role="tabpanel">
            <single-list-create article-id="{{ $article->id }}">
            </single-list-create>
        </div>
        <div class="tab-pane" id="list_select" role="tabpanel">
            <single-list-select article-id="{{ $article->id }}" ref="list_select">
            </single-list-select>
        </div>
    </div>
</div>