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
	var $config = array();
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
		$this->_name = $this->config["snippet"]["name"] = "Jot";
		$this->_version = $this->config["snippet"]["version"] = "1.0 Beta 8";
		$this->config["snippet"]["versioncheck"] = "Unknown";
		$this->_ctime = time();
		$this->_check = 0;
		$this->_useMngUser = 0;
		$this->provider = new CJotDataDb;
	}
	
	function VersionCheck($version) {	
		if ($version == $this->_version) {
			$this->_check = 1;
		}
		$this->config["snippet"]["versioncheck"] = $version;
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
		$this->config["path"] = $this->Get("path");
		if (!$this->_check) {
			$output = '<div style="border: 1px solid red;font-weight: bold;margin: 10px;padding: 5px;">
			Jot cannot load because the snippet code version ('.$this->config["snippet"]["versioncheck"].') isn\'t the same as the snippet included files version ('.$this->config["snippet"]["version"].').
			Possible cause is that you updated the jot files in the modx directory but didn\'t update the snippet code from the manager. The content for the updated snippet code can be found in jot.snippet.txt
			</div>';
			return $output;
		}
		
		// General settings
		$this->config["docid"] = !is_null($this->Get("docid")) ? intval($this->Get("docid")):$modx->documentIdentifier;
		$this->config["tagid"] = !is_null($this->Get("tagid")) ? preg_replace("/[^A-z0-9_\-]/",'',$this->Get("tagid")):'';
		$this->config["pagination"] = !is_null($this->Get("pagination")) ? $this->Get("pagination") : 0; // Set pagination (0 = disabled, # = comments per page)
		$this->config["captcha"] = !is_null($this->Get("captcha")) ? intval($this->Get("captcha")) : 0; // Set captcha (0 = disabled, 1 = enabled, 2 = enabled for not logged in users)
		$this->config["postdelay"] = !is_null($this->Get("postdelay")) ? $this->Get("postdelay") : 15; // Set post delay in seconds
		$this->config["guestname"] = !is_null($this->Get("guestname")) ? $this->Get("guestname") : "Anonymous"; // Set guestname if none is specified
		$this->config["subscribe"] = !is_null($this->Get("subscribe")) ? intval($this->Get("subscribe")) : 0;
		$this->config["placeholders"] = !is_null($this->Get("placeholders")) ? intval($this->Get("placeholders")) : 0;
		$this->config["authorid"] = !is_null($this->Get("authorid")) ? intval($this->Get("authorid")) : $modx->documentObject["createdby"];
		$this->config["subject"]["subscribe"] = !is_null($this->Get("subjectSubscribe")) ? $this->Get("subjectSubscribe") : "New reply to a topic you are watching";
		$this->config["subject"]["moderate"] = !is_null($this->Get("subjectModerate")) ? $this->Get("subjectModerate") : "New reply to a topic you are moderating";
		$this->config["debug"] = !is_null($this->Get("debug")) ? intval($this->Get("debug")) : 0;
		$this->config["output"] = !is_null($this->Get("output")) ? intval($this->Get("output")) : 1;
		
		// CSS Settings (basic)
		$this->config["css"]["rowalt"] = !is_null($this->Get("cssRowAlt")) ? $this->Get("cssAltRow") : "jot-row-alt";
		$this->config["css"]["rowme"] = !is_null($this->Get("cssRowMe")) ? $this->Get("cssRowMe") : "jot-row-me";
		$this->config["css"]["rowauthor"] = !is_null($this->Get("cssRowAuthor")) ? $this->Get("cssRowAuthor") : "jot-row-author";
		
		// Security
		$this->config["user"]["mgrid"] = intval($_SESSION['mgrInternalKey']);
		$this->config["user"]["usrid"] = intval($modx->getLoginUserID());
		$this->config["user"]["id"] = ($this->config["user"]["usrid"] > 0 ) ? (-$this->config["user"]["usrid"]) : $this->config["user"]["mgrid"];
		$this->config["user"]["host"] = NULL;
		$this->config["user"]["ip"] = $this->GetIP($this->config["user"]["host"]);
		$this->config["user"]["agent"] = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT']  : 'NO USER AGENT';
		$this->config["user"]["sechash"] = md5($this->config["user"]["id"].$this->config["user"]["host"].$this->config["user"]["ip"].$this->config["user"]["agent"]);
		
		// Automatic settings
		$this->_instance = $this->config["id"] = $this->UniqueId($this->config["docid"],$this->config["tagid"]);
		$this->_idshort = substr($this->_instance,0,8);
		if($this->config["captcha"] == 2) { if ($this->config["user"]["id"]) {	$this->config["captcha"] = 0;} else { $this->config["captcha"] = 1;} }
		$this->config["guestform"] = intval(($this->config["user"]["id"]) ? false : true);
		$this->config["seed"] = rand();
		$this->config["doc.pagetitle"] = $modx->documentObject["pagetitle"];
				
		#$sortorder = isset($sortorder) ? $sortorder : 1;
		$this->config["customfields"] = $this->Get("customfields") ? explode(",",$this->Get("customfields")):array(); // Set names of custom fields
								
		// Set access groups
		$this->config["permissions"]["post"] = !is_null($this->Get("canpost")) ? explode(",",$this->Get("canpost")):array();
		$this->config["permissions"]["view"] = !is_null($this->Get("canview")) ? explode(",",$this->Get("canview")):array();
		$this->config["permissions"]["moderate"] = !is_null($this->Get("canmoderate")) ? explode(",",$this->Get("canmoderate")):array();
		$this->config["permissions"]["trusted"] = !is_null($this->Get("trusted")) ? explode(",",$this->Get("trusted")):array();
		
		// Set Any access
		$this->_allowAnyPost = count($this->config["permissions"]["post"])==0 ? true : false;
		$this->_allowAnyView = count($this->config["permissions"]["view"])==0 ? true : false;
		
		// Moderation
		$this->config["moderation"]["type"] = !is_null($this->Get("moderated")) ? intval($this->Get("moderated")) : 0;
		$this->config["moderation"]["enabled"] = intval($modx->isMemberOfWebGroup($this->config["permissions"]["moderate"] ) || $modx->checkSession());
		$this->config["moderation"]["trusted"] = intval($modx->isMemberOfWebGroup($this->config["permissions"]["trusted"] ) || $modx->checkSession());
		$this->config["moderation"]["notify"] = !is_null($this->Get("notify")) ? intval($this->Get("notify")) : 1;
		
		// Templates
		$this->templates["form"] = !is_null($this->Get("tplForm")) ? $this->Get("tplForm") : $this->config["path"]."/chunk.form.inc.html";
		$this->templates["comments"] = !is_null($this->Get("tplComments")) ? $this->Get("tplComments") : $this->config["path"]."/chunk.comment.inc.html";
		$this->templates["navigation"] = !is_null($this->Get("tplNav")) ? $this->Get("tplNav") : $this->config["path"]."/chunk.navigation.inc.html";
		$this->templates["moderate"] = !is_null($this->Get("tplModerate")) ? $this->Get("tplModerate") : $this->config["path"]."/chunk.moderate.inc.html";
		$this->templates["subscribe"] = !is_null($this->Get("tplSubscribe")) ? $this->Get("tplSubscribe") : $this->config["path"]."/chunk.subscribe.inc.html";
		$this->templates["notify"] = !is_null($this->Get("tplNotify")) ? $this->Get("tplNotify") : $this->config["path"]."/chunk.notify.inc.txt";				
		$this->templates["notifymoderator"] = !is_null($this->Get("tplNotifyModerator")) ? $this->Get("tplNotifyModerator") : $this->config["path"]."/chunk.notify.moderator.inc.txt";

		// Querystring Keys
		$this->config["querykey"]["action"] = "jot".$this->_idshort;
		$this->config["querykey"]["navigation"] = "jn".$this->_idshort;
		$this->config["querykey"]["id"] = "jid".$this->_idshort;
		$this->config["querykey"]["view"] = "jv".$this->_idshort;
		
		// Generated links
		$this->_link = array($this->config["querykey"]["action"]=>NULL,$this->config["querykey"]["id"]=>NULL);
		$this->config["link"]["current"] = $this->preserveUrl($modx->documentIdentifier,'',$this->_link);
		$this->config["link"]["navigation"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["navigation"]=>NULL)),true);
		$this->config["link"]["subscribe"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["action"]=>'subscribe')));
		$this->config["link"]["unsubscribe"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["action"]=>'unsubscribe')));
		$this->config["link"]["delete"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["action"]=>'delete')),true);
		$this->config["link"]["view"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["view"]=>NULL)),true);
		$this->config["link"]["publish"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["action"]=>'publish')));
		$this->config["link"]["unpublish"] = $this->preserveUrl($modx->documentIdentifier,'',array_merge($this->_link,array($this->config["querykey"]["action"]=>'unpublish')));
		
		// Check for first run
		$this->provider->FirstRun($this->config["path"]);
		
		// Badwords
		$this->config["badwords"]["enabled"] = !is_null($this->Get("badwords")) ? 1 : 0;
		$this->config["badwords"]["type"] = !is_null($this->Get("bw")) ? intval($this->Get("bw")) : 1;
		if($this->config["badwords"]["enabled"]) {
			$badwords = $this->Get("badwords");
			$badwords = preg_replace("~([\n\r\t\s]+)~","",$badwords);
			$this->config["badwords"]["words"] = explode(",",$badwords);
			$this->config["badwords"]["regexp"] = "~" . implode("|",$this->config["badwords"]["words"]) . "~i";
		}
				
		// Moderation
		if ($this->config["moderation"]["enabled"]) {
			$this->config["moderation"]["view"] = $view = isset($_GET[$this->config["querykey"]["view"]]) ? $_GET[$this->config["querykey"]["view"]]: 2;
		}
		
		// Subscription
		$this->config["subscription"]["enabled"] = 0;
		$this->config["subscription"]["status"] = 0;
		if ($this->config["user"]["id"] && $this->config["subscribe"]) {
			$this->config["subscription"]["enabled"] = 1;
			$isSubscribed = $this->provider->hasSubscription($this->config["docid"],$this->config["tagid"], $this->config["user"]);
			if ($isSubscribed) $this->config["subscription"]["status"] = 1;
		}
					
		// Active action
		$activeMode = $_GET[$this->config["querykey"]["action"]];
		switch ($activeMode) {
			case "delete":
				$this->doModerate('delete',$_GET[$this->config["querykey"]["id"]]);
				break;
			case "publish":
				$this->doModerate('publish',$_GET[$this->config["querykey"]["id"]]);
				break;
			case "unpublish":
				$this->doModerate('unpublish',$_GET[$this->config["querykey"]["id"]]);
				break;
			case "edit":
			case "move":
			case "subscribe":
					if ($this->config["subscription"]["enabled"] == 1) {
						if ($this->config["subscription"]["status"] == 0) {
							$this->provider->Subscribe($this->config["docid"],$this->config["tagid"],$this->config["user"]);
							$this->config["subscription"]["status"] = 1;
						}
					}
					break;
			case "unsubscribe":
					if ($this->config["subscription"]["enabled"] == 1) {
						if ($this->config["subscription"]["status"] == 1) {
							$this->provider->Unsubscribe($this->config["docid"],$this->config["tagid"],$this->config["user"]);
							$this->config["subscription"]["status"] = 0;
						}
					}
			break;
		}
	
		// Passive Action
		$passiveMode = $this->Get("action");
							
		switch ($passiveMode) {
		  case "count-comments" : $output = $this->getCommentCount(); break;
		  case "count-subscriptions": $output = $this->SubscriptionCount(); break;
		  case "comments": $output = $this->getOutputComments(); break;
		  case "form": $output = $this->getOutputForm(); break;
		  case "default":
		  default: $output = $this->getOutputDefault(); break;
		}
		
		if ($this->config["debug"]) {
			$output .= '<br /><hr /><b>'.$this->_name.' '.$this->_version.': Debug</b><hr /><pre style="background-color: white;font-weight: bold;">';
			$output .= $this->getOutputDebug($this->config,"jot");
			$output .= '</pre><hr />';
	  }
		
		// Dump config into placeholders?
		if ($this->config["placeholders"]) {
			$this->setPlaceholders($this->config,"jot");
		}
		
		// Add default styling
		$src = '<link rel="stylesheet" type="text/css" href="'.$modx->config["base_url"].'assets/snippets/jot/jot.css" />';
		$modx->regClientCSS($src);
		
		return $output;
	}
	
	function getOutputDebug($value = '', $key = '', $path = '') {
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    $output = array();
		if (is_array($value)) { 
			foreach ($value as $subkey => $subval) {
				$output[] = $this->getOutputDebug($subval, $subkey, $keypath);
            }
		} else { 
			$output[] = '<span style="color: navy;">'.$keypath.'</span> = <span style="color: maroon;">'.htmlspecialchars($value).'</span><br />';	
		}
		return implode("",$output);
	}
	
	function setPlaceholders($value = '', $key = '', $path = '') {
		global $modx;
		$keypath = !empty($path) ? $path . "." . $key : $key;
	    $output = array();
		if (is_array($value)) { 
			foreach ($value as $subkey => $subval) {
				$this->setPlaceholders($subval, $subkey, $keypath);
            }
		} else {
			if (strlen($this->config["tagid"]) > 0) {$keypath .= ".".$this->config["tagid"]; }
			$modx->setPlaceholder($keypath,$value);	
		}
	}
	
	
	function getOutputDefault() {
		global $modx;
		$output = $this->getOutputForm();
		$output .= $this->getOutputComments();
		return $output;
	}
	
	function getOutputComments() {
		// Check if viewing is allowed
		if($this->_allowAnyView || $modx->isMemberOfWebGroup($this->config["xs_view"])) {
				
				// View (Moderation)
				$view = 1;
				if ($this->config["moderation"]["enabled"]) { 
					$view = $this->config["moderation"]["view"];
					$this->config["moderation"]["unpublished"] = $this->getCommentCount(0);
					$this->config["moderation"]["published"] = $this->getCommentCount(1);
					$this->config["moderation"]["mixed"] = $this->getCommentCount(2);
				}
				
				// Get total number of comments
				$commentTotal = $this->getCommentCount($view);
				$pagination = $this->config["pagination"];
				
				// Apply pagination if enabled
				if ($pagination > 0) {
					$pageLength = $pagination;
					$pageTotal = ceil($commentTotal / $pageLength);
					$pageCurrent = isset($_GET[$this->config["querykey"]["navigation"]]) ? $_GET[$this->config["querykey"]["navigation"]]: 1;
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
				$this->config['nav'] = array('total'=>$commentTotal,'start'=>$navStart,'end'=> $navEnd);
				$this->config['page'] = array('length'=>$pageLength,'total'=>$pageTotal,'current'=>$pageCurrent);
				
				// Render Moderation Options
				$output_moderate = NULL;
				if ($this->config["moderation"]["enabled"]) { 
					$tpl = new CChunkie($this->templates["moderate"]);
					$tpl->AddVar('jot',$this->config);
					$this->config["html"]["moderate"] = $output_moderate = $tpl->Render();
				}
					
				// Get comments
				$array_comments = $this->provider->GetComments($this->config["docid"],$this->config["tagid"],$view,"",$pageOffset,$pageLength);
								
				// Render navigation
				$output_navigation = NULL;
				if (($pagination > 0) && ($pageTotal > 1) ) {
						$tpl = new CChunkie($this->templates["navigation"]);
						$tpl->AddVar('jot',$this->config);
						$output_navigation = $tpl->Render();
				}	
				
				// Render subscription options
				$output_subscribe = NULL;
				$tpl = new CChunkie($this->templates["subscribe"]);
				$tpl->AddVar('jot',$this->config);
				$this->config["html"]["subscribe"] = $output_subscribe = $tpl->Render();
				
				// Render comments
				$count = count($array_comments);
				$comments = array();
				for ($i = 0; $i < $count; $i++) {
					$chunk["rowclass"] = $this->getChunkRowClass($i+1,$array_comments[$i]["createdby"]);
					$tpl = new CChunkie($this->templates["comments"]);
					$tpl->AddVar('jot',$this->config);
					$tpl->AddVar('comment',$array_comments[$i]);
					$tpl->AddVar('chunk',$chunk);
					$comments[] = $tpl->Render();
				}

				$this->config["html"]["comments"] = $output_comments = join("",$comments);
				$this->config["html"]["navigation"] = $output_navigation;
				$output_comments = $output_subscribe.$output_moderate.$output_navigation.$output_comments.$output_navigation;
		}		
		if ($this->config["output"]) return $output_comments;
	}
		
	function getOutputForm() {
		global $modx;
		$output_form = NULL;
		$form['error'] = 0;
		if($this->_allowAnyPost || $modx->isMemberOfWebGroup($this->config["permissions"]["post"])) {
			// Process Form
			if ($_POST["JotForm"] == $this->_instance ) {
				$pObj = $this->provider;
				# security check
				if ($this->config["postdelay"] != 0) {
					$failPostDelay = $pObj->hasPosted($this->config["postdelay"],$this->config["user"]);
				}
				$validateMsg = preg_match("~[\w]~",$_POST['message']);
				if (!$failPostDelay && $validateMsg) {
				if (($this->config["captcha"] == 0 || isset($_POST['formcode']) && isset($_SESSION['veriword']) && $_SESSION['veriword'] == $_POST['formcode'])) {
					
					# post form
					$pObj->Comment();
					$isPublished = 1;
					$badwords = 0;

					foreach($_POST as $n=>$v) {
						if (get_magic_quotes_gpc()) { $v = stripslashes($v); }
						switch($n) {
							case 'subject': $pObj->Set("title",$v); break;
							case 'message': $pObj->Set("content",$v); break;
							default:
								if (in_array($n, $this->config["customfields"])) {
									$pObj->SetCustom($n,$v);
								}
						}

						if ($this->config["badwords"]["enabled"]) {
							$badwords = $badwords + preg_match_all($this->config["badwords"]["regexp"],$v,$matches);
						}
					}				

					// Check publish settings (moderations)
					if ($isPublished && $this->config["moderation"]["type"] && !$this->config["moderation"]["trusted"]) {
						$isPublished = 0;
						$form['error'] = -1;
					}
					
					// Badwords detected
					$this->config["badwords"]["found"] = $badwords;
					if ($isPublished && $this->config["badwords"]["found"] && $this->config["badwords"]["enabled"] && !$this->config["moderation"]["trusted"]) {
						switch($this->config["badwords"]["type"]) {
							case 2: // Post Rejected
								$isPublished = -1;
								$form['error'] = -2; 
								break;
							case 1:  // Post Not Published
								$isPublished = 0;
								$form['error'] = -1; // Post Not Published
								break;
							}
					}
					
				
					// Create Comment
					$pObj->Set("published",$isPublished);
					$pObj->Set("createdon",$this->_ctime);
					$pObj->Set("createdby",$this->config["user"]["id"]);
					$pObj->Set("secip",$this->config["user"]["ip"]);
					$pObj->Set("sechash",$this->config["user"]["sechash"]);
					$pObj->Set("uparent",$this->config["docid"]);
					$pObj->Set("tagid",$this->config["tagid"]);
					if ($isPublished>=0) $pObj->Save();
					
					// Notify Subscribers
					if ($isPublished>0) $this->doNotifySubscribers($pObj->Get("id"));
					
					// Notify Moderators
					if (($isPublished==0) || ($isPublished>0 && $this->config["moderation"]["notify"]==2)) $this->doNotifyModerators($pObj->Get("id"));
					
					// Destroy Comment Object
					unset($pObj);
					
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
			$tpl = new CChunkie($this->templates["form"]);
			$tpl->AddVar('jot',$this->config);
			$tpl->AddVar('form',$form);
			$tpl->AddVar('submit',$submitted);
			$this->config["html"]["form"] = $output_form = $tpl->Render();
		}
		if ($this->config["output"]) return $output_form;
	}
	
	// Notifications
	function doNotifySubscribers($commentid=0) {
		global $modx;
		if ($this->config["subscription"]["enabled"]) {
				
				// Get comment fields
				$cObj = $this->provider;
				$cObj->Comment($commentid);
				$comment = $cObj->getFields();
				unset($cObj);
																
				$subscriptions = $this->provider->getSubscriptions($this->config["docid"],$this->config["tagid"]);
				$count = count($subscriptions);
				for ($i = 0; $i < $count; $i++) {
						if ($this->config["user"]["id"] != $subscriptions[$i]["userid"] ) {
							$user = $this->getUserInfo($subscriptions[$i]["userid"]);
							$tpl = new CChunkie($this->templates["notify"]);
							$tpl->AddVar('jot',$this->config);
							$tpl->AddVar('comment',$comment);
							$tpl->AddVar('siteurl',"http://".$_SERVER["SERVER_NAME"]);
							$tpl->AddVar('recipient',$user);
							$message = $tpl->Render();
							mail($user["email"], $this->config["subject"]["subscribe"], $message, "From: ".$modx->config['emailsender']."\r\n"."X-Mailer: Content Manager - PHP/".phpversion());
						}
				}
		}
	}
	
	// Moderator Notification
	function doNotifyModerators($commentid=0) {
		global $modx;
		if ($this->config["moderation"]["notify"]) {
			
		  // Get comment fields
			$cObj = $this->provider;
			$cObj->Comment($commentid);
			$comment = $cObj->getFields();
			unset($cObj);
			
			$moderators = $this->getMembersOfWebGroup($this->config["permissions"]["moderate"]);
			foreach ($moderators as $moderator){
				$user = $modx->getWebUserInfo($moderator);
				$tpl = new CChunkie($this->templates["notifymoderator"]);
				$tpl->AddVar('jot',$this->config);
				$tpl->AddVar('comment',$comment);
				$tpl->AddVar('siteurl',"http://".$_SERVER["SERVER_NAME"]);
				$tpl->AddVar('recipient',$user);
				$message = $tpl->Render();
				mail($user["email"], $this->config["subject"]["moderate"], $message, "From: ".$modx->config['emailsender']."\r\n"."X-Mailer: Content Manager - PHP/".phpversion());
			}
		}
	}
	
	// Returns comment count
	function getCommentCount($view=1) {
		return $this->provider->GetCommentCount($this->config["docid"],$this->config["tagid"],$view);
	}
	
	// Returns subscription count (not implemented yet)
	function SubscriptionCount() {
		return "Subscription Count!";
	}
	
	// Moderation
	function doModerate($action = '',$id) {
		$output = NULL;
		if ($this->config["moderation"]["enabled"]) {
			$pObj = $this->provider;
			switch ($action) {
				case "delete":
					$pObj->Comment($id);
					$pObj->Delete();
				case "publish":
					$pObj->Comment($id);
					$pObj->Set("publishedon",$this->_ctime);
					$pObj->Set("publishedby",$this->config["user"]["id"]);
					$pObj->Set("published",1);
					$pObj->Save();
					$this->doNotifySubscribers();
					break;
				case "unpublish":
					$pObj->Comment($id);
					$pObj->Set("publishedon",$this->_ctime);
					$pObj->Set("publishedby",$this->config["user"]["id"]);
					$pObj->Set("published",0);
					$pObj->Save();
					break;
			}
		}
		return $output;
	}
	
	// Gets userinfo from either web or manager users
	function getUserInfo($userid = 0,$field = NULL) {
		global $modx;
		if (intval($userid) < 0) {
			$user = $modx->getWebUserInfo(-($userid));
		} else {
			$user = $modx->getUserInfo($userid);
		}
		if ($field) {	return $user[$field]; }
		return $user;
	}	
	
	
	// Returns an array containing webusers which are a member of the specified group(s).
	function getMembersOfWebGroup($groupNames=array()) {
		global $modx;
		$usrIDs = array();
		$tbl = $modx->getFullTableName("webgroup_names");
		$tbl2 = $modx->getFullTableName("web_groups");
		$sql = "SELECT distinct wg.webuser
						FROM $tbl wgn
						INNER JOIN $tbl2 wg ON wg.webgroup=wgn.id AND wgn.name IN ('" . implode("','",$groupNames) . "')";
		$usrRows = $modx->db->getColumn("webuser", $sql);
		foreach ($usrRows as $k => $v) $usrIDs[] = intval($v);
		return $usrIDs;
	}	

	// Templating
	function getChunkRowClass($count,$userid) {
		if ( $this->config["user"]["id"] == $userid && ($userid != 0)) {
			$rowstyle = "jot-row-me";
		} elseif ( $this->config["authorid"] == $userid && ($userid != 0) ) {
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
		
		return str_replace("&","&amp;",$modx->makeUrl($docid, $alias, $url));
	}
	
}
?>