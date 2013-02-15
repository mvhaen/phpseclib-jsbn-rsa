
<?php

	$path = 'phpseclib';
	set_include_path(get_include_path() . PATH_SEPARATOR . $path);
	include_once('Crypt/RSA.php');

	/// use generate.php to generate a new key and paste it here.
	$privatekey="-----BEGIN RSA PRIVATE KEY----- 
MIICXAIBAAKBgQC3RCD1pNmr/SByydk23VPi3iqnkIIq0WCIB72j4XazNcUZAsohd4JBmBgc6L6o XeEyquoRBP0EPkrSwK9wW9qWa10vkqarUXDRYesej3prHV+6Zz+KTc6+VUB++XB3gskbF1J6+CCi w6O1hjQa5U7wNzkHTUc44/81JXvfuSM8UwIDAQABAoGAUYaLKvt0oZ/vKWFFsbRvtsKiMvyEC0wt JxZadGa+CSboUSH+jTi+xzNDtsiK2Bc7MPD7Qyr260ZSvsJcyRzzdanwewenUMLXAL1JOaZhxQ2+ tcbWDiX6aByL5lkGu4cxNGpEGoa34fo7bFMMzqpAjgMqTIocJWMxMIdRCkzwfdECQQDemsSLSST/ TD4gd/CB9m/NrdPWzRgyneHvKCT17g777ILzW3JsGrGqiZzWqMxF/ynN32XwM/FT7V00dAnngeGN AkEA0sKM0RQTvt7pKn8vHCZUNsbSMkgT52DbWrKFEUA660808BmflvpuwKXZZ+8vKN0F73e76eF1 95eUdtauy+XtXwJBAKl+tLrNpfMSLZfxW1rJtxWoDs3Wel9IIhlEuufbLOObkZYVAknYBYGxqI82 FdwSTtVoDalZE57w9HAVDtmM5p0CQBZoiQBR2ieZG8Fg9GlRyfJpAUBHWZZoPepOwMcsxRbvvPkq QEWVKuFgwNTEIYd+uHrViC09w4UnoKlh+gPD1pECQB78s8sG3V9Et+2DnwDOJ4prZXfOikV2Hlf/ BTwQp7L0iMErKSCV07Ek3/Pbjg+VU431wo8ZHvBOydFBfBkAS9A= 
----END RSA PRIVATE KEY-----";
	
	function publicKeyToHex($privatekey) {
		
		$rsa = new Crypt_RSA();
	
		$rsa->loadKey($privatekey);
		$raw = $rsa->getPublicKey(CRYPT_RSA_PUBLIC_FORMAT_RAW);
		return $raw['n']->toHex();			
	}
	
	function decrypt($privatekey, $encrypted) {
		$rsa = new Crypt_RSA();

		$encrypted=pack('H*', $encrypted);

		$rsa->loadKey($privatekey);
		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		return $rsa->decrypt($encrypted);		
	}
	
	if (isset($_GET['encrypted'])) {
		echo '<div class="alert alert-info span10">';
		echo "<h2>Received encrypted data</h2><p style=\"word-wrap: break-word\">".$_GET['encrypted']."</p>";
		echo "<h2>After decreption:</h2><p>".decrypt($privatekey, $_GET['encrypted'])."</p>";
		echo '</div>';
		return;
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8">
	<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/css/bootstrap-combined.min.css" rel="stylesheet">
	<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.0/js/bootstrap.min.js"></script>
	<script src="javascript/jsbn.js"></script>
	<script src="javascript/jsbn2.js"></script>
	<script src="javascript/prng4.js"></script>
	<script src="javascript/rng.js"></script>
	<script src="javascript/rsa.js"></script>
	<script src="javascript/rsa2.js"></script>
	<script>
	
	function encrypt() {
		var publickey = "<?=publicKeyToHex($privatekey)?>";
     	var rsakey = new RSAKey();
       	rsakey.setPublic(publickey, "10001");
		var enc = rsakey.encrypt($('#plaintext').val());
		
		$.get('index.php?encrypted='+enc, function(data) {
			$('#feedback').html(data);
		});				
		
		return;
	}
	
	</script>
	<title>RSA encryption/decryption demo</title>
</head>
<body>
	<div class="row-fluid">
		<div class="span10 offset1">
			<div class="page-header">
				<h1>RSA encryption/decryption <small>using <a href="http://www-cs-students.stanford.edu/~tjw/jsbn/">jsnb</a> and <a href="http://phpseclib.sourceforge.net">phpseclib</a></small></h1>
				<h2><small>Example by <a href="http://twitter.com/mvhaen">Michael Voorhaen</a></small></h2>
			</div>
		</div>
	</div>
	<div class="row-fluid">
		<div class="span4">
			<form class="form-horizontal" method="post">
			  <div class="control-group">
			    <label class="control-label" for="inputEmail">Plaintext</label>
			    <div class="controls">
			      <input type="text" name="plaintext" id="plaintext" placeholder="enter something">
			    </div>
			  </div>
			</form>
		</div>
		<div class="span4">
			<button type="button" class="btn btn-primary" onclick="encrypt()">Encrypt</button>
		</div>
		<br/>
	</div>
	<div class="row-fluid">
		<div id="feedback" class="span11 offset1">
			
		</div>		
	</div>
</body>
</html>
