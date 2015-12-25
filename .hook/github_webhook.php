<?php
include_once($_SERVER['HOME'] .'/code/private/scripts/util.php');

try {
	// Annoyingly, we have to parse the raw POSTDATA.
	$payload = json_decode(file_get_contents('php://input'), 1);

	// Only execute if we have a github push.
	if (!isset($_SERVER['HTTP_X_HUB_SIGNATURE'])) {
		poc_error('No signature header');
	}
	if (!isset($secrets['gh_token'])) {
		poc_error('No GH Token found');
	}

	// TODO: pick up other branches
	// TODO: DRY this out
	exec("git fetch $remote_url master:_lean_upstream", $output, $status);
	if ($status !== 0) {
		poc_error("Fetch error - $status", $output);
	}
	exec('git merge -s recursive -Xtheirs _lean_upstream -m "'. $payload['head_commit']['message'] .'"', $output, $status);
	if ($status == 128) {
		poc_error("Uncommitted changes present - Merge blocked", $output);
	}
	elseif ($status !== 0) {
		poc_error("Merge error - $status", $output);
	}
	// Push merged lean changes up to Pantheon's internal repo
	exec('git push origin master', $output, $status);
	// This will trigger a sync_code and do a build.
	print_r($output);
}
catch (Exception $e) {
	// Try and emit an error message to the dashboard if there was a fail.
	poc_error($e->getMessage(), $e);
}

