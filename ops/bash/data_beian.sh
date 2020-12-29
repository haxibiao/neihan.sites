#!/bin/bash

echo "修复备案站群数据..."
php db:seed --class=SiteBeianSeeder

