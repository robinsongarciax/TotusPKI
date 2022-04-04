<?php
namespace TotusPKI;

class Certificate {

	private $publicKey;
	private $info;
	private $encriptedPrivateKey;
	private $privateKey;	

	/**
	 * @param string|stream $cer path to the cer file or the read data of the file
	 * @param string|stream $key path to the key file or the read data of the file
	 * @param string $password passphrase must be used to the specified key is encrypted
	 */
	public function __construct($cer, $key = null, string $password = null) {
		$this->publicKey = new PublicKey($cer);
		if (isset($key)) {
			$this->encriptedPrivateKey = new EncriptedPrivateKey($key, $password);
			if ($this->encriptedPrivateKey->checkPublicKey($this->getCertificate()) === FALSE) {
				throw new TotusPKIException("El certificado no corresponde con la llave privada");
			}
		}
		$this->info = $this->getCertificateInfo();
	}

	public function getCertificate() {
		return $this->publicKey->getCertificate();
	}

	public function getPublicKey() {
		return $this->publicKey->getPublicKey();
	}

	public function getPrivateKey() {

		$privateKey = $this->encriptedPrivateKey->getPrivateKey();
		//Validar si el certificado y la llave corresponden
		if ($this->encriptedPrivateKey->checkPublicKey($this->getCertificate()) === FALSE) {
			throw new TotusPKIException("El certificado no corresponde con la llave privada");
		}

		$this->privateKey = $privateKey;
		return $this->privateKey;
	}

	public function isFiel() {
		if (empty($this->info) ) {
			$this->getCertificateInfo();
		}

		return array_key_exists('C', $this->info['subject']);
	}

	public function getCertificateInfo() {
		return $this->publicKey->getInfo();
	}

	public function getSerialNumber() {

		$serialDec = implode(array_map('hexdec', str_split($this->info['serialNumberHex'], 2)));
		return implode(array_map('chr', str_split($serialDec, 2)));
	}

	public function verifySignature(string $data, string $signature, string $signatureAlg = null) {
		if ($signatureAlg == null)
			$signatureAlg = OPENSSL_ALGO_SHA256;

		return openssl_verify($data, $signature, $this->getPublicKey(), $signatureAlg);
	}

}