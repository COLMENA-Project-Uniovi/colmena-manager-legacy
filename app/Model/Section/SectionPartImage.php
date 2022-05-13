<?php
class SectionPartImage extends AppModel{
	public $name = "SectionPartImage";

	//PATHS
	protected $content_path = "sections/section_part_images";

	//STANDARD RESOURCE NAMES
	protected $main_image_name = "principal";

	public $belongsTo = 'Section';

	public $validate = array(

	);

	public function add($post_entity){
		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error añadiendo la parte.");
		}

		// Upload media
		$status = parent::save_main_image($this->id, $post_entity[$this->name]['main_img']);

		//if error
		if($status !== true){
			throw new Exception ("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
		}

		return true;
	}

	public function edit($read_entity = null, $post_entity = null){
		$id = $read_entity[$this->name]['id'];
		$this->id = $id;
		$post_entity[$this->name]['modified'] = CakeTime::format(time(), "%Y-%m-%d %H:%M:%S");

		if (!$this->save($post_entity)) {
			throw new Exception ("Ha habido un error editando la parte.");
		}

		// Upload media
		$status = parent::save_main_image($id, $post_entity[$this->name]['main_img']);

		//if error
		if($status !== true){
			throw new Exception("Ha habido un error subiendo las imágenes o ficheros adjuntos.");
		}

		return true;
	}

	public function remove($entity_id){
		if (!$this->delete($entity_id)) {
			throw new Exception("Ha habido un error borrando la parte.");
		}

		//search folder and delete
		$dir = new Folder(parent::get_root_resources().$this->content_path.DS.$entity_id);

		$dir->delete();
		return true;
	}
}
?>
