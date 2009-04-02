<?php
    include_once "base_controller.php";
    include_once "tasks_controller.php";
    include_once "tags_controller.php";

    uses('sanitize');

    class KasesController extends BaseController
    {
        var $paginate = array('limit' => 15,
        'order' => array('Kase.name' => 'asc'));

        var $uses = array('Kase', 'ContactMethod', 'Note',
                'Task', 'Person', 'Company', 'Tag', 'Group', 'Lru');
        var $helpers = array('Html','Javascript','Ajax', 'Textile');
        var $components = array('RequestHandler');

        function index()
        {
            $this->set('tab', 'cases');


            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $kases = $this->paginate('Kase',
                array('Kase.creator_id' => $users));

            $kases = $this->fill_in_kases($kases);

            $this->set('kases', $kases);

            TagsController::generate_tag_list_for_type($this, 2);
        }

        function tag($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'cases');

            $prefix = BaseController::get_db_prefix();

            $query = <<<EOT
SELECT Kase.* FROM {$prefix}kases Kase
LEFT JOIN {$prefix}tags t ON t.id = $id
LEFT JOIN {$prefix}association_tags ats ON ats.tag_id = t.id
WHERE ats.association_type = 2 AND ats.associated_with_id = Kase.id
EOT;

            $this->set('kases', $this->Person->query($query));

            $this->Tag->id = $id;
            $this->set('tag', $this->Tag->read());

            TagsController::generate_tag_list_for_type($this, 1);

            $this->render('index');
        }

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'cases');
            $this->set('id', $id);

            if ($this->RequestHandler->isPost())
            {
                $this->Kase->query(
                  "DELETE FROM ". BaseController::get_db_prefix() .
                  "kases WHERE id = {$id}" .
                  " AND creator_id IN (" . implode(",",
                  AdminController::extract_users($this,
                  $this->logged_on_group_id())) . ")");

                $this->remove_link('/kases/view/' . $id);

                $this->redirect('/kases');
            }
            else
            {
                $kase = $this->kase_secure_read($id);
                $this->set('kase', $kase);
            }
        }

        function add()
        {
            $this->set('tab', 'cases');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $mr_clean->clean($this->data);

                $this->data['Kase']['creator_id'] =
                $this->logged_on_user_id();
                $this->data['Kase']['account_id'] =
                $this->logged_on_account_id();

                if ($this->Kase->save($this->data))
                {

                    $this->Session->setFlash($this->data['Kase']['name']
                        . ' has been added.');
                    $this->redirect('/kases/view/'. $this->Kase->id);
                }
            }
        }

        function edit($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'kases');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $mr_clean->clean($this->data);

                $this->kase_secure_read($id);
                $this->Kase->id = $id;
                $this->Kase->read();

                $this->Kase->creator_id = $this->logged_on_user_id();
                $this->Kase->account_id = $this->logged_on_account_id();

                if ($this->Kase->save($this->data))
                {
                    $this->redirect('/kases/view/'. $this->Kase->id);
                }
            }
            else
            {
                $this->data = $this->kase_secure_read($id);
            }
        }

        function view($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'cases');

            $kase = $this->kase_secure_read($id);
            $this->set('kase', $kase);

            $this->set('notes', $this->Note->find('all', array(
              'conditions' => array('associated_with_id' => $id, 'association_type' => 2),
              'order' => 'Note.created DESC'
            )));

            $this->set("editable", AdminController::can_edit($this,
                $this->logged_on_group_id(), $kase['Kase']['creator_id']));

            $this->cache_link('/kases/view/' . $id,
                $kase['Kase']['name']);

            $this->set('kases', KasesController::get_kases($this));

            $this->set('associated_with_id', $id);
            $this->set('association_type', 2);

            TagsController::generate_tag_list($this, $id, 2);

            TasksController::generate_task_list($this, $id, 2);

            $about = $this->Note->findAll(array(
              'associated_with_id' => $id, 'association_type' => 102));

            if(! empty($about))
            $this->set('about', $about[0]['Note']['text']);
            else
            $this->set('about', '');

            $prefix = BaseController::get_db_prefix();

            $query = <<<EOT
SELECT Person.*, Company.* FROM {$prefix}people Person
LEFT JOIN {$prefix}companies Company ON Company.id = Person.company_id
LEFT JOIN {$prefix}notes Note ON Note.associated_with_id = Person.id
WHERE Note.kase_id = $id
EOT;

            $this->set('people', $this->Company->query($query));

            $query = <<<EOT
SELECT DISTINCT Company.* FROM {$prefix}companies Company
LEFT JOIN {$prefix}people Person ON Person.company_id = Company.id
LEFT JOIN {$prefix}notes Note ON Note.associated_with_id = Person.id
WHERE Note.kase_id = $id
EOT;
            $this->set('companies', $this->Company->query($query));
        }

        private function kase_secure_read($id)
        {
            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $obj = $this->Kase->find(array('Kase.id' => $id,
                        'Kase.creator_id' => $users));

            if(empty($obj))
            {
                $this->redirect('/admin/security');
                exit;
            }
            return $obj;
        }

        private function fill_in_kases($kases)
        {
            foreach($kases as $key => $kase)
            {
                $notes = $this->Note->findAll(
                    array('associated_with_id' => $kase['Kase']['id']));

                if(count($notes) > 0)
                {
                    $kase['Kase']['updated_on'] = $notes[0]['Note']['created'];
                    $kases[$key] = $kase;
                }
            }

            return $kases;
        }

        public static function get_kases($view)
        {
            $users = AdminController::extract_users($view,
                $view->logged_on_group_id());

            $kases = $view->Kase->findAll(
                array('Kase.creator_id' => $users));

            return $kases;
        }
    }
?>
