<?php
namespace TotusPKI;

class EncriptedPrivateKey extends ElementoPKISimple {
	private $password;
	private $privateKey;

	public function __construct(string $key, string $password = null) {
		parent::__construct('ENCRYPTED PRIVATE KEY', $key);
		$this->password = $password;
		$this->loadPrivateKey();
	}

	public function getPrivateKey() {
		return $this->privateKey;
	}

	private function loadPrivateKey() {
		if ($this->data === null) {
			throw new TotusPKIException("Debes especificar el archivo key");
		}
		if ($this->password === null) {
			throw new TotusPKIException("Debes ingresar la contraseña de la llave privada");
		}

		$this->privateKey = openssl_pkey_get_private($this->data, $this->password);

		// Validar si el password es válido
		if ($this->privateKey === FALSE) {
			throw new TotusPKIException("Password incorrecto");
		}
	}

	public function checkPublicKey($publicKey) {
		//Validar si el certificado y la llave corresponden
		return openssl_x509_check_private_key($publicKey, $this->privateKey);
	}
}