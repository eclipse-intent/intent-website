<?php

	# Set the theme for your project's web pages.
	# See the Committer Tools "How Do I" for list of themes
	# https://dev.eclipse.org/committers/
	# Optional: defaults to system theme
	$theme = "solstice";

	# Define your project-wide Nav bars here.
	# Format is Link text, link URL (can be http://www.someothersite.com/), target (_self, _blank), level (1, 2 or 3)
	# these are optional

	$Menu->setMenuItemList(array());
	$Menu->addMenuItem("Home", "/intent", "_self");
	$Menu->addMenuItem("Download", "/intent/downloads", "_self");
	$Menu->addMenuItem("Support", "/intent/support", "_self");
	$Menu->addMenuItem("Developers", "/intent/developers", "_self");
	
	$Nav->setLinkList(array());
	$Nav->addNavSeparator("About this project", "https://projects.eclipse.org/projects/mylyn.docs.intent/", "", 1  );
	$Nav->addCustomNav("Wiki", "https://wiki.eclipse.org/Intent", 	"_self", 2);
	$Nav->addCustomNav("Newsgroup", "https://www.eclipse.org/forums/index.php/f/219/", "_self", 2);
	# TODO $Nav->addCustomNav("Project Plan", "https://projects.eclipse.org/projects/modeling.m2t.acceleo/documentation", "_self", 2);
	$Nav->addCustomNav("Bugs", "https://bugs.eclipse.org/bugs/buglist.cgi?bug_status=UNCONFIRMED&bug_status=NEW&bug_status=ASSIGNED&bug_status=REOPENED&bug_status=VERIFIED&list_id=16796935&product=Mylyn%20Docs%20Intent&query_format=advanced", 	"_self", 2);
	$Nav->addCustomNav("File a Bug", "https://bugs.eclipse.org/bugs/enter_bug.cgi?product=Mylyn%20Docs%20Intent", 	"_self", 2);

	# TODO $Nav->addNavSeparator("Developers", "https://projects.eclipse.org/projects/modeling.m2t.acceleo/developer", "", 1  );
	$Nav->addCustomNav("Git", "http://git.eclipse.org/c/intent/org.eclipse.mylyn.docs.intent.main.git/", "_self", 2);
	# TODO $Nav->addCustomNav("Gerrit", "https://git.eclipse.org/r/#/admin/projects/intent/org.eclipse.mylyn.docs.intent.main.git", "_self", 2);
	# TODO $Nav->addCustomNav("Mailing List", "https://dev.eclipse.org/mailman/listinfo/m2t-dev", "_self", 2);

	$Nav->addNavSeparator("Related Projects", "https://www.eclipse.org/modeling", "", 1  );
	$Nav->addCustomNav("EMF", "https://www.eclipse.org/modeling/emf", "_self", 2);
	$Nav->addCustomNav("Sirius", "https://www.eclipse.org/sirius", "_self", 2);
	
	# $App->AddExtraHtmlHeader('<link rel="stylesheet" type="text/css" href="/intent/style_intent.css"/>' . "\n\t");
	
	$App->Promotion = TRUE;
	# TODO $App->SetGoogleAnalyticsTrackingCode("UA-16777490-1");
?>
