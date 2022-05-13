<?php
class SectionsController extends AppController{
	public $name = "Sections";
	//path to the resources
	private $content_path = "sections";

	//pagination
	public $paginate = array(
		'limit' => 500,
		'order' => array(
			'Section.id' => 'asc'
		)
	);
	
	public function index($keyword = null) {
		// if search
		if ($this->request->is('post')) {
			// recover the keyword
			$keyword = $this->request->data['Section']['keyword'];
			//re-send to the same controller with the keyword
			return $this->redirect(array('action' => 'index', $keyword));
		}

		$sections = array();

		// If performing search, there is a keyword
		if($keyword != null){
			$sections = $this->Section->find(
				"treepathcomplete",
				array(
					"conditions" => array(
						'Section.name LIKE' => '%'.$keyword.'%'
					)
				)
			);
		}else{
			$sections = $this->Section->find("treepathcomplete");
		}

		$this->set('sections', $sections);
		$this->set("keyword", $keyword);
	}

	public function view($id = null) {
		if (!$id) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->Section->id = $id;
		$section = $this->Section->read();

		if (!$section) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->set('section', $section);
    }

	public function add() {
		// If request is type post, add new element
		if ($this->request->is('post')) {
			$section = $this->request->data;

			$section['Section']['template'] = "standard";

			if ($this->Section->save($section)) {
				// Upload profile image
				$path = $this->content_path.$this->Section->id;
				$section_img = $this->request->data['Section']['section_img'];

				$status = parent::upload_image($path, $section_img, 'principal');

				//if error
				if($status !== true){
					//print trace
					$this->Session->setFlash(
						'<p>'.$status.'</p>',
						'default', 
						array("class" => 'error-message')
					);

					//redirect to the controller and display error msg
					return $this->redirect(array('action' => 'add'));
				}

				$this->Session->setFlash('<p>La sección se ha añadido correctamente.</p>');
				return $this->redirect(array('action' => 'edit', $this->Section->id));
			}
			$this->Session->setFlash(
				'<p>Ha habido un error añadiendo la sección.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}

		$this->set(
			'sections', 
			$this->Section->find('treepath')
		);
	}

	public function edit($id = null, $language = null) {
		//If language is not set, set default
		if(!$language){
			$language = Configure::read("Config.language");
		}

		$this->Section->id = $id;
		$this->Section->locale = $language;
		$section = $this->Section->read();		

		//If trying to edit a section that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('action' => 'index'));
		}
		
		// If we are submitting edition form
		if ($this->request->is(array('post', 'put'))) {
			$section = $this->request->data;
			$section['Section']['id'] = $id;

			if ($this->Section->save($section)) {
				$this->request->data = $section;
				// Upload profile image
				$path = $this->content_path.$id;
				$section_img = $this->request->data['Section']['section_img'];

				$status = parent::upload_image($path, $section_img, 'principal');

				//if error
				if($status !== true){
					$this->Session->setFlash(
						'<p>'.$status.'</p>',
						'default', 
						array("class" => 'error-message')
					);
					//re-asign the data
					$this->request->data = $section;
					//redirect
					return $this->redirect(array('action' => 'edit', $id));
				}
				$this->Session->setFlash('<p>La sección se ha editado correctamente.</p>');
				return $this->redirect(array('action' => 'edit', $id));
			}
			$this->Session->setFlash(
				'<p>Ha habido un error editando la sección.</p> <p>Por favor, comprueba los datos e inténtalo de nuevo más tarde.</p>', 
				'default', 
				array("class" => 'error-message')
			);
		}
		$this->request->data = $section;
		$this->set('section', $section);
		$this->set(
			'sections', 
			$this->Section->find(
				'treepath',
				array(
					"conditions" => array(
						"Section.id != ".$section['Section']['id']
					)
				)
			)
		);

		$this->set('img_url', parent::get_img_url($this->content_path, 'principal', $this->Section->id));
		$this->set('entity_path', $this->content_path);


		$full_array = array();
		$full_array['parts'] = array();
		$full_array['order'] = array();
		$full_array['parts_aux'] = array();

		$full_array = $this->get_parts(
			$this->Section->SectionPartImage, 
			"SectionPartImage", 
			"section_part_images/", 
			$id, 
			$full_array);

		$full_array = $this->get_parts(
			$this->Section->SectionGallery, 
			"SectionGallery", 
			"section_galleries/", 
			$id, 
			$full_array);
		$full_array = $this->get_parts(
			$this->Section->SectionColumn, 
			"SectionColumn", 
			"section_columns/", 
			$id, 
			$full_array);

		$full_array = $this->get_parts(
			$this->Section->SectionTab, 
			"SectionTab", 
			"section_tabs/", 
			$id, 
			$full_array);

		$full_array = $this->get_parts(
			$this->Section->SectionPartLink, 
			"SectionPartLink", 
			"section_links/", 
			$id, 
			$full_array);
		
		/*$full_array = $this->get_parts(
			$this->Section->SectionPartTab, 
			"SectionPartTab", 
			"parttabs/", 
			$id, 
			$language, 
			$full_array);
		$full_array = $this->get_parts(
			$this->Section->SectionPartSmalllink, 
			"SectionPartSmalllink", 
			"partsmalllinks/", 
			$id, 
			$language, 
			$full_array);*/

		array_multisort($full_array['order'], SORT_ASC, $full_array['parts_aux'], $full_array['parts']);

		$this->set('parts', $full_array['parts']);
	}

	private function get_parts($find_object, $class_name, $path, $id, $full_array){
		// OBTAIN PART IMAGES
		$section_parts = $find_object->find(
			"all",
			array(
				'conditions' => array(
					$class_name.'.section_id' => $id
				),
				'order' => array(
					$class_name.'.sort' => 'asc'
				),
				'recursive' => '0'
			)
		);


		$section_parts = parent::add_img_urls($section_parts, $class_name, $this->content_path.$path, 'principal');

		foreach ($section_parts as $part) {
			$full_array['order'][$part[$class_name]['id'].$class_name] = $part[$class_name]['sort'];
			$full_array['parts_aux'][$part[$class_name]['id'].$class_name] = $part;
			$full_array['parts'][$part[$class_name]['id'].$class_name] = $part;
		}

		return $full_array;
	}

	public function delete($id = null) {
		if ($this->request->is('get')) {
			return $this->redirect(array('action' => 'index'));
		}

		$this->Section->id = $id;
		$section = $this->Section->read();

		// If trying to remove a element that doesn't exists, redirect
		if (!$id || !$section) {
			return $this->redirect(array('action' => 'index'));
		}

		if ($this->Section->delete($id)) {
			//search folder and delete
			$dir = new Folder($this->content_path.$id);
			$dir->delete();

			$this->Session->setFlash(
				'<p>La sección se ha eliminado correctamente.</p>', 
				'default', 
				array("class" => 'error-message')
			);
			return $this->redirect(array('action' => 'index'));
		}
	}
}
?>