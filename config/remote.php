<?php

return [

	/*
		    |--------------------------------------------------------------------------
		    | Default Remote Connection Name
		    |--------------------------------------------------------------------------
		    |
		    | Here you may specify the default connection that will be used for SSH
		    | operations. This name should correspond to a connection name below
		    | in the server list. Each connection will be manually accessible.
		    |
	*/

	'default' => 'production',

	/*
		    |--------------------------------------------------------------------------
		    | Remote Server Connections
		    |--------------------------------------------------------------------------
		    |
		    | These are the servers that will be accessible via the SSH task runner
		    | facilities of Laravel. This feature radically simplifies executing
		    | tasks on your servers, such as deploying out these applications.
		    |
	*/

	'connections' => [
		'homestead' => [
			'host' => 'localhost',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'do001' => [
			'host' => 'do001',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'sp020' => [
			'host' => 'sp020',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin001' => [
			'host' => 'lin001',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin002' => [
			'host' => 'lin002',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin003' => [
			'host' => 'lin003',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin004' => [
			'host' => 'lin004',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin005' => [
			'host' => 'lin005',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin006' => [
			'host' => 'lin006',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin007' => [
			'host' => 'lin007',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin008' => [
			'host' => 'lin008',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'lin009' => [
			'host' => 'lin009',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
		'gz001' => [
			'host' => 'gz001',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],

		'hk003' => [
			'host' => 'hk003',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],

		'hk002' => [
			'host' => 'hk002',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],

		'hk001' => [
			'host' => 'hk001',
			'username' => 'root',
			'password' => '',
			'key' => '/data/build/ssh/id_rsa',
			'keytext' => '',
			'keyphrase' => '',
			'agent' => '',
			'timeout' => 36000,
		],
	],

	/*
		    |--------------------------------------------------------------------------
		    | Remote Server Groups
		    |--------------------------------------------------------------------------
		    |
		    | Here you may list connections under a single group name, which allows
		    | you to easily access all of the servers at once using a short name
		    | that is extremely easy to remember, such as "web" or "database".
		    |
	*/

	'groups' => [
		'web' => ['production'],
	],

];
