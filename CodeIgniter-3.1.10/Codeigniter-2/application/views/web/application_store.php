<!DOCTYPE html>
<html>
<head>
	<script src="<?php echo ASSETS_URL; ?>admin/js/jquery.js"></script>
    <script src="<?php echo ASSETS_URL; ?>admin/js/custom.js"></script>
    <script src="<?php echo ASSETS_URL; ?>admin/js/jquery.validate.min.js"></script>
    <script src="<?php echo ASSETS_URL; ?>admin/js/additional-methods.min.js"></script>
</head>
<script type="text/javascript">
	 (function() {
		 var app = {
		   launchApp: function() {
			var operating_system = getMobileOperatingSystem();
			//alert(operating_system);
			if(operating_system == "iOS")
			     window.location.replace("GiftCast://"); //if app installed
			else
				// window.location.replace("intent:#Intent;action=com.zaptechsolutions.giftcast;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end");
			window.location.replace("myapp://mytest/login");
			// window.location.replace("intent://#Intent;action=android.intent.action.SEND,package=com.zaptechsolutions.giftcast;end");
		     this.timer = setTimeout(this.openWebApp, 1000);
		   },

		   openWebApp: function() {
			var operating_system = getMobileOperatingSystem();
			//alert(operating_system);
			if(operating_system == "iOS")
				window.location.replace("https://itunes.apple.com/app/id1436986233?mt=8");
			else
				//window.location.replace("http://www.giftcastapp.com"); //if app  not installed then play store
				// window.location.replace("intent:#Intent;action=com.zaptechsolutions.giftcast;category=android.intent.category.DEFAULT;category=android.intent.category.BROWSABLE;S.msg_from_browser=Launched%20from%20Browser;end");
				window.location.replace("myapp://mytest/login");
		   }
		 };

		 app.launchApp();
		 //app. openWebApp();
		})();
		/**
		 * Determine the mobile operating system.
		 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
		 *
		 * @returns {String}
		 */
		function getMobileOperatingSystem() {
		  var userAgent = navigator.userAgent || navigator.vendor || window.opera;

		      // Windows Phone must come first because its UA also contains "Android"
		    if (/windows phone/i.test(userAgent)) {
		        return "Windows Phone";
		    }

		    if (/android/i.test(userAgent)) {
		        return "Android";
		    }

		    // iOS detection from: http://stackoverflow.com/a/9039885/177710
		    if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
		        return "iOS";
		    }

		    return "unknown";
		}
</script>
</html>