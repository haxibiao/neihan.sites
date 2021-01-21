# 简单breeze  内测源码包使用流程

- bash bash/init_breeze.sh 获取子模块源码
- composer autoload  + 10+ composer 依赖包 +  10+ providers 手动注册
- breeze:install （提供models + nova resources + mvc ，路由）
- breeze:publish (发布资源文件)
- video:sync --source=爱你城 --collectable   同步电影合集短视频
- fix:content videos 发布哈希云同步下来的未发布动态的videos
- movie:sync --region=港剧  测试一个影视频道
- /register 一个新用户，发布文章
