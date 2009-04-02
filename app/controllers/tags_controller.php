<?php
    include_once "base_controller.php";
    include_once "admin_controller.php";

    uses('sanitize');

    class TagsController extends BaseController
    {
        var $uses = array('Tag', 'AssociationTag', 'Group', 'Person', 'Lru');
        var $helpers = array('Html','Javascript','Ajax','Form');

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->layout = "ajax";

            $this->AssociationTag->id = $id;
            $at = $this->AssociationTag->read();

            $this->AssociationTag->query(
                "DELETE FROM ". BaseController::get_db_prefix() .
                "association_tags WHERE id = {$id}");

            $this->generate_tag_list($this,
                $at['AssociationTag']['associated_with_id'],
                $at['AssociationTag']['association_type']);
        }

        function addtag()
        {
            $mr_clean = new Sanitize();
            $this->params = $mr_clean->clean($this->params);

            $this->layout = "ajax";

            $tag = $this->Tag->find(array(
                        'name' => strtolower($this->params['form']['tag'])));
            if(empty($tag))
            {
                $cm = array();
                $cm['Tag'] = array();
                $cm['Tag']['account_id'] = $this->logged_on_account_id();
                $cm['Tag']['name'] =
                strtolower($this->params['form']['tag']);
                $this->Tag->save($cm);
            }
            else
            $this->Tag->id = $tag['Tag']['id'];

            $a_id = $this->params['form']['associated_with_id'];
            $type = $this->params['form']['association_type'];

            $assoc = $this->AssociationTag->find(array(
                        'associated_with_id' => $a_id,
                        'association_type'	=> $type,
                        'tag_id' => $this->Tag->id));

            if(empty($assoc))
            {
                $cm = array();
                $cm['AssociationTag'] = array();
                $cm['AssociationTag']['tag_id'] = $this->Tag->id;
                $cm['AssociationTag']['associated_with_id'] = $a_id;
                $cm['AssociationTag']['association_type'] = $type;
                $this->AssociationTag->save($cm);
            }

            $this->generate_tag_list($this, $a_id, $type);
        }

        public static function generate_tag_list_for_type($view, $type)
        {
            $tags = $view->Tag->query('SELECT tags.*, ats.id FROM '. BaseController::get_db_prefix() . 'tags AS tags' .
                ' LEFT JOIN '. BaseController::get_db_prefix() . 'association_tags ats ON ats.tag_id = tags.id' .
                ' WHERE ats.association_type = ' . $type);
            $view->set('tags', $tags);

            $view->set('type', $type);
        }

        public static function generate_tag_list($view, $id, $type)
        {
            $tags = $view->Tag->query('SELECT tags.*, ats.id FROM '. BaseController::get_db_prefix() . 'tags AS tags' .
                ' LEFT JOIN '. BaseController::get_db_prefix() . 'association_tags ats ON ats.tag_id = tags.id' .
                ' WHERE ats.associated_with_id = ' . $id .
                ' AND ats.association_type = ' . $type);
            $view->set('tags', $tags);

            $view->set('association', $id);
            $view->set('type', $type);
        }
    }
?>