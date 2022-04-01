<?php
namespace TotusPKI;

abstract class ElementoPKISimple extends ElementoPKI {

	public function __toString() {
		return $this->data;
	}
}