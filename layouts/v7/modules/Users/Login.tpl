{*+**********************************************************************************
* The contents of this file are subject to the vtiger CRM Public License Version 1.1
* ("License"); You may not use this file except in compliance with the License
* The Original Code is: vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
************************************************************************************}
{* modules/Users/views/Login.php *}

{strip}
	<style>
		body {
			background: url(layouts/v7/resources/Images/login-background.jpg);
			background-position: center;
			background-size: cover;
			width: 100%;
			background-repeat: no-repeat;
		}
		.loginDiv {
			max-width: 380px;
			margin: 100px auto;
			padding: 20px;
			border-radius: 4px;
			box-shadow: 0 0 10px gray;
			background-color: #FFFFFF;
			text-align: center;
		}
		.group {
			margin: 20px 0;
			text-align: left;
		}
		input {
			width: 100%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 4px;
			font-size: 16px;
		}
		.button {
			width: 100%;
			padding: 10px;
			background-color: #35aa47;
			color: white;
			border: none;
			cursor: pointer;
			font-size: 16px;
			margin-top: 10px;
		}
		.button:hover {
			background-color: #2b8a3e;
		}
		.forgot-password {
			margin-top: 10px;
			text-align: center;
		}
		.forgot-password a {
			color: #007bff;
			text-decoration: none;
			font-size: 14px;
			cursor: pointer;
		}
		.forgot-password a:hover {
			text-decoration: underline;
		}
		.hidden {
			display: none;
		}
	</style>

	<div class="loginDiv">
		<img class="img-responsive user-logo" src="layouts/v7/resources/Images/vtiger.png">

		<!-- Error Message Handling -->
		<div>
			<span class="{if !$ERROR}hide{/if} failureMessage" id="validationMessage">{$MESSAGE}</span>
			<span class="{if !$MAIL_STATUS}hide{/if} successMessage">{$MESSAGE}</span>
		</div>

		<!-- Login Form -->
		<div id="loginFormDiv">
			<form method="POST" action="index.php">
				<input type="hidden" name="module" value="Users"/>
				<input type="hidden" name="action" value="Login"/>
				<div class="group">
					<input id="username" type="text" name="username" placeholder="Username" required>
				</div>
				<div class="group">
					<input id="password" type="password" name="password" placeholder="Password" required>
				</div>
				<button type="submit" class="button">Sign in</button>

				<!-- Forgot Password Link -->
				<div class="forgot-password">
					<a id="forgotPasswordLink">Forgot password?</a>
				</div>
			</form>
		</div>

		<!-- Forgot Password Form (Hidden Initially) -->
		<div id="forgotPasswordDiv" class="hidden">
			<form action="index.php" method="POST">
				<input type="hidden" name="module" value="Users"/>
				<input type="hidden" name="view" value="ForgotPassword"/>
				<div class="group">
					<input id="fusername" type="text" name="username" placeholder="Username" required>
				</div>
				<div class="group">
					<input id="email" type="email" name="emailId" placeholder="Email" required>
				</div>
				<button type="submit" class="button">Submit</button>
				<div class="forgot-password">
					<a id="backToLogin">Back to login</a>
				</div>
			</form>
		</div>
	</div>

	<script>
		jQuery(document).ready(function () {
			var loginFormDiv = jQuery('#loginFormDiv');
			var forgotPasswordDiv = jQuery('#forgotPasswordDiv');
			var validationMessage = jQuery('#validationMessage');

			// Show Forgot Password Form
			jQuery('#forgotPasswordLink').click(function () {
				loginFormDiv.addClass('hidden');
				forgotPasswordDiv.removeClass('hidden');
				validationMessage.addClass('hidden');
			});

			// Back to Login
			jQuery('#backToLogin').click(function () {
				forgotPasswordDiv.addClass('hidden');
				loginFormDiv.removeClass('hidden');
				validationMessage.addClass('hidden');
			});
		});
	</script>
{/strip}
