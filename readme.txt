=== Saksh WP SMTP ===
Contributors: susheelhbti
Donate link: http://aistore2030.com/
Tags: wp smtp,smtp,mail,email,phpmailer,mailer,wp mail,gmail,yahoo,mail smtp,  smtp bot, 
License: GPLv2
Requires at least: 2.7
Tested up to: 4.9.8
Stable tag: 1.1.1
Version : 3.0

Saksh WP SMTP can help us to integrate SMTP to your wordpress installation. So that you don't need to use mail() function.
 


Todo


1 send fund description




== Description ==

How to run bitcoin full node-

1. Download a bitcoin : https://bitcoincore.org/en/download/
2. Add Bitcoin Config File : 

	paytxfee=0.0005
	mintxfee=0.0000001
	txconfirmtarget=1
	Fallbackfee=0.00001
	server=1
	rpcuser=user
	rpcpassword=password
	maxconnections=0
	
	You change value as per your requiremnet


3. Run the following command :
 
	./bitcoind -regtest -rpcport=8332 -server=1 
	
	After this step you will get full node running.



How to configure wordpress plugins:

1. Open a Private Blockchain Settings page and set these fields according to config file.

2. If paramerter are correct then connection are established and show a totalblockcount otherwise connettion lost.



How to Mine coin:
	
	
	From the setting page you can download shell script which you need to run from the command line 
	
	
	


	
	



	
	
	


Now we added a form using this you can test your SMTP settings

 

###Key Features 

£ Set the From Name and Email Address that emails should be sent from.
£ Set Return Path of your Email or Keep it same as your email address.
£ Set SMTP Host and Port and Encryption type to be used for sending your emails.
£ Set to use Authentication or disable Authentication and add SMTP username and password.


###Key Usage :
You can integrate wordpress to your mandrill , sendgrid , getresponse, email-marketing247 SMTP Server, Amazon SES etc.


= Usage =

1. Download and extract   to `wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. "Dashboard"->"Settings"->"Saksh WP SMTP"
4. There are some examples on the settings page,  



== Installation ==

1. Download and extract   to `wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. "Dashboard"->"Settings"->"Saksh WP SMTP"
4. There are some examples on the settings page,  


== Changelog ==

= 1.1.0 =
 

* First release.

= 2.1.0 =
 

* Added Test SMTP Details Form.


