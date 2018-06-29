<?php
	//
	// RMUTi SSO SP configuration, based on OneLogin's SAML PHP Toolkit (https://github.com/onelogin/php-saml)
	//
	
	//
	// = Service Provider entityId =
	//   The entityId must be a part of project url.
	//
	//   Format: $host_name/project_dir
	// 
	//   Example: www.example.com/my_project
	//
	//   Automatic replace when browse to http(s)://$host_name/project_dir/sso
	//
    $entityId = 'it.ea.rmuti.ac.th/wepp';
	
	// = IdP certificate fingerprint =
    $certFingerprint = 'e99bf5f777fa1ec6cf7232420207b7a97a8be8e1';

	$REQUEST_SCHEME = 'http';
	if(isset($_SERVER[REQUEST_SCHEME])){
		$REQUEST_SCHEME = $_SERVER[REQUEST_SCHEME];
	}elseif(isset($_SERVER[HTTPS])){
		if($_SERVER[HTTPS]=='on'){
			$REQUEST_SCHEME = 'https';
		}
	}else{
		if($_SERVER[SERVER_PORT]==443)
			$REQUEST_SCHEME = 'https';
		else
			$REQUEST_SCHEME = 'http';
	}
	
	// = Project base url =
	$baseUrl = $REQUEST_SCHEME.'://'.$entityId;
	
    $sso_settings = array (
	
		// Service Provider Data that we are deploying
        'sp' => array (
		
			// Identifier of the SP entity  (must be a URI)
            'entityId' => $entityId, 
			
			// Specifies info about where and how the <AuthnResponse> message MUST be
			// returned to the requester, in this case our SP.
            'assertionConsumerService' => array (
			
				// URL Location where the <Response> from the IdP will be returned
                'url' => $baseUrl.'/sso/index.php?acs',
				
				// SAML protocol binding to be used when returning the <Response>
				// message.  Onelogin Toolkit supports for this endpoint the
				// HTTP-Redirect binding only
				'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',
            ),
			
			// Specifies info about where and how the <Logout Response> message MUST be
			// returned to the requester, in this case our SP.
            'singleLogoutService' => array (
			
				// URL Location where the <Response> from the IdP will be returned
                'url' => $baseUrl.'/sso/index.php?sls',
				
				// SAML protocol binding to be used when returning the <Response>
				// message.  Onelogin Toolkit supports for this endpoint the
				// HTTP-Redirect binding only
				'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
			
			// Specifies constraints on the name identifier to be used to
			// represent the requested subject.
			// Take a look on lib/Saml2/Constants.php to see the NameIdFormat supported
			'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
			
			// Usually x509cert and privateKey of the SP are provided by files placed at
			// the certs folder. But we can also provide them with the following parameters
			'x509cert' => '',
			'privateKey' => '',
        ),
		
		// Identity Provider Data that we want connect with our SP
        'idp' => array (
		
			// Identifier of the IdP entity  (must be a URI)
            'entityId' => $entityId,
			
			// SSO endpoint info of the IdP. (Authentication Request protocol)
            'singleSignOnService' => array (
			
				// URL Target of the IdP where the SP will send the Authentication Request Message
                'url' => 'https://login.rmuti.ac.th/idp/saml2/SSOService.php',
				
				// SAML protocol binding to be used when returning the <Response>
				// message.  Onelogin Toolkit supports for this endpoint the
				// HTTP-POST binding only
				'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
			
			// SLO endpoint info of the IdP.
            'singleLogoutService' => array (
			
				// URL Location of the IdP where the SP will send the SLO Request
                'url' => 'https://login.rmuti.ac.th/idp/saml2/SingleLogoutService.php',
				
				// SAML protocol binding to be used when returning the <Response>
				// message.  Onelogin Toolkit supports for this endpoint the
				// HTTP-Redirect binding only
				'binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
            ),
			
			// Public x509 certificate of the IdP
			//'x509cert' => '',
		
			/*
			*  Instead of use the whole x509cert you can use a fingerprint
			*  (openssl x509 -noout -fingerprint -in "idp.crt" to generate it,
			*   or add for example the -sha256 , -sha384 or -sha512 parameter)
			*
			*  If a fingerprint is provided, then the certFingerprintAlgorithm is required in order to
			*  let the toolkit know which Algorithm was used. Possible values: sha1, sha256, sha384 or sha512
			*  'sha1' is the default value.
			*/
            'certFingerprint' => $certFingerprint,
			'certFingerprintAlgorithm' => 'sha1',
        ),
    );
