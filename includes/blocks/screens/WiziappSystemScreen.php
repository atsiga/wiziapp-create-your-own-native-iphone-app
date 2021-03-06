<?php
/**
* @package WiziappWordpressPlugin
* @subpackage Display
* @author comobix.com plugins@comobix.com
*
*/

class WiziappSystemScreen extends WiziappBaseScreen{
	protected $name = '';
	protected $type = 'system';

	public function run(){}

	public function runByAbout(){
		$this->name = 'about';
		$app_name = WiziappConfig::getInstance()->app_name;
		if (strlen($app_name) > 20) {
			$app_name = WiziappHelpers::makeShortString($app_name, 20);
		}

		$page = array(
			'title' => $app_name,
			// 'version' =>  __('version') . ' ' . WiziappConfig::getInstance()->version,
			'version' =>  '',
			'imageURL' => WiziappConfig::getInstance()->getAppIcon(),
			'aboutTitle' => __('About', 'wiziapp') . ' ' . $app_name,
			'aboutContent' => WiziappConfig::getInstance()->getAppDescription(),
			// 'actions' => $actions
			'actions' => array()
		);

		$screen = $this->prepare($page, $this->getTitle(), 'about');
		$screen['screen']['class'] = 'about_screen';
		$this->output($screen);
	}

	function runByRegister($message=''){
		$uah = new WiziappCmsUserAccountHandler();
		$_SESSION['wiziapp_message'] = $uah->registration();
		WiziappTemplateHandler::load(WIZI_DIR_PATH.'themes/iphone/register.php');
		exit();
	}
	function runByForgotPassword(){
		$uah = new WiziappCmsUserAccountHandler();
		$_SESSION['wiziapp_message'] = $uah->forgotPassword();
		WiziappTemplateHandler::load(WIZI_DIR_PATH.'themes/iphone/forgot_password.php');
		exit();
	}

	/**
	* Used to get to enable communication between our SystemControl services (Plugin Dashboard / Generator and so on)
	* and the cms admin ui.
	* By using cms -> iframe (systemControl) -> iframe(cms) we can avoid the security problems.
	*/
	public function runByFrame(){
		$handler = isset($_GET['report']) ? 'WIZIAPP_REPORT_HANDLER' : 'WIZIAPP_HANDLER';
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="application/xhtml+xml; charset=UTF-8" />
			<title>WiziApp</title>
			<script type="text/javascript">
				if (window.location.search.length > 1) {
					if (window.parent.parent.<?php echo $handler; ?>) {
						window.parent.parent.<?php echo $handler; ?>.handleRequest(window.location.search.substring(1));
					}
				}
			</script>
		</head>
		<body>
		</body>
	</html>
	<?php
		exit();
	}
}