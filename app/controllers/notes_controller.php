<?php
    include_once "base_controller.php";
    include_once "admin_controller.php";
    include_once "kases_controller.php";

    uses('sanitize');

    class NotesController extends BaseController
    {
        var $uses = array('Note', 'Person',
                'Company', 'Kase', 'Attachment', 'Group', 'Lru');
        var $helpers = array('Html', 'Form', 'Javascript','Ajax', 'Textile');
        var $components = array('RequestHandler');

        function addnote()
        {
            if($this->RequestHandler->isAjax())
            $this->layout = "ajax";

            $associated_with_id = $this->params['form']['associated_with_id'];
            $association_type = $this->params['form']['association_type'];


            if (! empty($this->params['form']['File']) &&
                is_uploaded_file($this->params['form']['File']['tmp_name']))
            {
                $fileData = fread(fopen(
                        $this->params['form']['File']['tmp_name'], "r"),
                    $this->params['form']['File']['size']);

                $this->params['form']['File']['name'] =
                  $this->params['form']['File']['name'];
                $this->params['form']['File']['type'] =
                  $this->params['form']['File']['type'];
                $this->params['form']['File']['size'] =
                  $this->params['form']['File']['size'];
                $this->params['form']['File']['data'] = $fileData;

                $this->params['form']['File']['creator_id'] =
                $this->logged_on_user_id();
                $this->Attachment->save($this->params['form']['File']);

                $text = $this->params['form']['text'];

                if(empty($text))
                  $text = ' ';

                $file_id = $this->Attachment->id;
                $file_name = $this->params['form']['File']['name'];

                $this->params['form']['text'] = $text;
            }

            if(! empty($this->params['form']['text']))
            {
                $note = array();
                $note['Note'] = array();
                $note['Note']['text'] = $this->params['form']['text'];
                $note['Note']['kase_id'] = $this->params['form']['kase_id'];
                $note['Note']['associated_with_id'] = $associated_with_id;
                $note['Note']['association_type'] = $association_type;
                $note['Note']['creator_id'] = $this->logged_on_user_id();

                if(!empty($file_id))
                {
                  $note['Note']['file_id'] = $file_id;
                  $note['Note']['file_name'] = $file_name;
                }
                $this->Note->save($note);
            }

            if(! $this->RequestHandler->isAjax())
            {

                $this->redirect($this->link_from_association(
                        $associated_with_id, $association_type));
                exit;
            }

            $this->set('kases', KasesController::get_kases($this));
            $this->set('associated_with_id', $associated_with_id);
            $this->set('association_type', $association_type);

            $this->set('notes', $this->Note->find('all', array(
              'conditions' => array('associated_with_id' => $associated_with_id,
                'association_type' => $association_type),
              'order' => 'Note.created DESC'
            )));
        }

        function download($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $file = $this->Attachment->find('first',
                array('conditions' => array('Attachment.id' => $id,
                        'Attachment.creator_id' => $users)));

            header('Content-type: ' . $file['Attachment']['type']);
            header('Content-length: ' . $file['Attachment']['size']);
            header('Content-Disposition: attachment; filename='
                .$file['Attachment']['name']);;
            echo $file['Attachment']['data'];

            exit();
        }

        function about($associated_with_id, $association_type)
        {
            $mr_clean = new Sanitize();
            $associated_with_id = $mr_clean->paranoid($associated_with_id);
            $association_type = $mr_clean->paranoid($association_type);

            $this->set('tab', 'none');

            if (!empty($this->params['form']))
            {
                $type = 100 + $association_type;
                $this->Note->query("DELETE FROM ". BaseController::get_db_prefix() . "notes WHERE " .
                                "associated_with_id = $associated_with_id AND " .
                                "association_type = $type");

                $cm = array();
                $cm['Note'] = array();
                $cm['Note']['text'] =
                $this->params['form']['text'];
                $cm['Note']['associated_with_id'] =
                $associated_with_id;
                $cm['Note']['association_type'] =
                (100 + $association_type);
                $cm['Note']['creator_id'] = $this->logged_on_user_id();
                $this->Note->save($cm);

                $this->redirect($this->link_from_association(
                        $associated_with_id, $association_type));
                exit;
            }
            else
            {
                $note = $this->Note->findAll(array(
                                'associated_with_id' => $associated_with_id,
                                'association_type' => (100 + $association_type)));

                if(! empty($note))
                $this->set('note', $note[0]['Note']['text']);
                else
                $this->set('note', '');

                $this->set('link', $this->link_from_association(
                        $associated_with_id, $association_type));

                $this->set('name', $this->name_from_association($this->Note,
                        $associated_with_id, $association_type));
                
                $html = new HtmlHelper();
                $this->set('post_form', $html->url('/notes/about/') . $associated_with_id .
                        '/' . $association_type);
            }
        }

        function view($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'none');

            $this->note_secure_read($id);
            $this->Note->id = $id;
            $nt = $this->Note->read();

            $this->set('note', $nt);

            $this->set('link', $this->link_from_association(
                    $nt['Note']['associated_with_id'],
                    $nt['Note']['association_type']));

            $this->set('name', $this->name_from_association($this->Note,
                    $nt['Note']['associated_with_id'],
                    $nt['Note']['association_type']));
        }

        function edit($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'none');

            if (!empty($this->data))
            {
                $this->note_secure_read($id);
                $this->Note->id = $id;
                $nt = $this->Note->read();
                if ($this->Note->save($this->data))
                {
                    $this->redirect($this->link_from_association(
                            $nt['Note']['associated_with_id'],
                            $nt['Note']['association_type']));
                    exit;
                }
            }
            else
            {
                $this->note_secure_read($id);
                $this->Note->id = $id;
                $this->data = $this->Note->read();

                $this->set('note', $this->data);

                $this->set('link', $this->link_from_association(
                        $this->data['Note']['associated_with_id'],
                        $this->data['Note']['association_type']));

                $this->set('name', $this->name_from_association($this->Note,
                        $this->data['Note']['associated_with_id'],
                        $this->data['Note']['association_type']));
            }

        }

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->Note->id = $id;
            $nt = $this->Note->read();
            $file_id = $nt['Note']['file_id'];

            $this->set('note', $nt);

            $this->note_secure_read($id);
            $this->Note->query(
                        "DELETE FROM ". BaseController::get_db_prefix() . "notes WHERE id = {$id}" .
                        " AND creator_id IN (" . implode(",",
                    AdminController::extract_users($this,
                        $this->logged_on_group_id())) . ")");

            if(! empty($file_id))
                $this->Attachment->query(
                  "DELETE FROM ". BaseController::get_db_prefix() . "files WHERE id = {$file_id}");

            $this->redirect($this->link_from_association(
                    $nt['Note']['associated_with_id'],
                    $nt['Note']['association_type']));
        }

        public static function link_from_association(
            $associated_with_id, $association_type)
        {
            App::import('Helper', 'Html');
            $html = new HtmlHelper();

            if($association_type == 0)
              return $html->url('/people/view/') . $associated_with_id;
            else if($association_type == 1)
              return $html->url('/companies/view/') . $associated_with_id;

            return $html->url('/kases/view/') . $associated_with_id;
        }

        public static function name_from_association(
            $obj, $associated_with_id, $association_type)
        {
            if($association_type == 0 or $association_type == 100)
            {
                $ret = $obj->query(
                    "SELECT first_name,surname FROM ". 
                    BaseController::get_db_prefix()
                    . "people as people WHERE id = "
                    . $associated_with_id);
                return $ret[0]['people']['first_name']
                . ' ' . $ret[0]['people']['surname'];
            }
            else if($association_type == 1 or $association_type == 101)
            {
                $ret = $obj->query(
                    "SELECT name FROM ". 
                    BaseController::get_db_prefix()
                    . "companies AS companies WHERE id = "
                    . $associated_with_id);
                return $ret[0]['companies']['name'];
            }

            $ret = $obj->query(
               "SELECT name FROM ". BaseController::get_db_prefix()
                . "kases as kases WHERE id = "
                . $associated_with_id);
            return $ret[0]['kases']['name'];
        }

        private function note_secure_read($id)
        {
            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $obj = $this->Note->find(array('Note.id' => $id,
                        'Note.creator_id' => $users));

            if(empty($obj))
            {
                $this->redirect('/admin/security');
                exit;
            }
            return $obj;
        }
    }
?>
