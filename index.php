<?php  
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php");

	$App 	= new App();
	$Nav	= new Nav();
	$Menu 	= new Menu();

	include($App->getProjectCommon());

	$pageKeywords	= "intent, dsl, modeling, domain specific language, documentation, emf, dsl, metamodel, free, open source, modelwriter";
	$pageAuthor		= "Obeo";
	$pageTitle 		= "Intent";

	$html = file_get_contents('_index.html');
	
	# Generate the web page
	# TODO $App->AddExtraHtmlHeader('<link href="https://plus.google.com/114651471803085159652/" rel="publisher" />' . "\n\t");
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>
