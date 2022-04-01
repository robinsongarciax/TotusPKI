<?php
namespace TotusPKI;

class PublicKey extends ElementoPKISimple {

	public function __construct(string $cer) {
		parent::__construct('CERTIFICATE', $cer);
	}

	public function getInfo() {
		return openssl_x509_parse(openssl_x509_read($this->data));
	}

	public function getPublicKey() {
		return openssl_get_publickey($this->data);
	}

	public function getCertificate() {
		return $this->data;
	}
}