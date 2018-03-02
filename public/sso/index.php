<?php
/**
 *  SAML Handler
 */
ini_set('display_errors', 0);

$include = count(get_required_files())-1;

session_start();
require_once dirname(__FILE__).'/_toolkit_loader.php';
require_once dirname(__FILE__).'/settings.php';

$auth = new OneLogin_Saml2_Auth($sso_settings);

if(isset($_REQUEST['sso'])){
    // Request login to SSO

    $redirect = isset($_REQUEST['redirect'])? $_REQUEST['redirect']:$_SERVER['HTTP_REFERER'];
    $auth->login($redirect);

	die;
	
}else if(isset($_REQUEST['slo'])){
    // Request logout to SSO

    $redirect = isset($_REQUEST['redirect'])? $_REQUEST['redirect']:$baseUrl;
    $_SESSION['ssoUserdata'] = Null;
    $paramters = array();
    $nameId = null;
    $sessionIndex = null;
    if(isset($_SESSION['ssoNameId'])){
        $nameId = $_SESSION['ssoNameId'];
    }
    if(isset($_SESSION['ssoSessionIndex'])){
        $sessionIndex = $_SESSION['ssoSessionIndex'];
    }

    $auth->logout($redirect, $paramters, $nameId, $sessionIndex);

	die;

}else if(isset($_REQUEST['acs'])){
    // Login result from SSO

    $auth->processResponse();
    $errors = $auth->getErrors();
    if(!empty($errors)){
        print_r('<p>'.implode(', ', $errors).'</p>');
        exit();
    }
    if(!$auth->isAuthenticated()){
        echo "<p>Not authenticated</p>";
        exit();
    }

    $_SESSION['ssoUserdata'] = $auth->getAttributes();
    $_SESSION['ssoNameId'] = $auth->getNameId();
    $_SESSION['ssoSessionIndex'] = $auth->getSessionIndex();        

    // Sucessfully logged in
    // Begin of user codes ------------------

    foreach($_SESSION['ssoUserdata'] as $k=>$v){
        $_SESSION['_login_info'][$k] = $v[0];
    }
    $_SESSION['_logged_in'] = $_SESSION['_login_info']['uid'];

    // End of user codes --------------------

    if(isset($_POST['RelayState'])&& OneLogin_Saml2_Utils::getSelfURL()!= $_POST['RelayState']){
        $auth->redirectTo($_POST['RelayState']);
    }
	
	die;

}else if(isset($_REQUEST['sls'])){
    // Logout result from SSO

    $auth->processSLO();
    $errors = $auth->getErrors();
    if(empty($errors)){

        // Sucessfully logged out
        // Begin of user codes ------------------

        $_SESSION['_login_info'] = Null;
        $_SESSION['_logged_in'] = Null;

        // End of user codes --------------------

        $auth->redirectTo($_POST['RelayState']);
    }else{
        print_r('<p>'.implode(', ', $errors).'</p>');
    }
	
	die;
	
}
// If directory access to this file via browser, check entityId and replace with suggestion entityId.
if($include==0){
	if($_SERVER[REQUEST_URI]!=$_SERVER[SCRIPT_NAME]){
		$URI = $_SERVER[REQUEST_URI];
	}else{
		$URI = rtrim($_SERVER[REQUEST_URI], 'index.php');
	}

	$suggestEntityId = rtrim($_SERVER[HTTP_HOST].$URI, '/');
	$suggestEntityId = rtrim($suggestEntityId, 'sso');
	$suggestEntityId = rtrim($suggestEntityId, '/');
	
	if($suggestEntityId!=$sso_settings['sp']['entityId']){
		$f = file_get_contents(dirname(__FILE__).'/settings.php');
		$fs = explode("\n", $f);
		for($l=0; $l<count($fs); $l++){
			$f = trim($fs[$l]);
			if(substr($f, 0, 9)=="\$entityId"){
				$fs[$l] = preg_replace('/entityId.*/', "entityId = '$suggestEntityId';", $fs[$l]);
			}
		}
		file_put_contents(dirname(__FILE__).'/settings.php', implode("\n", $fs));
		$sso_settings['sp']['entityId'] = $suggestEntityId;
	}
}
?>
<center><h3>You are directly accessing to Single Sign-On script</h3></center><br/>
<style>
th {
	text-align: left;
}
ul {
	margin: 0px;
}
li {
	padding: 0px;
}
</style>
- Service Provider <a href="<?php echo $REQUEST_SCHEME.'://'.$sso_settings['sp']['entityId']."/sso/metadata.php"; ?>">SAML Metadata (<?php echo $REQUEST_SCHEME.'://'.$sso_settings['sp']['entityId']."/sso/metadata.php"; ?>)</a><br/>
  <br/>
- Use this value to add to your IdP:<br/>
  <div style='margin-left: 40px;'>
	<table style="padding-top: 5px;">
	<thead><th width=100>Name</th><th>Value</th></thead>
	<tbody>
		<tr><td>SP Metadata URL</td><td><?php echo $REQUEST_SCHEME.'://'.$sso_settings['sp']['entityId']."/sso/metadata.php"; ?></td></tr>
		<tr><td>SP Entity ID</td><td><?php echo $sso_settings['sp']['entityId']; ?></td></tr>
		<tr><td>AssertionConsumerService</td><td><?php echo $sso_settings['sp']['assertionConsumerService']['url']; ?></td></tr>
		<tr><td>SingleLogoutService</td><td><?php echo $sso_settings['sp']['singleLogoutService']['url']; ?></td></tr>
		<tr><td>NameIDFormat</td><td><?php echo $sso_settings['sp']['NameIDFormat']; ?></td></tr>
	</tbody>
	</table>
  </div>
  <br/>
<?php
if(isset($_SESSION['ssoUserdata'])){
    echo '- You are logged in -- You have the following attributes from $_SESSION[\'ssoUserdata\']:<br/>';
    echo "<div style='margin-left: 40px;'>";
    if(!empty($_SESSION['ssoUserdata'])){
        $attributes = $_SESSION['ssoUserdata'];
        echo '<table style="padding-top: 5px;"><thead><th>Name</th><th>Values</th></thead><tbody>';
        foreach($attributes as $attributeName => $attributeValues){
            echo '<tr><td>' . htmlentities($attributeName). '</td><td><ul>';
            foreach($attributeValues as $attributeValue){
                echo '<li>' . htmlentities($attributeValue). '</li>';
            }
            echo '</ul></td></tr>';
        }
        echo '</tbody></table>';
    }else{
        echo "<p>You don't have any attribute</p>";
    }
    echo "</div>";
}
?>
  <br/>
- <a href="?sso&redirect=">Login</a> <i>(Url: <?php echo $REQUEST_SCHEME.'://'.$sso_settings['sp']['entityId']."/sso/?sso&redirect=" ?>)</i><br/><br/>
- <a href="?slo&redirect=">Logout</a> <i>(Url: <?php echo $REQUEST_SCHEME.'://'.$sso_settings['sp']['entityId']."/sso/?slo&redirect=" ?>)</i><br/>
