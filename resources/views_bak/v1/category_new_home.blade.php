@extends('v1.layouts.app')

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
                                <img src="/images/category_10.png"/>
                            </div>
                        </td>
                        <td>
                            <a class="btn_hollow" href="javascript:;">
                                <input class="hide" type="file" unselectable="on"/>
                                上传专题封面
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title">
                            名称
                        </td>
                        <td>
                            <input placeholder="填写名称，不超过50字" type="text" class="form-control" />
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title pull-left">
                            描述
                        </td>
                        <td>
                            <textarea placeholder="填写描述" class="form-control">
                            </textarea>
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title pull-left">
                            其他管理员
                        </td>
                        <td>
                            <div class="user_add">
                                <input data-toggle="dropdown" placeholder="输入用户名" type="text" class="form-control" />
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
                                <input checked="" name="submission" type="radio" value="true"/>
                                <span>
                                    允许
                                </span>
                            </div>
                            <div>
                                <input name="submission" type="radio" value="false"/>
                                <span>
                                    不允许
                                </span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="setting_title">
                            投稿是否需要审核
                        </td>
                        <td>
                            <div>
                                <input checked="" name="examine" type="radio" value="true"/>
                                <span>
                                    需要
                                </span>
                            </div>
                            <div>
                                <input name="examine" type="radio" value="false"/>
                                <span>
                                    不需要
                                </span>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <input class="btn_success" type="submit" value="创建专题"/>
        </div>
    </div>
</div>
@stop
