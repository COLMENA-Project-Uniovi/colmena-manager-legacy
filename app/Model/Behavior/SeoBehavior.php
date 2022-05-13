<?php

class SeoBehavior extends ModelBehavior {
    public $name = 'Seo';

    public $seoModel = array();

    /*
        Initiate behaviour for the model using specified settings.
     */
    public function setup(Model $Model, $settings = array()) {
        $this->seoModel = $this->seoModel($Model);
    }

    /*public function beforeFind(Model $Model, $query = array()){

    }*/
    public function afterFind(Model $Model, $results = array(), $primary = false){
        if (!isset($results[0][$Model->name]['id'])) {
            return $results;
        }

        $locale = $this->_getLocale($Model);

        $aux_ids = array();
        foreach ($results as $result) {
            array_push($aux_ids, $result[$Model->name]['id']);
        }

        $conditions = array(
            'AND' => array(
                'SeoModel.model' => $Model->name,
                'SeoModel.locale' => $locale,
                'SeoModel.foreign_key IN' => $aux_ids
            )
        );

        if(count($results) == 1){
            $conditions = array(
            'AND' => array(
                'SeoModel.model' => $Model->name,
                'SeoModel.locale' => $locale,
                'SeoModel.foreign_key' => $aux_ids[0]
            )
        );
        }

        $seos = $this->seoModel->find(
            'all',
            array(
                'conditions' => $conditions
            )
        );

        $aux_results = array();
        foreach ($results as $result) {
            $entity_id = $result[$Model->name]['id'];
            $entity_seo = array();
            foreach ($seos as $seo) {
                if($seo['SeoModel']['foreign_key'] == $entity_id){
                    $entity_seo[$seo['SeoModel']['field']] = $seo['SeoModel']['content'];
                }
            }
            $result[$Model->name]['SeoModel'] = $entity_seo;
            array_push($aux_results, $result);
        }

        return $aux_results;
    }
    public function afterSave(Model $Model, $created, $options = array()){
        $seo_fields = $Model->data[$Model->name]['SeoModel'];

        $seos = array();
        foreach ($seo_fields as $field => $content) {
            $seo = array();

            if($field == 'folder'){
                $content = $Model->slugify($content);
            }

            $seo['model'] = $Model->name;
            $seo['field'] = $field;
            $seo['content'] = $content;
            $seo['foreign_key'] = $Model->data[$Model->name]['id'];
            $seo['locale'] = $this->_getLocale($Model);

            array_push($seos, $seo);
        }

        return $this->seoModel->saveSeoFields($seos);
    }

    public function afterDelete(Model $Model){
        return $this->seoModel->removeSeoFields($Model->name, $Model->data[$Model->name]['id']);
    }

/**
 * Get instance of model for translations.
 *
 * If the model has a seoModel property set, this will be used as the class
 * name to find/use. If no seoModel property is found 'SeoModel' will be used.
 *
 * @param Model $Model Model to get a seoModel for.
 * @return Model
 */
    public function seoModel(Model $Model) {
        if (empty($this->seoModel)) {
            $className = 'SeoModel';
            $this->seoModel = ClassRegistry::init($className);
        }

        return $this->seoModel;
    }

/**
 * Get selected locale for model
 *
 * @param Model $Model Model the locale needs to be set/get on.
 * @return mixed string or false
 */
    protected function _getLocale(Model $Model) {
        if (!isset($Model->locale) || $Model->locale === null) {
            $I18n = I18n::getInstance();
            $I18n->l10n->get(Configure::read('Config.language'));
            $Model->locale = $I18n->l10n->locale;
        }

        return $Model->locale;
    }
}

?>
