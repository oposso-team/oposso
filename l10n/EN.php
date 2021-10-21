<?php

$LOCAL["global"]["signin_or_register"][0] = "Sign in";
$LOCAL["global"]["signin_or_register"][1] = "or";
$LOCAL["global"]["signin_or_register"][2] = "Register";
$LOCAL["global"]["logout"] = "Logout";
$LOCAL["global"]["dialog"]["cancel"] = "Cancel";
$LOCAL["global"]["navigation"]["top"]["home"] = "Home";
$LOCAL["global"]["navigation"]["top"]["subscription"] = "Subscription";
$LOCAL["global"]["navigation"]["top"]["account"] = "Account";
$LOCAL["global"]["navigation"]["top"]["notification"] = "Notification";
$LOCAL["global"]["navigation"]["footer"]["contact"] = "Contact";
$LOCAL["global"]["navigation"]["footer"]["how_to"] = "How To";
$LOCAL["global"]["navigation"]["footer"]["imprint"] = "Imprint";
$LOCAL["global"]["navigation"]["footer"]["privacy_policy"] = "Data Protection Regulations";
$LOCAL["global"]["navigation"]["footer"]["terms_conditions"] = "General Terms and Conditions";

$LOCAL["home"]["intro"]["head"] = "Welcome!";
$LOCAL["home"]["intro"]["text"] = "This is the SAMBA+ subscription portal, where you manage your access to
SAMBA+ software subscriptions purchased at the SAMBA+ shop. <br/> <b>You have to create an account here independently from the shop account!</b>";
$LOCAL["home"]["signin"]["title"] = "Sign in";
$LOCAL["home"]["signin"]["submit"] = "Sign in";
$LOCAL["home"]["signin"]["email"] = "Email";
$LOCAL["home"]["signin"]["password"] = "Password";
$LOCAL["home"]["signin"]["link_forgot"] = "Forgot your password?";
$LOCAL["home"]["register"]["title"] = "Register";
$LOCAL["home"]["register"]["first_name"] = "First name";
$LOCAL["home"]["register"]["last_name"] = "Last name";
$LOCAL["home"]["register"]["organization"] = "Organization";
$LOCAL["home"]["register"]["email"] = "Email";
$LOCAL["home"]["register"]["password"] = "Password";
$LOCAL["home"]["register"]["password_confirm"] = "Password (confirm)";
$LOCAL["home"]["register"]["submit"] = "Submit";
$LOCAL["home"]["register"]["captcha"] = "To continue, please type the characters below";
$LOCAL["home"]["register"]["text"][0] = "By signing in I agree that I have read and accepted <br/>the";
$LOCAL["home"]["register"]["text"][1] = "User Agreement";
$LOCAL["home"]["register"]["text"][2] = "and";
$LOCAL["home"]["register"]["text"][3] = "Privacy Policy.";
$LOCAL["home"]["sendpass"]["title"] = "Forgot your password";
$LOCAL["home"]["sendpass"]["text"] = "Enter your email address and we will send you a new password.";
$LOCAL["home"]["sendpass"]["email"] = "Email";
$LOCAL["home"]["sendpass"]["submit"] = "Submit";
$LOCAL["home"]["text"] = "<p><br/><strong>Disclaimer</strong></p><p>The software offered for download by this service is Open Source and Free Software licensed under the GNU General Public License (GPL) version 2, 3 or higher. It is provided as is, without any warranty under the conditions of the GPL. For details see the homepages of the GNU General Public License and the Free Software Foundation.</p>";


$LOCAL["imprint"]["head"] = "Imprint";
$LOCAL["imprint"]["text"] = "
	<strong>Organization:&nbsp;</strong>SerNet Service Network GmbH<br /> 
	<strong>Address:&nbsp;</strong>Bahnhofsallee 1b, 37081 G&ouml;ttingen<br /> 
	<strong>Email:&nbsp;</strong>kontakt&nbsp;@&nbsp;sernet.de</p>
	<strong>Commercial Register:&nbsp;</strong>HR B 2816, Amtsgericht G&ouml;ttingen<br />
	<br />
	<strong>USt.ID:</strong>&nbsp;DE186981087</p><br />
	<br />
	<strong>CEO:</strong>&nbsp;Dr. Johannes Loxen</a><br />
	<strong>CFO</strong>: Reinhild Jung<br />
	<strong>CTO</strong>: Krischan Jodies<br />
	<br />
	<strong>Phone:&nbsp;</strong>+49 551 370000-0<br />
	<strong>Fax:&nbsp;</strong>+49 551 370000-9<br />
	<br />
	<strong>SerNet</strong>,&nbsp;<strong>verinice</strong>&nbsp;and&nbsp;<strong>SAMBA</strong><br />are registered trademarks of SerNet GmbH.";

$LOCAL["contact"]["head"] = "Contact";
$LOCAL["contact"]["text"] = "For any questions, issues or critical remarks regarding the Open Source software packages, manuals and other digital contents distributed via this subscription portal please contact the 	SerNet GmbH - home of the original SAMBA+ packages: <br />
	<p><strong>SerNet Service Network GmbH</strong><br /> 
	Bahnhofsallee 1b<br /> 
	37081 G&ouml;ttingen<br />
	Germany<br /></p> 
	You can send us an email to shop&nbsp;@&nbsp;samba.plus<br />
	<br />
	Or just give us a  call: +49 551 37 0000 0.<br />	
	<br />
	Visit www.sernet.de for further information.";


$LOCAL["howto"]["head"] = "How To";
$LOCAL["howto"]["text"] = "
	<p>Please add your subscriptions first and set either one global password or one password per subscription. Thereafter you can use your key and password to log in to the SerNet download server and download files from the protected areas.</p>
	<h2>Samba Packages</h2>
	<p>The following form of URLs can be used to automatically retrieve packages from our repositories with package managers such as apt, yum or zypper. Direct download links to the packages are also available. The packages are signed with SerNet's gpg build key. See <a href='#buildkey'>below</a> for details.</p>
	<p>The repository files can be copied to the following locations depending on the installer used:</p>
	<table class='table table-condensed'>
		<tbody><tr>
				<th>installer</th>
				<th>path for repo-file</th>
				<!-- th>add-repo action</th -->
			</tr>
			<tr>
				<td>apt</td>
				<td>/etc/apt/sources.list.d</td>
				<!-- td></td -->
			</tr>
			<tr>
				<td>yum</td>
				<td>/etc/yum.repos.d</td>
				<!-- td>yum-config-manager \-\- add-repo URL_TO_REPO_FILE</td -->
			</tr>
			<tr>
				<td>zypper</td>
				<td>/etc/zypp/repos.d</td>
				<!-- td>zypper addrepo URL_TO_REPO_FILE</td -->
			</tr>
		</tbody></table>
	<p></p>
	<p>Note that the repository files are templates: In the URLs inside the repository files, you should replace SUBSCRIPTION:PASSWORD with the corresponding subscription key and password.</p>
	<table class='list ui-widget ui-widget-content'>
		<thead>
			<tr class='ui-widget-header'>
				<th>Distribution</th>
				<th>Version</th>
				<th>Samba 4.3 repository</th>
				<th>Samba 4.3 download</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Debian</td>
				<td>jessie (8)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/debian/dists/jessie/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,https)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/debian' target='_blank'>samba/4.3/debian</a></td>
			</tr>
			<tr>
				<td>Debian</td>
				<td>wheezy (7)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/debian/dists/wheezy/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,https)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/debian' target='_blank'>samba/4.3/debian</a></td>
			</tr>
			<tr>
				<td>Debian</td>
				<td>squeeze (6)</td>
				<td><a href='http://download.sernet.de/subscriptions/samba/4.3/debian/dists/squeeze/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,http)</td>
				<td><a href='http://download.sernet.de/subscriptions/samba/4.3/debian' target='_blank'>samba/4.3/debian</a></td>
			</tr>
			<tr>
				<td>Ubuntu</td>
				<td>trusty (14.04)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu/dists/trusty/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,https)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu' target='_blank'>samba/4.3/ubuntu</a></td>
			</tr>
			<tr>
				<td>Ubuntu</td>
				<td>precise (12.04)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu/dists/precise/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,https)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu' target='_blank'>samba/4.3/ubuntu</a></td>
			</tr>
			<tr>
				<td>Ubuntu</td>
				<td>lucid (10.04)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu/dists/lucid/sernet-samba-4.3.list' target='_blank'>sernet-samba-4.3.list</a> (apt,https)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/ubuntu' target='_blank'>samba/4.3/ubuntu</a></td>
			</tr>
			<tr>
				<td>RHEL</td>
				<td>7</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/rhel/7/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (yum)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/rhel/7' target='_blank'>samba/4.3/rhel/7</a></td>
			</tr>
			<tr>
				<td>RHEL</td>
				<td>6</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/rhel/6/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (yum)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/rhel/6' target='_blank'>samba/4.3/rhel/6</a></td>
			</tr>
			<tr>
				<td>CentOS</td>
				<td>7</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/centos/7/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (yum)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/centos/7' target='_blank'>samba/4.3/centos/7</a></td>
			</tr>
			<tr>
				<td>CentOS</td>
				<td>6</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/centos/6/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (yum)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/centos/6' target='_blank'>samba/4.3/centos/6</a></td>
			</tr>
			<tr>
				<td>SLES</td>
				<td>12</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/sles/12/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/sles/12' target='_blank'>samba/4.3/sles/12</a></td>
			</tr>
			<tr>
				<td>SLES</td>
				<td>11</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/sles/11/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/sles/11' target='_blank'>samba/4.3/sles/11</a></td>
			</tr>
			<tr>
				<td>openSUSE</td>
				<td>13.2</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/13.2/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/13.2' target='_blank'>samba/4.3/suse/13.2</a></td>
			</tr>
			<tr>
				<td>openSUSE</td>
				<td>12.3</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.3/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.3' target='_blank'>samba/4.3/suse/12.3</a></td>
			</tr>
			<tr>
				<td>openSUSE</td>
				<td>12.2</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.2/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.2' target='_blank'>samba/4.3/suse/12.2</a></td>
			</tr>
			<tr>
				<td>openSUSE</td>
				<td>12.1</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.1/sernet-samba-4.3.repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/12.1' target='_blank'>samba/4.3/suse/12.1</a></td>
			</tr>
			<tr>
				<td>openSUSE</td>
				<td>11.1</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/11.1/sernet-samba-4..repo' target='_blank'>sernet-samba-4.3.repo</a> (zypper)</td>
				<td><a href='https://download.sernet.de/subscriptions/samba/4.3/suse/11.1' target='_blank'>samba/4.3/suse/11.1</a></td>
			</tr>
		</tbody>
	</table>
	<br/>
	<h2>The SerNet build key</h2>
	<p>The packages are signed with SerNet's gpg build key to guarantee authenticity. There are several ways to install the build key on system:</p>
	<ul>
		<li>
			<p>
				<b>Install a debian package:</b>
				<br>wget <a href='https://download.sernet.de/pub/sernet-samba-keyring_1.5_all.deb'>https://download.sernet.de/pub/sernet-samba-keyring_1.5_all.deb</a>
				<br>dpkg -i sernet-samba-keyring_1.5_all.deb
			</p>
		</li>
		<li>
			<p>
				<b>Install a RPM package:</b>
				<br>wget <a href='https://download.sernet.de/pub/sernet-build-key-1.1-5.noarch.rpm'>https://download.sernet.de/pub/sernet-build-key-1.1-5.noarch.rpm</a>
				<br>rpm -i sernet-build-key-1.1-5.noarch.rpm
			</p>
		</li>
		<li>
			<p>
				<b>Import the key manually:</b>
				<br>gpg --keyserver wwwkeys.pgp.net --recv-keys F4428B1A;
				<br>gpg --export --armor F4428B1A | apt-key add -
			</p>
		</li>
	</ul>
	<p>After importing the key, please make sure that 'gpg --fingerprint F4428B1A' shows the following fingerprint:</p>
	<p><b>7975 0C31 87AF 92DD AC46 086F D992 1B1C F442 8B1A</b></p>
	<h2>Disclaimer</h2>
	<p>The software offered for download by this service is Open Source and Free Software licensed under the GNU General Public License (GPL) version 2, 3 or higher. It is provided as is, without any warranty under the conditions of the GPL. For details see the homepages of the <a href='http://www.gnu.org/licenses/gpl.html'>GNU General Public License</a> and the <a href='http://www.fsf.org/'>Free Software Foundation</a>.</p>
";

$LOCAL["subscription"]["button"]["add_subscription"] = "Add new subscription";
$LOCAL["subscription"]["button"]["save_description"] = "Save Description";
$LOCAL["subscription"]["button"]["set_password"] = "Set Password";
$LOCAL["subscription"]["button"]["set_global_password"] = "Set global password";
$LOCAL["subscription"]["button"]["extend_subscription"] = "Extend selected subscriptions";
$LOCAL["subscription"]["head"]["current_subscription"] = "Current subscriptions";
$LOCAL["subscription"]["head"]["expired_subscription"] = "Expired subscriptions";
$LOCAL["subscription"]["table_head"]["id"] = "ID";
$LOCAL["subscription"]["table_head"]["description"] = "Description";
$LOCAL["subscription"]["table_head"]["key"] = "Username / Key";
$LOCAL["subscription"]["table_head"]["path"] = "Path";
$LOCAL["subscription"]["table_head"]["create_date"] = "Create Date";
$LOCAL["subscription"]["table_head"]["expire_date"] = "Expire Date";
$LOCAL["subscription"]["dialog"]["key"]["title"] = "Register subscription keys";
$LOCAL["subscription"]["dialog"]["key"]["label"] = "Subscription keys (one per line, max 500)";
$LOCAL["subscription"]["dialog"]["key"]["submit"] = "Register keys";
$LOCAL["subscription"]["dialog"]["extend"]["title"] = "Extend subscription duration";
$LOCAL["subscription"]["dialog"]["extend"]["label_selected"] = "Selected subscriptions";
$LOCAL["subscription"]["dialog"]["extend"]["label_keys"] = "Insert the same number of keys (one per line) as selected";
$LOCAL["subscription"]["dialog"]["extend"]["label_termsconditions"] = "I accept the terms and conditions for <a href='https://samba.plus/tcsambaplus.pdf' target='_blank'>SAMBA+ <span class='ui-icon ui-icon-extlink'></span></a><br/>(German: Ich akzeptiere die AGB f&uuml;r <a href='https://samba.plus/agbsambaplus.pdf' target='_blank'>SAMBA+ <span class='ui-icon ui-icon-extlink'></span></a>)";
$LOCAL["subscription"]["dialog"]["extend"]["selected_info"] = "subscriptions selected.";
$LOCAL["subscription"]["dialog"]["extend"]["submit"] = "Extend subscriptions";
$LOCAL["subscription"]["dialog"]["setpass"]["title"] = "Set password for subscription";
$LOCAL["subscription"]["dialog"]["setpass"]["label_pass"] = "Password";
$LOCAL["subscription"]["dialog"]["setpass"]["label_pass_confirm"] = "Password (confirm)";
$LOCAL["subscription"]["dialog"]["setpass"]["submit"] = "Set";
$LOCAL["subscription"]["info"]["setpass"] = "You have to set a password to use this subscription";

$LOCAL["account"]["head"] = "My account";
$LOCAL["account"]["msg"]["confirmation"] = "<h3>Preliminary Login!</h3>Access to other portal functions needs account validation - please check your email accounts.";
$LOCAL["account"]["firstname"] = "First name";
$LOCAL["account"]["lastname"] = "Last name";
$LOCAL["account"]["organization"] = "Organization";
$LOCAL["account"]["email"] = "Email";
$LOCAL["account"]["password"] = "Password";
$LOCAL["account"]["password_confirm"] = "Password (confirm)";
$LOCAL["account"]["send"] = "Request confirmation email again";
$LOCAL["account"]["save"] = "Save";

$LOCAL["notification"]["head"] = "Notifications";
$LOCAL["notification"]["twoMonth"] = "notify me via email <b>2 month</b> before a subscription ends.";
$LOCAL["notification"]["twoWeeks"] = "notify me via email <b>2 weeks</b> before a subscription ends.";
$LOCAL["notification"]["save"] = "Save";

$LOCAL["email"]["confirm_registration"]["subject"] = "Confirm your email address";
$LOCAL["email"]["confirm_registration"]["body"] = "Dear ###FIRSTNAME### ###LASTNAME###,\n\nFollow the link below to confirm your account:\n###URL###\n\nWith kind regards Your SerNet Samba Team";
$LOCAL["email"]["new_password"]["subject"] = "New Password";
$LOCAL["email"]["new_password"]["body"] = "Dear ###FIRSTNAME### ###LASTNAME###,\n\nYou have requested a new password:\n\n###PASS###\n\nIf you didn't, please ignore this email.\n\nWith kind regards Your SerNet Samba Team";
$LOCAL["email"]["notification"]["subject"] = "Expiration notification";
$LOCAL["email"]["notification"]["twoMonth"] = "Dear ###FIRSTNAME### ###LASTNAME###,\n\nSome subscriptions expire in less than two months relate to the following:\n\n###EXPIRE###\n\nWith kind regards Your SerNet Samba Team";
$LOCAL["email"]["notification"]["twoWeeks"] = "Dear ###FIRSTNAME### ###LASTNAME###,\n\nSome subscriptions expire in less than two weeks relate to the following:\n\n###EXPIRE###\n\nWith kind regards Your SerNet Samba Team";

$LOCAL["msg"]["error"]["unexpected_error"] = "An unexpected error has occurred.";
$LOCAL["msg"]["error"]["access_denied"] = "Please sign in first!";
$LOCAL["msg"]["error"]["access_denied_confirmed"] = "Please confirm your account first!";
$LOCAL["msg"]["error"]["invalid_email"] = "Enter valid Email";
$LOCAL["msg"]["error"]["email_already_exists"] = "Email already exists";
$LOCAL["msg"]["error"]["empty_lastname"] = "Enter Last name";
$LOCAL["msg"]["error"]["empty_password"] = "Enter Password";
$LOCAL["msg"]["error"]["email_auth"] = "Corresponding User not found.";
$LOCAL["msg"]["error"]["invalid_password_confirm"] = "Enter Password (confirm) same as Password";
$LOCAL["msg"]["error"]["invalid_password"] = "Allowed characters: A-Z, a-z, 0-9, -._~";
$LOCAL["msg"]["error"]["empty_subscription_list"] = "Please select some subscriptions first";
$LOCAL["msg"]["error"]["empty_termsconditions"] = "Please confirm the terms and conditions";
$LOCAL["msg"]["error"]["user_register"] = "<b>User '###EMAIL###' could not be created:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["user_update"] = "<b>User '###EMAIL###' could not be updated:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["notification_set"] = "<b>Notification settings could not be set:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["new_password_but_not_email"] = "<b>Successfully set a new passwort but could not sent it via email:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["password_not_set"] = "<b>Could not create a new passwort:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["incorrect_email_password"] = "Incorrect email address or password";
$LOCAL["msg"]["error"]["invalid_key"] = "Please enter valid keys";
$LOCAL["msg"]["error"]["key_not_found"] = "Key '###KEY###' not found";
$LOCAL["msg"]["error"]["add_key"] = "Key '###KEY###' is not available";
$LOCAL["msg"]["error"]["keys_number_mismatch"] = "Number of selected subscriptions and keys do not match";
$LOCAL["msg"]["error"]["no_subscription_id"] = "Subscription ID not found";
$LOCAL["msg"]["error"]["update_description"] = "<b>Description for ID ###ID### could not be updated:</b><br/>###ERRORMSG###";
$LOCAL["msg"]["error"]["session_expired"] = "Your session has expired! Please reload the page and try again";
$LOCAL["msg"]["error"]["captcha_empty"] = "Captcha must not be empty";
$LOCAL["msg"]["error"]["captcha_mismatch"] = "Sorry, the captcha field does not match the image, please try again";
$LOCAL["msg"]["success"]["user_register"] = "Email sent to '###EMAIL###'.<br/>Please check your mailbox to complete the registration.";
$LOCAL["msg"]["success"]["new_password"] = "A new password has been sent to your email address.";
$LOCAL["msg"]["success"]["user_updated"] = "User '###EMAIL###' successfully updated.";
$LOCAL["msg"]["success"]["notification_set"] = "Notification settings successfully updated.";
$LOCAL["msg"]["success"]["email_auth"] = "Your account has been successfully confirmed.";
$LOCAL["msg"]["success"]["add_key"] = "###KEYS### keys successfully added.";
$LOCAL["msg"]["success"]["extend_duration"] = "###KEYS### subscriptions successfully extended.";
$LOCAL["msg"]["success"]["subscription_password_set"] = "Password successfully set for subscription ####ID###.";
$LOCAL["msg"]["success"]["update_description"] = "Description successfully updated.";
?>
