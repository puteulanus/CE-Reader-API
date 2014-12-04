<?php
// 设定本体层地址
define(SELF_URL,'');
// 设定CDN地址
define(CDN_URL,'');
// 设定API地址
define(API_URL,'');
// 设定数据库
define(db_host, getenv('OPENSHIFT_MYSQL_DB_HOST'));// 数据库地址
define(db_port, getenv('OPENSHIFT_MYSQL_DB_PORT'));// 数据库端口
define(db_user, getenv('OPENSHIFT_MYSQL_DB_USERNAME'));// 数据库用户名
define(db_name, getenv('OPENSHIFT_APP_NAME'));// 数据库名
define(db_passwd, getenv('OPENSHIFT_MYSQL_DB_PASSWORD'));// 数据库密码
define(db_prefix, 'cr_');// 表前缀
