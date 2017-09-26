<?php
	function getBuildsFrom($branchDirs, $PWD) {
		$buildDirs = array();
	
		foreach ($branchDirs as $branch) {
			// Some of the builds are of the form <type><YYYYMMDD>-<HHMM>
			// Others are the usual <type><YYYYMMDDHHMM>
			$buildDirs[$branch] = loadDirSimple("$PWD/$branch", "[IMNRS](\d{8}-?\d{4})", "d");
		}
	
		// sort by branch (1.2.2 and 1.2.1 will both be in "1.2"), then version (1.2.2 or 1.2.1), then type
		$builds_temp = array();
		foreach ($buildDirs as $branch => $dirList) {
			$version = substr($branch, 0, 3);
			foreach ($dirList as $dir) {
				$type = substr($dir, 0, 1);
	
				$builds_temp[$version][$branch][$type][] = $dir;
			}
		}
	
		return $builds_temp;
	}
	
	function reorderAndSplitBuilds($oldBuilds, $buildTypes, $hiddenBuilds) {
		$newBuilds = array();
		$releases = array();
		
		foreach ($buildTypes as $branch => $types) {
			$version = substr($branch, 0, 3);
			foreach ($types as $type => $names) {
				if ($type == "R" && isset($oldBuilds[$version][$branch][$type])) {
					$id = $oldBuilds[$version][$branch][$type][0];
					if (!isset($hiddenBuilds[$branch]) || !in_array($id, $hiddenBuilds[$branch])) {
						$releases[$version][$branch] = $id;
					}
				} else if (array_key_exists($version, $oldBuilds) && array_key_exists($branch, $oldBuilds[$version]) && array_key_exists($type, $oldBuilds[$version][$branch]) && is_array($oldBuilds[$version][$branch][$type])) {
					if (isset($hiddenBuilds[$branch])) {
						$newBuilds[$version][$branch][$type] = array();
						foreach ($oldBuilds[$version][$branch][$type] as $id) {
							if (!in_array($id, $hiddenBuilds[$branch])) {
								$newBuilds[$version][$branch][$type][] = $id;
							}
						}
					} else {
						$newBuilds[$version][$branch][$type] = $oldBuilds[$version][$branch][$type];
					}
					rsort($newBuilds[$version][$branch][$type]);
				}
			}
		}
		return array($newBuilds,$releases);
	}
	
	function getUpdateSiteArchive($zips) {
		foreach ($zips as $zip) {
			if (preg_match("/((S|s)ite)|((U|u)pdate)/", $zip)) {
				return $zip;
			}
		}
		return "";
	}
	
	function getSDKArchive($zips) {
		foreach ($zips as $zip) {
			if (preg_match("/SDK/", $zip)) {
				return $zip;
			}
		}
		return "";
	}
	
	function getBuildLabel($zips) {
		// the label is the version number plus its appended alias (if any)
		foreach ($zips as $zip) {
			preg_match("/(\d+\.\d+\.\d+)((M|RC)\d+)?/", $zip, $matches);
			if (sizeof($matches) > 0) {
				return preg_replace("/(\d+\.\d+\.\d+)((M|RC)\d+)?/", "$1 $2", $matches[0]);
			}
		}
		return "";
	}
	
	function getTypeLabel($type) {
		if ($type == "R") {
			return "Release";
		}
		if ($type == "M") {
			return "Maintenance";
		}
		if ($type == "S") {
			return "Stable";
		}
		if ($type == "I") {
			return "Integration";
		}
		if ($type == "N") {
			return "Nightly";
		}
		return "";
	}
	
	function getTypeUpdateSite($type) {
		if ($type == "R") {
			return "releases";
		}
		if ($type == "M") {
			return "maintenance";
		}
		if ($type == "S") {
			return "milestones";
		}
		if ($type == "I") {
			return "interim";
		}
		if ($type == "N") {
			return "nightly";
		}
		return "";
	}
	
	function generateHTMLReleaseList($releases, $projectTitle, $PR, $PWD, $websiteRoot) {
		// We'll only show the very first release in the list (latest), the others will be reduced by default
		$display = true;
	
		$releaseList = "";
		if (sizeof($releases) > 0) {
			$releaseList .= "<li class=\"repo-item\">\n";
			$releaseList .= "<a href=\"javascript:toggle('repo_releases')\" class=\"repo-label1\">Releases</a>";
			$releaseList .= "<a name=\"releases\" href=\"#releases\">";
			$releaseList .= "<img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/>";
			$releaseList .= "</a>\n";
			$releaseList .= "<div class=\"repo1\" id=\"repo_releases\">\n";
			
			$releaseList .= "<table border=\"0\" width=\"100%\">\n";
			$releaseList .= "<tr class=\"repo-info\">";
			$releaseList .= "<td><img src=\"" . $websiteRoot . "/images/22/package-x-generic.png\" alt=\"composite update site\"/></td>";
			$releaseList .= "<td><b><a href=\"http://download.eclipse.org/" . $PR . "/updates/releases\">Update Site</a></b> for use with <a href=\"http://help.eclipse.org/indigo/index.jsp?topic=/org.eclipse.platform.doc.user/tasks/tasks-127.htm\">p2</a>.</td>";
			$releaseList .= "<td class=\"file-size level1\"></td>";
			$releaseList .= "</tr>\n";
			$releaseList .= "</table>\n";
			
			$releaseList .= "<ul>\n";
			
			foreach ($releases as $version => $branches) {
				$htmlVersion = preg_replace("/\./", "_", $version);
			
				$releaseList .= "<li  class=\"repo-item\">\n";
				$releaseList .= "<a href=\"javascript:toggle('repo_releases_" . $htmlVersion . "')\" class=\"repo-label2\">" . $version . " Releases</a>";
				$releaseList .= "<a name=\"releases_" . $htmlVersion . "\" href=\"#releases_" . $htmlVersion . "\"><img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/></a>\n";
				
				$releaseList .= "<div class=\"repo2\" id=\"repo_releases_" . $htmlVersion . "\"";
				if ($display) {
					$releaseList .= ">\n";
				} else {
					$releaseList .= " style=\"display: none\">\n";
				}
				
				$releaseList .= "<table border=\"0\" width=\"100%\">\n";
				$releaseList .= "<tr class=\"repo-info\">";
				$releaseList .= "<td><img src=\"" . $websiteRoot . "/images/16/package-x-generic.png\" alt=\"composite update site\"/></td>";
				$releaseList .= "<td><b><a href=\"http://download.eclipse.org/" . $PR . "/updates/releases/" . $version . "\">Update Site</a></b> for use with <a href=\"http://help.eclipse.org/indigo/index.jsp?topic=/org.eclipse.platform.doc.user/tasks/tasks-127.htm\">p2</a>.</td>";
				$releaseList .= "<td class=\"file-size level2\"></td>";
				$releaseList .= "</tr>\n";
				$releaseList .= "</table>\n";
				
				$releaseList .= "<ul>\n";
				
				foreach ($branches as $branch => $ID) {
					$releaseList .= generateHTMLForBuild($projectTitle, $PR, $PWD, $websiteRoot, $version, $branch, $ID, "releases", $display);
					
					// Only display the very latest release
					if ($display) {
						$display = false;
					}
				}
				
				$releaseList .= "</ul>\n";
				$releaseList .= "</div>\n";
				$releaseList .= "</li>\n";
			}
			
			$releaseList .= "</ul>\n";	
			$releaseList .= "</div>\n";
			$releaseList .= "</li>\n";
		}
		return $releaseList;
	}
	
	function generateHTMLBuildList($builds, $projectTitle, $PR, $PWD, $websiteRoot) {
		// Only display the very latest build
		$display = true;
		
		$buildList = "";
		if (sizeof($builds) > 0) {
			foreach ($builds as $version => $branches) {
				$htmlVersion = preg_replace("/\./", "_", $version);
			
				$buildList .= "<li class=\"repo-item\">\n";
				$buildList .= "<a href=\"javascript:toggle('repo_" . $htmlVersion . "')\" class=\"repo-label1\">" . $version . " Builds</a>";
				$buildList .= "<a name=\"builds_" . $htmlVersion . "\" href=\"#builds_" . $htmlVersion . "\">";
				$buildList .= "<img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/>";
				$buildList .= "</a>\n";
				
				$buildList .= "<div class=\"repo1\" id=\"repo_" . $htmlVersion . "\"";
				if ($display) {
					$buildList .= ">\n";
				} else {
					$buildList .= " style=\"display: none\">\n";
				}
				
				$buildList .= "<ul>\n";
				
				foreach ($branches as $branch => $types) {
					$htmlBranch = preg_replace("/\./", "_", $branch);
					
					$buildList .= "<li class=\"repo-item\">\n";
					$buildList .= "<a href=\"javascript:toggle('repo_" . $htmlBranch . "')\" class=\"repo-label1\">" . $branch . "</a>";
					$buildList .= "<a name=\"builds_" . $htmlBranch . "\" href=\"#builds_" . $htmlBranch . "\">";
					$buildList .= "<img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/>";
					$buildList .= "</a>\n";
					
					$buildList .= "<div class=\"repo2\" id=\"repo_" . $htmlBranch . "\"";
					if ($display) {
						$buildList .= ">\n";
					} else {
						$buildList .= " style=\"display: none\">\n";
					}
					
					$buildList .= "<ul>\n";
					
					foreach ($types as $type => $IDs) {
						$typeLabel = getTypeLabel($type);
						$typeUpdateSite = getTypeUpdateSite($type);
						
						$buildList .= "<li class=\"repo-item\">\n";
						$buildList .= "<a href=\"javascript:toggle('repo_" . $htmlBranch . "_" . $type . "')\" class=\"repo-label1\">" . $branch . " " . $typeLabel . " Builds</a>";
						$buildList .= "<a name=\"builds_" . $htmlBranch . "_" . $type . "\" href=\"#builds_" . $htmlBranch . "_" . $type . "\">";
						$buildList .= "<img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/>";
						$buildList .= "</a>\n";
						
						$buildList .= "<div class=\"repo2\" id=\"repo_" . $htmlBranch . "_" . $type . "\"";
						if ($display) {
							$buildList .= ">\n";
						} else {
							$buildList .= " style=\"display: none\">\n";
						}
						
						$buildList .= "<ul>\n";
						
						foreach ($IDs as $ID) {
							$buildList .= generateHTMLForBuild($projectTitle, $PR, $PWD, $websiteRoot, $version, $branch, $ID, $typeUpdateSite, $display);
							
							// Only display the very latest build
							if ($display) {
								$display = false;
							}
						}
						
						$buildList .= "</ul>\n";	
						$buildList .= "</div>\n";
						$buildList .= "</li>\n";
					}
					
					$buildList .= "</ul>\n";	
					$buildList .= "</div>\n";
					$buildList .= "</li>\n";
				}
				
				$buildList .= "</ul>\n";	
				$buildList .= "</div>\n";
				$buildList .= "</li>\n";
			}
		}
		return $buildList;
	}
	
	function generateHTMLForBuild($projectTitle, $PR, $PWD, $websiteRoot, $version, $branch, $ID, $typeUpdateSite, $display = false) {
		// YYYY/MM/DD HH:MM
		$dateFormat = preg_replace("/[IMNRS](\d{4})(\d{2})(\d{2})-?(\d{2})(\d{2})/", "$1/$2/$3 $4:$5", $ID);
		$zips_in_folder = loadDirSimple("$PWD/$branch/$ID/", "(\.zip|\.tar\.gz)", "f");
		$archivedSite = getUpdateSiteArchive($zips_in_folder);
		$SDKArchive = getSDKArchive($zips_in_folder);
		$buildLabel = getBuildLabel($zips_in_folder);
		if ($buildLabel == "" || $buildLabel == " ") {
			$buildLabel = $branch;
		}
	
		$buildHTML = "<li class=\"repo-item\">\n";
		// PENDING add alias if any in the displayed text
		$buildHTML .= "<b><a href=\"javascript:toggle('drop_" . $ID . "')\" class=\"drop-label\">" . $buildLabel . " (" . $dateFormat . ")</a></b>";
		$buildHTML .= "<a name=\"" . $ID . "\" href=\"#" . $ID . "\"><img src=\"" . $websiteRoot . "/images/link_obj.gif\" alt=\"Permalink\" width=\"12\" height=\"12\"/></a>\n";
		$buildHTML .= "<div class=\"drop\" id=\"drop_" . $ID . "\"";
		if ($display) {
			$buildHTML .= ">\n";
		} else {
			$buildHTML .= " style=\"display: none\">\n";
		}
		
		$buildHTML .= "<table border=\"0\" width=\"100%\">\n";
		
		// UPDATE SITE
		$buildHTML .= "<tr class=\"repo-info\">";
		$buildHTML .= "<td><img src=\"" . $websiteRoot . "/images/22/package-x-generic.png\" alt=\"composite update site\"/></td>";
		$buildHTML .= "<td><b><a href=\"http://download.eclipse.org/" . $PR . "/updates/" . $typeUpdateSite . "/" . $version . "/" . $ID . "\">Update Site</a></b> for use with <a href=\"http://help.eclipse.org/indigo/index.jsp?topic=/org.eclipse.platform.doc.user/tasks/tasks-127.htm\">p2</a>.</td>";
		$buildHTML .= "<td class=\"file-size level3\"></td>";
		$buildHTML .= "</tr>\n";
		
		$buildHTML .= "<tr class=\"drop-info\"><td colspan=\"3\"><hr class=\"drop-separator\"></td></tr>";
		
		// ARCHIVED UPDATE SITE
		if ($archivedSite != "") {
			$buildHTML .= "<tr class=\"drop-info\">";
			$buildHTML .= "<td><img src=\"" . $websiteRoot . "/images/16/package-x-generic.png\" alt=\"archived update site\"/></td>";
			$buildHTML .= "<td><a href=\"http://www.eclipse.org/downloads/download.php?file=/" . $PR . "/downloads/drops/" . $branch . "/" . $ID . "/" . $archivedSite . "&amp;protocol=http\">Archived update site</a> for local use with <a href=\"http://help.eclipse.org/indigo/index.jsp?topic=/org.eclipse.platform.doc.user/tasks/tasks-127.htm\">p2</a>.</td>";
			// PENDING retrieve zip size
			$buildHTML .= "<td class=\"file-size level3\"><i></i></td>";
			$buildHTML .= "</tr>\n";
		}
		
		// SDK
		if ($SDKArchive != "") {
			$buildHTML .= "<tr class=\"drop-info\">";
			$buildHTML .= "<td><img src=\"" . $websiteRoot . "/images/16/go-down.png\" alt=\"" . $projectTitle . " SDK\"/></td>";
			$buildHTML .= "<td><a href=\"http://www.eclipse.org/downloads/download.php?file=/" . $PR . "/downloads/drops/" . $branch . "/" . $ID . "/" . $SDKArchive . "&amp;protocol=http\">" . $projectTitle . " SDK</a></td>";
			// PENDING retrieve zip size
			$buildHTML .= "<td class=\"file-size level3\"><i></i></td>";
			$buildHTML .= "</tr>\n";
		}
		
		$buildHTML .= "</table>\n";
		
		$buildHTML .= "</div>\n";
		$buildHTML .= "</li>\n";
		
		return $buildHTML;
	}
?>