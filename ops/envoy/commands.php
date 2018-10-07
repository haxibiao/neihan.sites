<?php

// 临时命令
// php artisan fix:data users 修复上次296用户 id变成144 导致一系列网页错误
// 给视频添加旋转属性
$run_commands = <<<EOT
php artisan fix:data images10
EOT;
