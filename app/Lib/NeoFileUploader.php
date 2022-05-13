<?php

/*
NEO FILE UPLOADER 
DEVELOPED BY CARLOS F. MEDINA, PABLO ABELLA & JULIA VALLINA
*/

class NeoFileUploader{
	

	var $allowed_maximum_width = 8000;
	var $allowed_maximum_height = 8000;
	var $allowed_maximum_size = 10;
	
	var $allowed_image_extensions = array('png' => 'image/png',
									  'jpg' => 'image/jpeg',
									  'gif' => 'image/gif');
	
	var $allowed_file_extensions = array('doc' => 'application/msword',
									  'pdf' => 'application/pdf', 
									  'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
									  'zip' =>'application/zip');
	
	
	var $error_msg = "Ha habido un error subiendo los archivos.";
	
	
	
	function upload_file($file, $path, $image_name, $is_image = false){
	
		$extension = $this->validate_file($file, $is_image);
		//Check if file is correct
		if ($file["error"] != 0 || !$extension){
			throw new Exception($this->error_msg);
		}
		
		//create rooth
		$image_name .= ".".$extension;
		$image_complete_path = $path."/".$image_name;
		
		//upload file
		if(move_uploaded_file($file["tmp_name"], $image_complete_path)){
			chmod($image_complete_path, 0777);
		}else{
			throw new Exception($this->error_msg);
		}
		
		return $image_name;
	}

	function validate_file($file, $is_image){
		$extensions_array = $this->allowed_file_extensions;
		
		if($is_image){
			$extensions_array = $this->allowed_image_extensions;
		}

		//obtain image parameters
		list($width, $height, $type, $attr) = getimagesize($file["tmp_name"]);
		
		//obatain extensions
		$extension = array_search($file['type'], $extensions_array);
				
		//validations
		if (!$extension || 
			($file["size"]/(1024*1024) > $this->allowed_maximum_size) || 
			$width > $this->allowed_maximum_width || 
			$height >$this->allowed_maximum_height){
			
			return false;
		}

		return $extension;
	}	
}


?>