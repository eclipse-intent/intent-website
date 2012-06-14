<?php  			
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'
	require_once($_SERVER["DOCUMENT_ROOT"] . "/modeling/includes/buildServer-common.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/modeling/includes/downloads-scripts.php");
	require_once($_SERVER["DOCUMENT_ROOT"] . "/modeling/includes/scripts.php");
	require_once("./custom-scripts.php");
	
	$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="/modeling/includes/common.css"/>' . "\n\t");
	$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="/modeling/includes/downloads.css"/>' . "\n\t");
	$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="styles.css"/>' . "\n\t");
	$App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="_styles.css"/>' . "\n\t");
	$App->AddExtraHtmlHeader('<script src="/modeling/includes/downloads.js" type="text/javascript"></script>' . "\n\t");
	
	#### Project dependant variables ####
	$projectTitle = "Intent";
	$pageTitle = "Intent - Download";
	// Path to the downloads area under http://downloads.eclipse.org (will be used by custom-scripts and various "eclipse" scripts)
	$PR = "intent";
	// absolute path to the site's home page (will be used by custom-scripts for images... should probably use css instead)
	$websiteRoot = "/intent";
	
	# version => array of qualifiers
	$hiddenBuilds = array(
		"0.7.0" => array("R201205301208", "S201205301208"), #release, RC2
	);
	#### End variables ####
	
	
	$PWD = getPWD("downloads/drops");
	$branches = loadDirSimple($PWD, ".*", "d");
	rsort($branches);
	
	$buildtypes = array(
		"R" => "Release",
		"S" => "Stable",
		"I" => "Integration",
		"M" => "Maintenance",
		"N" => "Nightly"
	);
	$buildTypes = getBuildTypes($branches, $buildtypes);
	
	// Retrieve the list of builds from the disk (folder list only)
	$builds = getBuildsFrom($branches, $PWD);
	
	$builds = reorderAndSplitBuilds($builds, $buildTypes, $hiddenBuilds);	
	$releases = $builds[1];
	$builds = $builds[0];
	
	$html  = "<div id=\"midcolumn\">\n";
	$html .= "<ul>\n";
	$html .= generateHTMLReleaseList($releases, $projectTitle, $PR, $PWD, $websiteRoot);
	$html .= generateHTMLBuildList($builds, $projectTitle, $PR, $PWD, $websiteRoot);
	$html .= "</ul>\n";
	$html .= "</div>\n\n";
	
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
