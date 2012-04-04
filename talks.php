<?php  																														require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/app.class.php");	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/nav.class.php"); 	require_once($_SERVER['DOCUMENT_ROOT'] . "/eclipse.org-common/system/menu.class.php"); 	$App 	= new App();	$Nav	= new Nav();	$Menu 	= new Menu();		include($App->getProjectCommon());    # All on the same line to unclutter the user's desktop'
/*******************************************************************************
 * Copyright (c) 2009 
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the Eclipse Public License v1.0
 * which accompanies this distribution, and is available at
 * http://www.eclipse.org/legal/epl-v10.html
 *
 * Contributors:
 *    
 *******************************************************************************/

	$pageTitle 		= "Intent - Talks & Transcripts";

	$html  = <<<EOHTML
<div id="midcolumn">
<h2>$pageTitle</h2>
<p> You will find here the transcripts of all Intent talks. </p>

<p>
<a href="http://www.eclipse.org/intent/pages/transcripts/2012_AgileALMConnect/Intent_AgileALMConnect2012.htm">Agile ALM Connect 2012 : Create useful documentation with Mylyn Intent</a>
<a href="http://www.eclipse.org/intent/pages/transcripts/2011_EclipseConEurope/Intent_ece2011.htm">EclipseCon Europe 2011 : Create useful documentation with Mylyn Intent</a>
</p>


</div>
EOHTML;
	# Generate the web page
	$App->generatePage($theme, $Menu, $Nav, $pageAuthor, $pageKeywords, $pageTitle, $html);
?>