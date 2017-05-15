<?php defined('BLUDIT') or die('Bludit CMS.');

// ============================================================================
// Variables
// ============================================================================

// Array with all published pages
$pages = array();

// Array with all pages (published, draft, scheduled)
$allPages = array();

// Object Page for the page filtered bye the user
$page = false;

// Array with all page parents published
//$pageParents = array();

// Array with all published pages, the array is a key=>Page-object
$pagesKey = array();

// ============================================================================
// Main
// ============================================================================

// Execute the scheduler
if( $dbPages->scheduler() ) {
	// Reindex tags
	reindexTags();

        // Reindex categories
        reindexCategories();
}

// Build specific page
if( $Url->whereAmI()==='page' ) {

        // Build the page
	$page = buildPage( $Url->slug() );

	// The page doesn't exist
	if($page===false) {
		$Url->setNotFound(true);
	}
	// The page is not published
	elseif( !$page->published() ) {
		$Url->setNotFound(true);
	}
	else {
		$pages[0] = $page;
	}
}
elseif( $Url->whereAmI()==='tag' ) {
	buildPagesByTag();
}
elseif( $Url->whereAmI()==='category' ) {
        buildPagesByCategory();
}
elseif( $Url->whereAmI()==='home' ) {
        buildPagesForHome();
}
elseif( $Url->whereAmI()==='admin' ) {
        buildPagesForAdmin();
}

if( $Url->notFound() ) {
	$Url->setWhereAmI('page');
	$page = new Page('error');
}
