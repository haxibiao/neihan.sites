<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta content="width=device-width, initial-scale=0.8, maximum-scale=1.0, user-scalable=0" name="viewport">
    <title>合集</title>

    <link rel="stylesheet" type="text/css" href="/css/base.css" />
    <link rel="stylesheet" type="text/css" href="/css/collection.css" />

</head>


<body style="background-color: #161819;">

    <div id="war">
        <div id="top">
            <img src="{{$collection->logo}}">
            <div id="top_right">
                <div id="title">
                    <img src="/images/collection_icon.png">
                    <strong>{{ $collection->name }}</strong>
                </div>
                <div id="top_main">
                    <p>@ {{ $collection->user->name }}</p>
                    <strong>&nbsp; 创建的合集</strong>
                </div>
                <strong>更新至第{{ $collection->count_posts }}集</strong>
            </div>
        </div>
        <div id="play_data">
            <span>
                {{random_int(10000,100000)}} 播放&nbsp;·&nbsp;{{random_int(100,100)}}收藏
            </span>
        </div>

        <div id="app" value="{{$i=1}}">


            @foreach($posts as $post)
            <a class="list_a" href="/share/post/{{$post->id}}" target="_blank">
                <img class="list_img" src="{{ $post->cover }}" />
                <div class="text_cl">
                    <div class="text_top">
                        <p><strong>第 {{$i++}} 集 &nbsp;| &nbsp;</strong>{{$post->description}}</p>
                    </div>
                    <div class="text_button">
                        <strong>{{gmstrftime('%M:%S',$post->video->duration)}}</strong>&nbsp;&nbsp;
                        <span>{{random_int(100,1000)}}播放</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>


    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="/js/collection.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue-resource@1.5.1"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            list: [1, 2, 3],
            page: 2,
        },
        methods: {
            more() {

                var that = this;
                var page = that.page
                var url = '/share/collect?name=' + page;
                var hearder = {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                };
                var params = {}
                that.$http.post(url, params, hearder, {
                        emulateJSON: true
                    })
                    .then((res) => {
                        //成功获取数据
                        console.log(res.data);
                        that.list = that.list.concat(res.data);
                        that.page = that.page++;
                    })
                    .catch((err) => {
                        //请求错误
                        console.log(err);
                    });
            }
        }
    });
</script>