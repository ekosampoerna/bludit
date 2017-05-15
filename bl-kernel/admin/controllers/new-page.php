<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Check role
// ============================================================================

// ============================================================================
// Functions
// ============================================================================

function addPage($args)
{
	global $dbPages;
	global $Language;

	// Add the page, if the $key is FALSE the creation of the post failure.
	$key = $dbPages->add($args);

	if($key) {
		// Re-index categories
		reindexCategories();

		// Re-index tags
		reindextags();

		// Call the plugins after page created
		Theme::plugins('afterPageCreate');

		// Create an alert
		Alert::set( $Language->g('Page added successfully') );

		// Redirect
		Redirect::page('pages');

		return true;
	}
	else {
		Log::set(__METHOD__.LOG_SEP.'Error occurred when trying to create the page');
		Log::set(__METHOD__.LOG_SEP.'Cleaning database...');
		$dbPages->delete($key);
	}

	return false;
}

// ============================================================================
// Main before POST
// ============================================================================

// ============================================================================
// POST Method
// ============================================================================

if( $_SERVER['REQUEST_METHOD'] == 'POST' )
{
	addPage($_POST);
}

// ============================================================================
// Main after POST
// ============================================================================
