<?php
require __DIR__ . '/vendor/autoload.php';
try {
	$certificate = new TotusPKI\Certificate('watm640917j45.cer', 'Claveprivada_FIEL_WATM640917J45_20190528_155346.key', '12345678a');

	$data = $certificate->isFiel();
	echo "<pre>";
	print_r($data);
} catch (\Exception $ex) {
	echo $ex->getMessage();
}
