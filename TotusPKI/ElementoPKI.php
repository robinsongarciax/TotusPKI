<?php
namespace TotusPKI;

abstract class ElementoPKI implements IElementoPKI {

	protected $name;
	protected $data;

	public function __construct(string $name, string $data) {
		$this->name = $name;
		$data = @is_file($data) ? file_get_contents($data) : $data;
		
		$this->data = $data;
		if (substr($this->data, 0, 10) !== '-----BEGIN')
			$this->fromBinary();
	}

	private function fromBinary(){
		$this->data = "-----BEGIN $this->name-----" . PHP_EOL . chunk_split(base64_encode($this->data), 64, PHP_EOL) . "-----END $this->name-----" . PHP_EOL;
	}

	abstract public function __toString();
}