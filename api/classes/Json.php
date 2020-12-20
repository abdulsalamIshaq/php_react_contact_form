<?php
namespace Json;

class Json {
	public function encode(array $data = []) {
		echo json_encode($data);
	}
}