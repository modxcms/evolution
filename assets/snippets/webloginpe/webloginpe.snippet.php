<?php
	/**
	 * WebLoginPE Snippet 1.3.1
	 * v1.3.1 Bugfix by Soshite @ MODx CMS Forums & Various Other Forum Members
	 *
	 * @package WebLoginPE
	 * @author Scotty Delicious
	 * @version 1.3.1
	 *
	 * See the "docs" folder for detailed usage and parameter instructions.
	 */

	$type = isset($type) ? $type : 'simple';
	$regType = isset($regType) ? $regType : 'instant';
	$notify = isset($notify) ? $notify : '';
	$groups = isset($groups) ? $groups : '';
	$regRequired = isset($regRequired) ? $regRequired : '';
	$customTable = isset($customTable) ? $customTable : 'web_user_attributes_extended';
	$customFields = isset($customFields) ? $customFields : '';
	$prefixTable = isset($prefixTable) ? $prefixTable : 1;
	$lang = isset($lang) ? $lang : 'en';
	$userImageSettings = isset($userImage) ? $userImage : '105000,100,100';
	$dateFormat = isset($dateFormat) ? $dateFormat : '%A %B %d, %Y at %I:%M %p';
	$disableServices = isset($disableServices) ? explode(',', str_replace(', ',',',$disableServices)) : array();
	$tableCheck = isset($tableCheck) ? $tableCheck : 1;
	
	include_once MODX_BASE_PATH.'assets/snippets/webloginpe/webloginpe.class.php';
	include MODX_BASE_PATH.'assets/snippets/webloginpe/webloginpe.templates.php';
	if (file_exists(MODX_BASE_PATH.'assets/snippets/webloginpe/lang/'.$lang.'.php'))
	{
		include_once MODX_BASE_PATH.'assets/snippets/webloginpe/lang/'.$lang.'.php';
	}
	else
	{
		include_once MODX_BASE_PATH.'assets/snippets/webloginpe/lang/en.php';
		$modx->setPlaceholder('wlpe.message', $wlpe_lang[105]);
		print '[+wlpe.message+]';
	}
	
	$wlpe = new WebLoginPE($wlpe_lang, $dateFormat, $userImageSettings, $type);
	$wlpe->CustomTable($customTable, $customFields, $prefixTable, $tableCheck);

	$liHomeId = isset($liHomeId) ? explode(',', $liHomeId) : '';
	$loHomeId = isset($loHomeId) ? $loHomeId : '';
	$regHomeId = isset($regHomeId) ? $regHomeId : '';
	$regSuccessId = isset($regSuccessId) ? $regSuccessId : '';
	$regSuccessPause = isset($regSuccessPause) ? $regSuccessPause : 5;
	$profileHomeId = isset($profileHomeId) ? $profileHomeId : '';
	$inputHandler = isset($inputHandler) ? explode('||', $inputHandler) : array();
	$usersList = isset($usersList) ? $usersList : '';
	
	if ($regType == 'verify'){$wlpeRegisterTpl = $wlpeRegisterVerifyTpl;}else{$wlpeRegisterTpl = $wlpeRegisterInstantTpl;}
	
	$displayLoginFormTpl = isset($loginFormTpl) ? $wlpe->Template($loginFormTpl) : $wlpeDefaultFormTpl;
	$displaySuccessTpl = isset($successTpl) ? $wlpe->Template($successTpl) : $wlpeDefaultSuccessTpl;
	$displayRegisterTpl = isset($registerTpl) ? $wlpe->Template($registerTpl) : $wlpeRegisterTpl;
	$displayRegSuccessTpl = isset($registerSuccessTpl) ? $wlpe->Template($registerSuccessTpl) : $wlpeDefaultFormTpl;
	$displayProfileTpl = isset($profileTpl) ? $wlpe->Template($profileTpl) : $wlpeProfileTpl;
	$displayViewProfileTpl = isset($viewProfileTpl) ? $wlpe->Template($viewProfileTpl) : $wlpeViewProfileTpl;
	$displayUsersOuterTpl = isset($usersOuterTpl) ? $wlpe->Template($usersOuterTpl) : $wlpeUsersOuterTpl;
	$displayUsersTpl = isset($usersTpl) ? $wlpe->Template($usersTpl) : $wlpeUsersTpl;
	$displayManageOuterTpl = isset($manageOuterTpl) ? $wlpe->Template($manageOuterTpl) : $wlpeUsersOuterTpl;
	$displayManageTpl = isset($manageTpl) ? $wlpe->Template($manageTpl) : $wlpeManageTpl;
	$displayManageProfileTpl = isset($manageProfileTpl) ? $wlpe->Template($manageProfileTpl) : $wlpeManageProfileTpl;
	$displayManageDeleteTpl = isset($manageDeleteTpl) ? $wlpe->Template($manageDeleteTpl) : $wlpeManageDeleteTpl;
	$displayProfileDeleteTpl = isset($profileDeleteTpl) ? $wlpe->Template($profileDeleteTpl) : $wlpeProfileDeleteTpl;
	$displayActivateTpl = isset($activateTpl) ? $wlpe->Template($activateTpl) : $wlpeActivateTpl;
	$displayResetTpl = isset($resetTpl) ? $wlpe->Template($resetTpl) : $wlpeResetTpl;
	$notifyTpl = isset($notifyTpl) ? $wlpe->Template($notifyTpl) : $wlpeNotifyTpl;
	$notifySubject = isset($notifySubject) ? $notifySubject : 'New Web User for '.$modx->config['site_name'].'.';
	$messageTpl = isset($messageTpl) ? $wlpe->Template($messageTpl) : $wlpeMessageTpl;
	$tosChunk = isset($tosChunk) ? $wlpe->Template($tosChunk) : $wlpeTos;
	$modx->setPlaceholder('tos', $tosChunk);
	
	$loadJquery = isset($loadJquery) ? $loadJquery : false;
	$customJs = isset($customJs) ? $customJs : '';
	
	if (isset($pruneDays))
	{
		$wlpe->PruneUsers($pruneDays);
	}
		
	if ($loadJquery == 'true' || $loadJquery == true || $loadJquery == 1 || $loadJquery == '1') 
	{
		$wlpe->RegisterScripts($customJs);
	}
	else if (!empty($customJs))
	{
		$modx->regClientStartupScript($customJs);
	}
	
	$wlpe->ActiveUsers();
	$wlpe->PlaceHolders($inputHandler, $messageTpl);
	
	$modx->regClientStartupScript('assets/snippets/webloginpe/js/ieButtonFix.js');

	$service = $_REQUEST['service'];
	if (empty($service) || $service == '')
	{
		$service = $_REQUEST['serviceButtonValue'];
	}
	
	if ($type == 'register')
	{
		if (in_array('register', $disableServices)){return;}
		switch ($service) 
		{
			case 'register' :
				if (in_array('register', $disableServices)){return;}
				$registration = $wlpe->Register($regType, $groups, $regRequired, $notify, $notifyTpl, $notifySubject);
				
				if (isset($regSuccessId) && $regSuccessId !== '')
				{
					if ($registration == 'success')
					{
						$url = rtrim($modx->config['site_url'], '/').$modx->makeURL($regSuccessId);
						header('Refresh: '.$regSuccessPause.';URL='.$url);
						return $displayRegSuccessTpl;
					}
					return $displayRegisterTpl;
					
				}
				if ($registration == 'success')
				{
					return $displayRegSuccessTpl;
				}
				return $displayRegisterTpl;
				break;
				
			case 'cancel':
				if ($loHomeId == '') $loHomeId = $modx->config['site_start'];
				$url = $modx->makeURL($loHomeId);
		        $modx->sendRedirect($url,0,'REDIRECT_REFRESH');
				break;
			
			case 'login' :
				$wlpe->Login($type, $liHomeId);

				if ($modx->getLoginUserID())
				{
					return $displaySuccessTpl;
				}
				return $displayLoginFormTpl;
				break;

			case 'logout' :
				$wlpe->Logout($type, $loHomeId);
				return $displayLoginFormTpl;
				break;
			
			default :
				return $displayRegisterTpl;
		}
		return;
	}
	
	else if ($type == 'profile')
	{
		if (in_array('profile', $disableServices)){return;}
		switch ($service) 
		{
			case 'saveprofile' :
				if (in_array('saveprofile', $disableServices)){return;}
				$wlpe->SaveUserProfile();
				$wlpe->PlaceHolders($inputHandler, $messageTpl);
				return $displayProfileTpl;
				break;
				
			case 'cancel':
				if ($loHomeId == '') $loHomeId = $modx->config['site_start'];
				$url = $modx->makeURL($loHomeId);
		        $modx->sendRedirect($url,0,'REDIRECT_REFRESH');
				break;
				
			case 'logout':
				if ($loHomeId == '') $loHomeId = $modx->config['site_start'];
				$wlpe->Logout($type, $loHomeId);
				break;
				
			case 'deleteprofile':
				if (in_array('deleteprofile', $disableServices)){return;}
				return $displayProfileDeleteTpl;
				break;
			
			case 'confirmdeleteprofile':
				if (in_array('confirmdeleteprofile', $disableServices)){return;}
				$wlpe->RemoveUserProfile();
				return '[+wlpe.message+]';
				break;
				
			default :
				return $displayProfileTpl;
				break;
		}
		return;
	}
	
	else if ($type == 'users')
	{
		if (in_array('users', $disableServices)){return;}
		switch ($service)
		{
			case 'viewprofile':
				if (in_array('viewprofile', $disableServices)){return;}
				$wlpe->ViewUserProfile($_REQUEST['username'],$inputHandler);
				return $displayViewProfileTpl;
				break;
				
			case 'messageuser':
				if (in_array('messageuser', $disableServices)){return;}
				$wlpe->SendMessageToUser();
				return $displayViewProfileTpl;
				break;
			
			default :
				$userpage = $wlpe->ViewAllUsers($displayUsersTpl, $displayUsersOuterTpl, $usersList);
				return $userpage;
		}
		return;
	}
	
	else if ($type == 'manager')
	{
		if (in_array('manager', $disableServices)){return;}
		switch ($service)
		{
			case 'editprofile':
				if (in_array('editprofile', $disableServices)){return;}
				$wlpe->ViewUserProfile($_REQUEST['username'],$inputHandler);
				return $displayManageProfileTpl;
				break;
				
			case 'saveuserprofile' :
				if (in_array('saveuserprofile', $disableServices)){return;}
				$wlpe->SaveUserProfile($_POST['internalKey']);
				$manageUsersPage = $wlpe->ViewAllUsers($displayManageTpl, $displayManageOuterTpl, $usersList);
				return $manageUsersPage;
				break;
				
			case 'messageuser':
				if (in_array('messageuser', $disableServices)){return;}
				$wlpe->SendMessageToUser();
				return $displayViewProfileTpl;
				break;
				
			case 'deleteuser':
				if (in_array('deleteuser', $disableServices)){return;}
				$_SESSION['editInternalKey'] = $_POST['internalKey'];
				return $displayManageDeleteTpl;
				break;

			case 'confirmdeleteuser':
				if (in_array('confirmdeleteuser', $disableServices)){return;}
				$wlpe->RemoveUserProfileManager($_SESSION['editInternalKey']);
				$manageUsersPage = $wlpe->ViewAllUsers($displayManageTpl, $displayManageOuterTpl, $usersList);
				unset($_SESSION['editInternalKey']);
				return $manageUsersPage;
				break;
			
			default :
				$manageUsersPage = $wlpe->ViewAllUsers($displayManageTpl, $displayManageOuterTpl, $usersList);
				return $manageUsersPage;
		}
		return;
	}
	
	else if ($type == 'simple')
	{
		switch ($service) 
		{

			case 'login' :
				$wlpe->Login($type, $liHomeId);

				if ($modx->getLoginUserID())
				{
					return $displaySuccessTpl;
				}
				return $displayLoginFormTpl;
				break;

			case 'logout' :
				$wlpe->Logout($type, $loHomeId);
				return $displayLoginFormTpl;
				break;

			case 'profile' :
				if (in_array('profile', $disableServices)){return;}
				if (empty($profileHomeId))
				{
					return $displayProfileTpl;
				}
				$url = $modx->makeURL($profileHomeId);
		        $modx->sendRedirect($url,0,'REDIRECT_REFRESH');
		        return;
				break;
				
			case 'saveprofilesimple' :
				if (in_array('saveprofile', $disableServices)){return;}
				$wlpe->SaveUserProfile();
				$wlpe->PlaceHolders($inputHandler, $messageTpl);
				return $displayProfileTpl;
				break;
			
			case 'deleteprofilesimple':
				if (in_array('deleteprofile', $disableServices)){return;}
				return $displayProfileDeleteTpl;
				break;

			case 'confirmdeleteprofilesimple':
				if (in_array('confirmdeleteprofile', $disableServices)){return;}
				$wlpe->RemoveUserProfile();
				return '[+wlpe.message+]';
				break;

			case 'registernew' :
				if (in_array('register', $disableServices)){return;}
				if (empty($regHomeId))
				{
					return $displayRegisterTpl;
				}
				$url = $modx->makeURL($regHomeId);
		        $modx->sendRedirect($url,0,'REDIRECT_REFRESH');
		        return;
				break;
				
			case 'register':
				if (in_array('register', $disableServices)){return;}
				$registration = $wlpe->Register($regType, $groups, $regRequired, $notify, $notifyTpl, $notifySubject);
				
				if (isset($regSuccessId) && $regSuccessId !== '')
				{
					if ($registration == 'success')
					{
						$url = rtrim($modx->config['site_url'], '/').$modx->makeURL($regSuccessId);
						header('Refresh: '.$regSuccessPause.';URL='.$url);
						return $displayRegSuccessTpl;
					}
					return $displayRegisterTpl;
					
				}
				if ($registration == 'success')
				{
					return $displayRegSuccessTpl;
				}
				return $displayRegisterTpl;
				break;

			case 'forgot' :
				if (in_array('forgot', $disableServices)){return;}
				return $displayResetTpl;
				break;
			
			case 'resetpassword' :
				if (in_array('resetpassword', $disableServices)){return;}
				$wlpe->ResetPassword();
				if (isset($wlpe->Report)) 
				{
					if (isset($_POST['email']))
					{
						return $displayResetTpl;
					}
					else
					{
						return $displayActivateTpl;
					}
				}
				return;
				break;
			
			case 'activate' :
				if (in_array('activate', $disableServices)){return;}
				return $displayActivateTpl;
				break;
			
			case 'activated':
                if (in_array('activated', $disableServices)){return;}
                $wlpe->ActivateUser();
                // pixelchutes 1:57 AM 9/19/2007
                // Here we check for an error, then reload the activation template if necessary
                // Do NOT reload if wlpe->Report indicates success
                 // Added strip_tags() around string which means an error is not thrown regarding a modifier from closing
                // html tag e.g. if $wlpe_lang[104] contains "</div>" this will fail as "/d" treated as modifier
                if ( isset( $wlpe->Report ) && !preg_match( "/".strip_tags($wlpe_lang[104])."/i", $wlpe->Report ) )
                {
                    return $displayActivateTpl;
                }                
                return $displayLoginFormTpl;
                break;
			
			default :
				
				if ($modx->getLoginUserID())
				{
					return $displaySuccessTpl;
				}
				else
				{
					$wlpe->AutoLogin();
					return $displayLoginFormTpl;
				}

		}// [END] Switch : $service for simple.
	}
	
	else if ($type == 'taconite')
	{
		switch ($service) 
		{

			case 'login' :
				$wlpe->Login($type, $liHomeId);

				if (isset($wlpe->Report)) 
				{
					return $wlpe->Report;
				}
				return;
				break;

			case 'logout' :
				$wlpe->Logout($type, $loHomeId);				
				return;
				break;

			case 'register' :
				if (in_array('register', $disableServices)){return;}
				$wlpe->Register($regType, $groups, $regRequired, $notify, $notifyTpl, $notifySubject);
				return $wlpe->Report;
				break;
			
			case 'resetpassword' :
				if (in_array('resetpassword', $disableServices)){return;}
				$wlpe->ResetPassword();
				return $wlpe->Report;
				break;
				
			case 'activated':
				if (in_array('activated', $disableServices)){return;}
				$wlpe->ActivateUser();
				return $wlpe->Report;
				break;
					
			default :
				if ($modx->getLoginUserID())
				{
					return;
				}
				else
				{
					$wlpe->AutoLogin();
				}
		}// [END] Switch : $service for taconite.
	}
	
	else
	{
		return;
	}
?>