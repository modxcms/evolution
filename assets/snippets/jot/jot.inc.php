<?php
/*####
#
#	Name: Jot
#	Version: 1.0 BETA 6
#	Author: Armand "bS" Pondman (apondman@zerobarrier.nl)
#	Date: Oct 2, 2006 20:42 CET
#
####*/

include_once "minixml-1.3.0/minixml.inc.php";
include_once "custom.class.inc.php";
include_once "data.db.class.inc.php";
include_once "chunkie.class.inc.php";

class CJot {
	var $_name;
	var $_version;
	var $_config = array();
	var $_parameters = array();
	var $_ctime;
	var $_useMngUser;
	var $_allowAnyPost;
	var $_allowAnyView;
	var $_provider;
	var $_instance;
	var $_templates = array();
	var $_link = array();

	
	function CJot() {
		global $modx;
		$this->_name = $this->_config["snippet"]["name"] = "Jot";
		$this->_version = $this->_config["snippet"]["version"] = "1.0 Beta 6";
		$this->_config["snippet"]["versioncheck"] = "Unknown";
		$this->_ctime = time();
		$this->_check = 0;
		$this->_useMngUser = 0;
		$this->_provider = new CJotDataDb;
	}
	
	function VersionCheck($version) {	
		if ($version == $this->_version) {
			$this->_check = 1;
		}
		$this->_config["snippet"]["versioncheck"] = $version;
	}
	
	function Get($field) {
		return $this->_parameters[$field];
	}
	
	function Set($field, $value) {
		$this->_parameters[$field] = $value;
	}
	
	function GetIP(&$type_used) {
        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $_ip = $_SERVER['HTTP_CLIENT_IP'];
            $type_used = 'C';
        } else
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                $type_used = 'F';
       } else
            if (isset($_SERVER['REMOTE_ADDR'])) {
                $_ip = $_SERVER['REMOTE_ADDR'];
                $type_used = 'R';
        } else {
                $_ip = 'UNKNOWN';
                $type_used = 'U';
        }
        return $_ip;
 	}
	
	function UniqueId($docid = 0,$tagid = '') {
		// Creates a unique hash / id
		$id[] = $docid."&".$tagid."&";
		foreach ($this->_parameters as $n => $v) { $id[] = $n.'='.($v); }
		return md5(join('&',$id));
	}

	function Run() {
		global $modx;
		
		// Check version		
		$this->_config["path"] = $this->Get("path");
		if (!$this->_check) {
			$output = '<div style="border: 1px solid red;font-weight: bold;margin: 10px;padding: 5px;">
			Jot cannot load because the snippet code version ('.$this->_config["snippet"]["versioncheck"].') isn\'t the same as the snippet included files version ('.$this->_config["snippet"]["version"].').
			Possible cause is that you updated the jot files in the modx directory but didn\'t update the snippet code from the manager. The content for the updated snippet code can be found in jot.snippet.txt
			</div>';
			return $output;
		}
		
		// General settings
		$this->_config["docid"] = !is_null($this->Get("docid")) ? intval($this->Get("docid")):$modx->documentIdentifier;
		$this->_config["tagid"] = !is_null($this->Get("tagid")) ? preg_replace("/[^A-z0-9_\-]/",'',$this->Get("tagid")):'';
		$this->_config["pagination"] = !is_null($this->Get("pagination")) ? $this->Get("pagination") : 0; // Set pagination (0 = disabled, # = comments per page)
		$this->_config["captcha"] = !is_null($this->Get("captcha")) ? intval($this->Get("captcha")) : 0; // Set captcha (0 = disabled, 1 = enabled, 2 = enabled for not logged in users)
		$this->_config["postdelay"] = !is_null($this->Get("postdelay")) ? $this->Get("postdelay") : 15; // Set post delay in seconds
		$this->_config["guestname"] = !is_null($this->Get("guestname")) ? $this->Get("guestname") : "Anonymous"; // Set guestname if none is specified
		$this->_config["subscribe"] = !is_null($this->Get("subscribe")) ? intval($this->Get("subscribe")) : 0;
		$this->_config["placeholders"] = !is_null($this->Get("placeholders")) ? intval($this->Get("placeholders")) : 0;
		$this->_config["authorid"] = !is_null($this->Get("authorid")) ? intval($this->Get("authorid")) : $modx->documentObject["createdby"];
		$this->_config["notifysubject"] = !is_null($this->Get("notifysubject")) ? $this->Get("notifysubject") : "New reply to a topic you are watching";
		$this->_config["debug"] = !is_null($this->Get("debug")) ? intval($this->Get("debug")) : 0;
		$this->_config["output"] = !is_null($this->Get("output")) ? intval($this->Get("output")) : 1;
		
		// CSS Settings (basic)
		$this->_config["css"]["rowalt"] = !is_null($this->Get("cssRowAlt")) ? $this->Get("cssAltRow") : "jot-row-alt";
		$this->_config["css"]["rowme"] = !is_null($this->Get("cssRowMe")) ? $this->Get("cssRowMe") : "jot-row-me";
		$this->_config["css"]["rowauthor"] = !is_null($this->Get("cssRowAuthor")) ? $this->Get("cssRowAuthor") : "jot-row-author";
		
		// Security
		$this->_config["user"]["mgrid"] = intval($_SESSION['mgrInternalKey']);
		$this->_config["user"]["usrid"] = intval($modx->getLoginUserID());
		$this->_config["user"]["id"] = ($this->_config["user"]["usrid"] > 0 ) ? (-$this->_config["user"]["usrid"]) : $this->_config["user"]["mgrid"];
		$this->_config["user"]["host"] = NULL;
		$this->_config["user"]["ip"] = $this->GetIP($this->_config["user"]["host"]);
		$this->_config["user"]["agent"] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']  : 'NO USER AGENT';
		$this->_config["user"]["sechash"] = md5($this->_config["user"]["id"].$this->_config["user"]["host"].$this->_config["user"]["ip"].$this->_config["user"]["agent"]);
		
		// Automatic settings
		$this->_instance = $this->_config["id"] = $this->UniqueId($this->_config["docid"],$this->_config["tagid"]);
		if($this->_config["captcha"] == 2) { if ($this->_config["user"]["id"]) {	$this->_config["captcha"] = 0;} else { $this->_config["captcha"] = 1;} }
		$this->_config["guestform"] = intval(($this->_config["user"]["id"]) ? false : true);
		$this->_config["seed"] = rand();
		$this->_config["doc.pagetitle"] = $modx->documentObject["pagetitle"];
				
		#$sortorder = isset($sortorder) ? $sortorder : 1;
		$this->_config["customfields"] = $this->Get("customfields") ? explode(",",$this->Get("customfields")):array(); // Set names of custom fields
								
		// Set access groups
		$this->_config["permissions"]["post"] = !is_null($this->Get("canpost")) ? explode(",",$this->Get("canpost")):array();
		$this->_config["permissions"]["view"] = !is_null($this->Get("canview")) ? explode(",",$this->Get("canview")):array();
		$this->_config["permissions"]["moderate"] = !is_null($this->Get("canmoderate")) ? explode(",",$this->Get("canmoderate")):array();
		$this->_config["permissions"]["trusted"] = !is_null($this->Get("trusted")) ? explode(",",$this->Get("trusted")):array();
		
		// Set Any access
		$this->_allowAnyPost = count($this->_config["permissions"]["post"])==0 ? true : false;
		$this->_allowAnyView = count($this->_config["permissions"]["view"])==0 ? true : false;
		
		// Moderation
		$this->_config["moderation"]["type"] = !is_null($this->Get("moderated")) ? intval($this->Get("moderated")) : 0;
		$this->_config["moderation"]["enabled"] = intval($modx->isMemberOfWebGroup($this->_config["permissions"]["moderate"] ) || $modx->checkSession());
		$this->_config["moderation"]["trusted"] = intval($modx->isMemberOfWebGroup($this->_config["permissions"]["trusted"] ) || $modx->checkSession());
		
		// Templates
		if($this->Get("tplForm")) $this->_templates["form"] = $modx->getChunk($this->Get("tplForm"));
		if(empty($this->_templates["form"])) $this->_templates["form"] = file_get_contents($this->_config["path"]."/chunk.form.inc.html");
		
		if($this->Get("tplComments")) $this->_templates["comments"] = $modx->getChunk($this->Get("tplComments"));
		if(empty($this->_templates["comments"])) $this->_templates["comments"] = file_get_contents($this->_config["path"]."/chunk.comment.inc.html");
		
		if($this->Get("tplNav")) $this->_templates["navigation"] = $modx->getChunk($this->Get("tplNav"));
		if(empty($this->_templates["navigation"])) $this->_templates["navigation"] = file_get_contents($this->_config["path"]."/chunk.navigation.inc.html");
		
		if($this->Get("tplModerate")) $this->_templates["moderate"] = $modx->getChunk($this->Get("tplModerate"));
		if(empty($this->_templates["moderate"])) $this->_templates["moderate"] = file_get_contents($this->_config["path"]."/chunk.comment.moderate.inc.html");
		
		if($this->Get("tplModerateBar")) $this->_templates["moderatebar"] = $modx->getChunk($this->Get("tplModerateBar"));
		if(empty($this->_templates["moderatebar"])) $this->_templates["moderatebar"] = file_get_contents($this->_config["path"]."/chunk.moderate.inc.html");
		
		if($this->Get("tplSubscribe")) $this->_templates["subscribe"] = $modx->getChunk($this->Get("tplSubscribe"));
		if(empty($this->_templates["subscribe"])) $this->_templates["subscribe"] = file_get_contents($this->_config["path"]."/chunk.subscribe.inc.html");
		
		if($this->Get("tplNotify")) $this->_templates["notify"] = $modx->getChunk($this->Get("tplNotify"));
		if(empty($this->_templates["notify"])) $this->_templates["notify"] = file_get_contents($this->_config["path"]."/chunk.notify.inc.txt");
		
		// Links		
		
		$this->_config["querykey"]["action"] = "jot".substr($this->_instance,0,8);
		$this->_config["querykey"]["navigation"] = "jn".substr($this->_instance,0,8);
		$this->_config["querykey"]["id"] = "jid".substr($this->_instance,0,8);
		$this->_config["querykey"]["view"] = "jv".substr($this->_instance,0,8);
		
		$this->_link = array($this->_config["querykey"]["action"]=>NULL,$this->_config["querykey"]["id"]=>NULL);
		$this->_config["link"]["current"] = $this->preserveUrl($modx->documentIdentifier,'',$this->_link);
		$this->_config["link"]["navigation"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["navigation"]=>NULL)),true);
		$this->_config["link"]["subscribe"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["action"]=>'subscribe')));
		$this->_config["link"]["unsubscribe"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["action"]=>'unsubscribe')));
		$this->_config["link"]["delete"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["action"]=>'delete')),true);
		$this->_config["link"]["view"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["view"]=>NULL)),true);
		$this->_config["link"]["publish"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["action"]=>'publish')));
		$this->_config["link"]["unpublish"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->_config["querykey"]["action"]=>'unpublish')));
		
		// Check for first run
		$this->_provider->FirstRun($this->_config["path"]);
		
		// Badwords
		$this->_config["badwords"]["enabled"] = !is_null($this->Get("badwords")) ? 1 : 0;
		$this->_config["badwords"]["type"] = !is_null($this->Get("bw")) ? intval($this->Get("bw")) : 1;
		if($this->_config["badwords"]["enabled"]) {
			$badwords = $this->Get("badwords");
			$badwords = preg_replace("~([\n\r\t\s]+)~","",$badwords);
			$this->_config["badwords"]["words"] = explode(",",$badwords);
			$this->_config["badwords"]["regexp"] = "~" . implode("|",$this->_config["badwords"]["words"]) . "~i";
		}
				
		// Moderation
		if ($this->_config["moderation"]["enabled"]) {
			$this->_config["moderation"]["view"] = $view = isset($_GET[$this->_config["querykey"]["view"]]) ? $_GET[$this->_config["querykey"]["view"]]: 1;
		}
		
		// Subscription
		if ($this->_config["user"]["id"] && $this->_config["subscribe"]) {
			$this->_config["subscription"]["enabled"] = 1;
			$isSubscribed = $this->_provider->hasSubscription($this->_config["docid"],$this->_config["tagid"], $this->_config["user"]);
			if ($isSubscribed) { $this->_config["subscription"]["status"] = 1;}
			 else { $this->_config["subscription"]["status"] = 0; }
		} else {
			$this->_config["subscription"]["enabled"] = 0;
		}
			
		// Active action
		$activeMode = $_GET[$this->_config["querykey"]["action"]];
		switch ($activeMode) {
			case "delete":
				$this->Moderate('delete',$_GET[$this->_config["querykey"]["id"]]);
				break;
			case "publish":
				$this->Moderate('publish',$_GET[$this->_config["querykey"]["id"]]);
				break;
			case "unpublish":
				$this->Moderate('unpublish',$_GET[$this->_config["querykey"]["id"]]);
				break;
			case "edit":
			case "move":
			case "subscribe":
					if ($this->_config["subscription"]["enabled"] == 1) {
						if ($this->_config["subscription"]["status"] == 0) {
							$this->_provider->Subscribe($this->_config["docid"],$this->_config["tagid"],$this->_config["user"]);
							$this->_config["subscription"]["status"] = 1;
						}
					}
					break;
			case "unsubscribe":
					if ($this->_config["subscription"]["enabled"] == 1) {
						if ($this->_config["subscription"]["status"] == 1) {
							$this->_provider->Unsubscribe($this->_config["docid"],$this->_config["tagid"],$this->_config["user"]);
							$this->_config["subscription"]["status"] = 0;
						}
					}
			break;
		}
	
		// Passive Action
		$passiveMode = $this->Get("action");
							
		switch ($passiveMode) {
		  case "count-comments" : $output = $this->CommentCount(); break;
		  case "count-subscriptions": $output = $this->SubscriptionCount(); break;
		  case "comments": $output = $this->ShowComments(); break;
		  case "form": $output = $this->ShowForm(); break;
		  case "default":
		  default: $output = $this->ActionDefault(); break;
		}
		
		if ($this->_config["debug"]) {
			$output .= '<br /><hr /><b>'.$this->_name.' '.$this->_version.': Debug</b><hr /><pre style="background-color: white;font-weight: bold;">';
			$output .= $this->PrintDebug($this->_config,"jot");
			$output .= '</pre><hr />';
	  }
		
		// Dump config into placeholders?
		if ($this->_config["placeholders"]) {
			$this->CreatePlaceholders($this->_config,"jot");
		}
		
		// Add default styling
		$src = '<link rel="stylesheet" type="text/css" href="'.$modx->config["base_url"].'assets/snippets/jot/jot.css" />';
		$modx->regClientCSS($src);
		
		return $output;
	}
	
	function PrintDebug($value = '', $key = '', $path = '') {
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    $output = array();
		if (is_array($value)) { 
			foreach ($value as $subkey => $subval) {
				$output[] = $this->PrintDebug($subval, $subkey, $keypath);
            }
		} else { 
			$output[] = '<span style="color: navy;">'.$keypath.'</span> = <span style="color: maroon;">'.htmlspecialchars($value).'</span><br />';	
		}
		return implode("",$output);
	}
	
	function CreatePlaceholders($value = '', $key = '', $path = '') {
		global $modx;
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    $output = array();
		if (is_array($value)) { 
			foreach ($value as $subkey => $subval) {
				$this->CreatePlaceholders($subval, $subkey, $keypath);
            }
		} else {
			if (strlen($this->_config["tagid"]) > 0) {$keypath .= ".".$this->_config["tagid"]; }
			$modx->setPlaceholder($keypath,$value);	
		}
	}
	
	
	function ActionDefault() {
		$output = $this->ShowForm();
		$output .= $this->ShowComments();
		return $output;
	}
	
	function ShowComments() {
		// Check if viewing is allowed
		if($this->_allowAnyView || $modx->isMemberOfWebGroup($this->_config["xs_view"])) {
				
				// View (Moderation)
				$view = 1;
				if ($this->_config["moderation"]["enabled"]) { 
					$view = $this->_config["moderation"]["view"];
					$this->_config["moderation"]["published"] = $this->CommentCount(1);
					$this->_config["moderation"]["unpublished"] = $this->CommentCount(0);
				}
				
				// Get total number of comments
				$commentTotal = $this->CommentCount($view);
				$pagination = $this->_config["pagination"];
				
				// Apply pagination if enabled
				if ($pagination > 0) {
					$pageLength = $pagination;
					$pageTotal = ceil($commentTotal / $pageLength);
					$pageCurrent = isset($_GET[$this->_config["querykey"]["navigation"]]) ? $_GET[$this->_config["querykey"]["navigation"]]: 1;
					if ( ($pageCurrent < 1) || ($pageCurrent > $pageTotal) ) { $pageCurrent = 1; };
					$pageOffset = (($pageCurrent*$pageLength)-$pageLength);
					$navStart = ($pageOffset+1);
					$navEnd = ($pageOffset+$pageLength) > $commentTotal ? $commentTotal : ($pageOffset+$pageLength);
				} else {
					$pageLength = 0;
					$pageOffset = 0;
					$pageTotal = 1;
					$pageCurrent = 1;
					$navStart = 0;
					$navEnd = $commentTotal;
				}
				
				// Navigation
				$this->_config['nav'] = array('total'=>$commentTotal,'start'=>$navStart,'end'=> $navEnd);
				$this->_config['page'] = array('length'=>$pageLength,'total'=>$pageTotal,'current'=>$pageCurrent);
				
				// Render Moderation Options
				$output_moderate = NULL;
				if ($this->_config["moderation"]["enabled"]) { 
					$tpl = new CChunkie($this->_templates["moderatebar"]);
					$tpl->AddVar('jot',$this->_config);
					$this->_config["html"]["moderatebar"] = $output_moderate = $tpl->Render();
				}
					
				// Get comments
				$array_comments = $this->_provider->GetComments($this->_config["docid"],$this->_config["tagid"],$view,"",$pageOffset,$pageLength);
								
				// Render comments (basic)
				$output_navigation = NULL;
				if (($pagination > 0) && ($pageTotal > 1) ) {
						$tpl = new CChunkie($this->_templates["navigation"]);
						$tpl->AddVar('jot',$this->_config);
						$output_navigation = $tpl->Render();
				}	
				
				$output_subscribe = NULL;
				$tpl = new CChunkie($this->_templates["subscribe"]);
				$tpl->AddVar('jot',$this->_config);
				$this->_config["html"]["subscribe"] = $output_subscribe = $tpl->Render();
				
				$commentpl = $this->_templates["comments"];
				if ($this->_config["moderation"]["enabled"]) { 
					$commentpl = str_replace("[+chunk.moderate+]",$this->_templates["moderate"],$commentpl);
				}	else {
					$commentpl = str_replace("[+chunk.moderate+]","",$commentpl);
				}

				$count = count($array_comments);
				$comments = array();
				for ($i = 0; $i < $count; $i++) {
					$chunk["rowclass"] = $this->ChunkRowClass($i+1,$array_comments[$i]["createdby"]);
					$tpl = new CChunkie($commentpl);
					$tpl->AddVar('jot',$this->_config);
					$tpl->AddVar('comment',$array_comments[$i]);
					$tpl->AddVar('chunk',$chunk);
					$comments[] = $tpl->Render();
				}
				
				$this->_config["html"]["comments"] = $output_comments = join("",$comments);
				$this->_config["html"]["navigation"] = $output_navigation;
				$output_comments = $output_subscribe.$output_moderate.$output_navigation.$output_comments.$output_navigation;
				
				// Render Comments (advanced using chunkie repeat and if)
				#$tpl = new CChunkie($this->_templates["comments"]);
				#$tpl->AddVar('jot',$this->_config);
				#$tpl->AddVar('comments',$array_comments);
				#$output_comments = $tpl->Render();
		}		
		if ($this->_config["output"]) return $output_comments;
	}
		
	function ShowForm() {
		global $modx;
		$output_form = NULL;
		$form['error'] = 0;
		if($this->_allowAnyPost || $modx->isMemberOfWebGroup($this->_config["permissions"]["post"])) {
			// Process Form
			if ($_POST["JotForm"] == $this->_instance ) {
				$pObj = $this->_provider;
				# security check
				if ($this->_config["postdelay"] != 0) {
					$failPostDelay = $pObj->hasPosted($this->_config["postdelay"],$this->_config["user"]);
				}
				$validateMsg = preg_match("~[\w]~",$_POST['message']);
				if (!$failPostDelay && $validateMsg) {
				if (($this->_config["captcha"] == 0 || isset($_POST['formcode']) && isset($_SESSION['veriword']) && $_SESSION['veriword'] == $_POST['formcode'])) {
					
					# post form
					$pObj->Comment();
					$published = 1;
					$badwords = 0;

					foreach($_POST as $n=>$v) {
						if (get_magic_quotes_gpc()) { $v = stripslashes($v); }
						switch($n) {
							case 'subject': $pObj->Set("title",$v); break;
							case 'message': $pObj->Set("content",$v); break;
							default:
								if (in_array($n, $this->_config["customfields"])) {
									$pObj->SetCustom($n,$v);
								}
						}

						if ($this->_config["badwords"]["enabled"]) {
							$badwords = $badwords + preg_match_all ($this->_config["badwords"]["regexp"],$v,$matches);
						}
					}				

					// Check publish settings (moderations)
					if ($published && $this->_config["moderation"]["type"] && !$this->_config["moderation"]["trusted"]) {
						$published = 0;
						$form['error'] = -1;
					}
					
					// Badwords detected
					$this->_config["badwords"]["found"] = $badwords;
					if ($published && $this->_config["badwords"]["found"] && $this->_config["badwords"]["enabled"] && !$this->_config["moderation"]["trusted"]) {
						switch($this->_config["badwords"]["type"]) {
							case 2: // Post Rejected
								$published = -1;
								$form['error'] = -2; 
								break;
							case 1:  // Post Not Published
								$published = 0;
								$form['error'] = -1; // Post Not Published
								break;
							}
					}
					
				
					// Create Comment
					$pObj->Set("published",$published);
					$pObj->Set("createdon",$this->_ctime);
					$pObj->Set("createdby",$this->_config["user"]["id"]);
					$pObj->Set("secip",$this->_config["user"]["ip"]);
					$pObj->Set("sechash",$this->_config["user"]["sechash"]);
					$pObj->Set("uparent",$this->_config["docid"]);
					$pObj->Set("tagid",$this->_config["tagid"]);
					if ($published>=0) $pObj->Save();
					
					// Notify Subscribers
					if ($published>0) $this->NotifySubscribers();
					
				} else { $form['error'] = 1;}
			  } else {
			  		
					if (!$validateMsg) { 
						# no message content
						$form['error'] = 3; }
					else {
						# post to fast
				  		$form['error'] = 2;
					}
						
			  		
			  }
			}
			
			// if post failed save fields
			$submitted = array();
			if ($form['error'] > 0) {
				foreach($_POST as $n=>$v) {
					if (get_magic_quotes_gpc()) { $v = stripslashes($v); }
					$submitted[$n] = $v;
				}
			}
													
			// Render Form
			$tpl = new CChunkie($this->_templates["form"]);
			$tpl->AddVar('jot',$this->_config);
			$tpl->AddVar('form',$form);
			$tpl->AddVar('submit',$submitted);
			$this->_config["html"]["form"] = $output_form = $tpl->Render();
		}
		if ($this->_config["output"]) return $output_form;
	}
	
	// Notifications
	function NotifySubscribers() {
		global $modx;
		if ($this->_config["subscription"]["enabled"]) {
				$subscriptions = $this->_provider->GetSubscriptions($this->_config["docid"],$this->_config["tagid"]);
				$count = count($subscriptions);
				for ($i = 0; $i < $count; $i++) {
						if ($this->_config["user"]["id"] != $subscriptions[$i]["userid"] ) {
							$user = $this->UserInfo($subscriptions[$i]["userid"]);
							$tpl = new CChunkie($this->_templates["notify"]);
							$tpl->AddVar('jot',$this->_config);
							$tpl->AddVar('siteurl',"http://".$_SERVER["SERVER_NAME"]);
							$tpl->AddVar('recipient',$user);
							$message = $tpl->Render();
							mail($user["email"], $this->_config["notifysubject"], $message, "From: ".$modx->config['emailsender']."\r\n"."X-Mailer: Content Manager - PHP/".phpversion());
						}
				}
		}
	}
	
	// Return comment count
	function CommentCount($view=1) {
		return $this->_provider->GetCommentCount($this->_config["docid"],$this->_config["tagid"],$view);
	}
	
	// Return subscription count
	function SubscriptionCount() {
		return "Subscription Count!";
	}
	
	// Moderation
	function Moderate($action = '',$id) {
		$output = NULL;
		if ($this->_config["moderation"]["enabled"]) {
			$pObj = $this->_provider;
			switch ($action) {
				case "delete":
					$pObj->Comment($id);
					$pObj->Delete();
				case "publish":
					$pObj->Comment($id);
					$pObj->Set("publishedon",$this->_ctime);
					$pObj->Set("publishedby",$this->_config["user"]["id"]);
					$pObj->Set("published",1);
					$pObj->Save();
					$this->NotifySubscribers();
					break;
				case "unpublish":
					$pObj->Comment($id);
					$pObj->Set("publishedon",$this->_ctime);
					$pObj->Set("publishedby",$this->_config["user"]["id"]);
					$pObj->Set("published",0);
					$pObj->Save();
					break;
			}
		}
		return $output;
	}
	
	function UserInfo($userid = 0,$field = NULL) {
		global $modx;
		if (intval($userid) < 0) {
			$user = $modx->getWebUserInfo(-($userid));
		} else {
			$user = $modx->getUserInfo($userid);
		}
		if ($field) {	return $user[$field]; }
		return $user;
	}	
	
	// Templating (only in basic)
	function ChunkRowClass($count,$userid) {
		if ( $this->_config["user"]["id"] == $userid && ($userid != 0)) {
			$rowstyle = "jot-row-me";
		} elseif ( $this->_config["authorid"] == $userid && ($userid != 0) ) {
			$rowstyle = "jot-row-author";
		} else {
			$rowstyle = ($count%2) ? "jot-row-alt" : "";
		}
		return $rowstyle;
	}
	
	// Preserve querystring when creating an url
	function preserveUrl($docid = '', $alias = '', $array_values = array(), $suffix = false) {
		global $modx;
		$array_get = $_GET;
		$urlstring = array();
		
		unset($array_get["id"]);
		unset($array_get["q"]);
		
		$array_url = array_merge($array_get, $array_values);
		foreach ($array_url as $name => $value) {
			if (!is_null($value)) {
			  $urlstring[] = $name . '=' . urlencode($value);
			}
		}
		
		$url = join('&',$urlstring);
		if ($suffix) {
			if (empty($url)) { $url = "?"; }
			 else { $url .= "&"; }
		}
		
		return $modx->makeUrl($docid, $alias, $url);
	}
	
}
?>