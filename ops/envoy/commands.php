<?php

// 临时命令
// php artisan fix:data users 修复上次296用户 id变成144 导致一系列网页错误
$run_commands = <<<EOT
echo "do some fix data ...."
php artisan fix:data users;
echo "fix success ...."
EOT;
