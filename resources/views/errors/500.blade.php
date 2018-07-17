@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron">
        <div class="container error">
            <img src="/images/404.png" alt="">
            <div class="info">
                <h2>服务器内部错误,崩溃啦！</h2>
                <p class="state">没关系，我带你回去<a class="return" href="{{ url()->previous() }}"> 返回</a></p>
                    
                <i class="iconfont icon-icon-test1"></i>
            </div>
            <div class="recommend">
                <div class="title">
                    <h3>爱你城为你推荐</h3>
                </div>
                <div class="hot-recommend">
                    <span>精彩推荐：</span>
                    <a href="/dota2">dota2</a>
                    <a href="/nishuihan">逆水寒</a>
                    <a href="/lushichuanshuo">炉石传说</a>
                    <a href="/DNF">地下城与勇士</a>
                </div>
                <div class="video">
                    <div class="col-xs-6 col-md-3 video">
                        <div class="video-item vt"><div class="thumb">
                            <a href="/video/424" target="_blank">
                                <img src="https://ainicheng.com/storage/video/424.jpg" alt="绝地求生之精彩集锦2-爱你城原创出品"> 
                                <i class="duration">04:07</i> 
                                <i class="hover-play"></i>
                            </a>
                        </div> 
                            <ul class="info-list">
                                <li class="video-title"><a target="_blank" href="/video/424">绝地求生之精彩集锦2-爱你城原创出品</a></li>
                                 <li>
                                    <p class="subtitle single-line">31次播放</p>
                                </li>
                             </ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3 video">
                        <div class="video-item vt">
                            <div class="thumb">
                                <a href="/video/423" target="_blank">
                                    <img src="https://ainicheng.com/storage/video/423.jpg" alt="如果CSGO成为角色扮演游戏！"> 
                                    <i class="duration">  04:00 </i>
                                    <i class="hover-play"></i>
                                </a>
                            </div>
                             <ul class="info-list">
                                <li class="video-title"><a target="_blank" href="/video/423">如果CSGO成为角色扮演游戏！</a>
                                </li> 
                                <li>
                                    <p class="subtitle single-line">14次播放</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xs-6 col-md-3 video">
                        <div class="video-item vt">
                            <div class="thumb">
                                <a href="/video/422" target="_blank">
                                            <img src="https://ainicheng.com/storage/video/422.jpg" alt="最新《赛博朋克风角色扮演》游戏-4K故事预告片2018"> 
                                            <i class="duration"> 02:11                     </i> 
                                   <i class="hover-play"></i>
                               </a>
                            </div> 
                            <ul class="info-list">
                                <li class="video-title">
                                    <a target="_blank" href="/video/422">最新《赛博朋克风角色扮演》游戏-4K故事预告片2018</a>
                                </li> 
                                <li>
                                    <p class="subtitle single-line">8次播放</p>
                                </li>
                            </ul>
                        </div>
                   </div>
                    <div class="col-xs-6 col-md-3 video">
                        <div class="video-item vt">
                            <div class="thumb">
                                <a href="/video/421" target="_blank">
                                    <img src="https://ainicheng.com/storage/video/421.jpg" alt="【这波真滴秀】孤存、A+、17shou新地图萨诺刚枪教学！"> 
                                    <i class="duration"> 04:58                     </i> 
                                    <i class="hover-play"></i>
                                </a>
                            </div> <ul class="info-list">
                                <li class="video-title">
                                    <a target="_blank" href="/video/421">【这波真滴秀】孤存、A+、17shou新地图萨诺刚枪教学！</a>
                                </li>
                                <li>
                                    <p class="subtitle single-line">10次播放</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
