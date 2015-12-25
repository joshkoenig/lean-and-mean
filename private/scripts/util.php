<?php
// Utility functions/variables for PoC.
// TODO: replace these with core commands or beter APIs.

// Load our secrets.
$secrets = json_decode(file_get_contents($_SERVER['HOME'].'/files/private/secrets.json'), 1);

$workspace = $_SERVER['HOME']. '/code';
$gh_url = 'https://github.com/joshkoenig/lean-and-mean.git';
// Quick and dirty HTTPS auth'ed url builder
$remote_url = str_replace('https://',
                          'https://'. $secrets['gh_token']. ':x-oauth-basic@',
                          $gh_url);


function poc_error($reason = 'Uknown failure', $extended = FALSE) {
	// Make creative use of the error reporting API
	$data = array('file'=>'GitHub Integration',
								'line'=>'Error',
								'type'=>'error',
								'message'=>$reason);
	$params = http_build_query($data);
	$result = pantheon_curl('https://api.live.getpantheon.com/sites/self/environments/self/events?'. $params, NULL, 8443, 'POST');

	error_log("GitHub Integration failed - $reason");
	// Dump additional debug info into the error log
	if ($extended) {
		error_log(print_r($extended, 1));
	}
	die("GitHub Integration failed - $reason");
}
