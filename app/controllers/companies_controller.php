<?php
    include_once "base_controller.php";
    include_once "admin_controller.php";
    include_once "people_controller.php";
    include_once "tasks_controller.php";
    include_once "tags_controller.php";
    include_once "kases_controller.php";

    uses('sanitize');

    class CompaniesController extends BaseController
    {
        var $paginate = array('limit' => 15,
        'order' => array('Company.name' => 'asc'));

        var $uses = array('Company', 'Person', 'ContactMethod', 'Attachment',
                'Note', 'Task', 'Tag', 'Address', 'Group', 'Kase', 'Lru');
        var $helpers = array('Html','Javascript','Ajax', 'Form',
          'CountryList', 'Textile');
        var $components = array('RequestHandler');

        function index()
        {
            $this->set('tab', 'companies');

            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $companies = $this->paginate('Company',
                array('Company.creator_id' => $users));

            $this->set('companies', PeopleController::populate_contact(
                $this, $companies, 'Company', 1));

            TagsController::generate_tag_list_for_type($this, 1);
        }

        function edit($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'companies');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);
                $this->params = $mr_clean->clean($this->params);

                $this->company_secure_read($id);
                $this->Company->id = $id;
                $this->Company->read();
                if ($this->Company->save($this->data))
                {
                    PeopleController::save_contacts(
                        $this->params, $id, 1, $this);

                    PeopleController::save_addresses(
                        $this->params, $id, 1, $this);

                    $this->redirect('/companies/view/'. $this->Company->id);
                }
            }
            else
            {
                $this->data = $this->company_secure_read($id);
                PeopleController::load_contact_methods($id, 1, $this);
                PeopleController::load_addresses($id, 1, $this);
            }
        }

        function picture($id)
        {
            $this->set('tab', 'companies');

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

                $this->company_secure_read($id);
                $this->Company->id = $id;
                $this->Company->read();
                $this->data['Company']['image_id'] = $this->Attachment->id;
                $this->Company->save($this->data);

                $this->redirect('/companies/view/'. $id);
            }
        }

        function tag($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'companies');

            $query = "SELECT Company.* FROM ". BaseController::get_db_prefix()
                . "companies Company
                        LEFT JOIN tags t ON t.id = $id
                        LEFT JOIN association_tags ats ON ats.tag_id = t.id
                        WHERE ats.association_type = 1
                        AND ats.associated_with_id = Company.id";

            $query .= " AND Company.creator_id IN " .
            AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $this->set('companies', $this->Person->query($query));

            $this->Tag->id = $id;
            $this->set('tag', $this->Tag->read());

            TagsController::generate_tag_list_for_type($this, 1);

            $this->render('index');
        }

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'companies');
            $this->set('id', $id);

            if ($this->RequestHandler->isPost())
            {
                $sql = 	"DELETE FROM ". BaseController::get_db_prefix() .
                  "companies WHERE id = {$id}" .
                  " AND creator_id IN (" . implode(",",
                  AdminController::extract_users($this,
                  $this->logged_on_group_id())) . ")";

                $this->Company->query($sql);


                $this->remove_link('/companies/view/' . $id);

                $this->redirect('/companies');
            }
            else
            {
                $comp = $this->company_secure_read($id);
                $this->set('company', $comp);
            }
        }

        function add()
        {
            $this->set('tab', 'companies');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);

                $this->data['Company']['creator_id'] =
                $this->logged_on_user_id();
                $this->data['Company']['account_id'] =
                $this->logged_on_account_id();

                if ($this->Company->save($this->data))
                {
                    PeopleController::save_contacts(
                        $this->params, $this->Company->id, 1, $this);

                    PeopleController::save_addresses(
                        $this->params, $this->Company->id, 1, $this);

                    $this->redirect('/companies/view/'. $this->Company->id);
                    exit;
                }
            }

            // Call the method with bad parameters that I know won't
            // find any data so the method will load defaults instead.
            PeopleController::load_contact_methods(-1, -1, $this);
            PeopleController::load_addresses(-1, -1, $this);
        }

        function view($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'companies');

            $comp = $this->company_secure_read($id);
            $this->set('company', $comp);

            $this->cache_link('/companies/view/' . $id,
                $comp['Company']['name']);

            $this->set('people', $this->Person->findAll(array(
                'company_id' => $comp['Company']['id'],
                        'Person.account_id' => $this->logged_on_account_id())));

            $this->set('notes', $this->Note->find('all', array(
              'conditions' => array('associated_with_id' => $id,
                'association_type' => 1),
              'order' => 'Note.created DESC'
            )));

            $this->set("editable", AdminController::can_edit($this,
                $this->logged_on_group_id(), $comp['Company']['creator_id']));

            $this->set('kases', KasesController::get_kases($this));

            PeopleController::load_addresses($id, 1, $this);
            PeopleController::load_contact_methods($id, 1, $this);

            TagsController::generate_tag_list($this, $id, 1);

            TasksController::generate_task_list($this, $id, 1);

            $about = $this->Note->findAll(array(
                                'associated_with_id' => $id, 'association_type' => 101));
            if(! empty($about))
            $this->set('about', $about[0]['Note']['text']);
            else
            $this->set('about', '');

            $this->set('associated_with_id', $id);
            $this->set('association_type', 1);
        }

        function partial($id)
        {
            $mr_clean = new Sanitize();
            $this->params = $mr_clean->clean($this->params);

            if(empty($this->params['form']['search_field']))
              $name = $id;
            else
              $name = $this->params['form']['search_field'];

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $companies = $this->Company->findAll(
                        "lower(name) LIKE '{$name}%' AND Company.creator_id IN "
                . $users);

            PeopleController::populate_contact(
                $this, $companies, 'Company', 1);

            $this->set('companies', $companies);

            $this->set('search_string', $name);
            $this->layout = "ajax";
        }

        private function company_secure_read($id)
        {
            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $obj = $this->Company->find(array('Company.id' => $id,
                        'Company.creator_id' => $users));

            if(empty($obj))
            {
                $this->redirect('/admin/security');
                exit;
            }
            return $obj;
        }
    }
?>
