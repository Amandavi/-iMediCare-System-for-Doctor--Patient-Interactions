<?php
defined('BASEPATH') OR exit('No direct script access allowed');



$config['base_url'] = 'http://localhost/iMediCare';


$config['index_page'] = '';//index.php

$config['uri_protocol']	= 'REQUEST_URI';//

$config['url_suffix'] = '';

$config['language']	= 'english';

$config['charset'] = 'UTF-8';

$config['enable_hooks'] = FALSE;

$config['subclass_prefix'] = 'MY_';

$config['composer_autoload'] = FALSE;

$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd';

$config['allow_get_array'] = TRUE;

$config['log_threshold'] = 0;

$config['log_path'] = '';

$config['log_file_extension'] = '';

$config['log_file_permissions'] = 0644;

$config['log_date_format'] = 'Y-m-d H:i:s';

$config['error_views_path'] = '';

$config['cache_path'] = '';

$config['cache_query_string'] = FALSE;

$config['encryption_key'] = '';

$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'ci_session';
$config['sess_expiration'] = 7200;
$config['sess_save_path'] = NULL;
#$config['sess_save_path'] = '/Users/janakadalugama/AAA_Projects/009_php_pro/sss';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = FALSE;

$config['cookie_prefix']	= '';
$config['cookie_domain']	= '';
$config['cookie_path']		= '/';
$config['cookie_secure']	= FALSE;
$config['cookie_httponly'] 	= FALSE;

$config['standardize_newlines'] = FALSE;
$config['log_threshold'] = 1;

$config['global_xss_filtering'] = FALSE;

$config['csrf_protection'] = FALSE;
$config['csrf_token_name'] = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire'] = 7200;
$config['csrf_regenerate'] = TRUE;
$config['csrf_exclude_uris'] = array();

$config['compress_output'] = FALSE;

$config['time_reference'] = 'Asia/Colombo';

$config['rewrite_short_tags'] = FALSE;

$config['proxy_ips'] = '';






$config['documents_path'] = 'documents/';
$config['tmp_path'] = 'tmp/';


//email details
$config['host'] = 'smtp.gmail.com';
$config['user_name'] = 'assystapp@gmail.com';
$config['password'] = 'mahesh@13701';
$config['from'] = 'assystapp@gmail.com';
$config['port'] = '587';

$config['_fromName'] = 'iMediCare';

$config['company_email'] = 'info@imedicare.com';

/*
|--------------------------------------------------------------------------
| SMS notification
|--------------------------------------------------------------------------
*/

$config['sms_senderId'] = 'A.T.H';
$config['sms_userName'] = 'ath1';
$config['sms_userPwd'] = 'utajith1963';
$config['sms_url'] = 'http://203.153.222.25:5000/sms/send_sms.php';












 