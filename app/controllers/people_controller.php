<?php
    include_once "base_controller.php";
    include_once "tasks_controller.php";
    include_once "tags_controller.php";
    include_once "kases_controller.php";

    uses('sanitize');

    class PeopleController extends BaseController
    {
        var $paginate = array('limit' => 15,
        'order' => array('Person.surname' => 'asc'));

        var $uses = array('Person', 'ContactMethod', 'Address', 'Attachment',
                'Note', 'Task', 'Company', 'Kase', 'Tag', 'Group', 'Lru');
        var $helpers = array('Html','Javascript','Ajax', 'Form',
          'CountryList', 'Textile');
        var $components = array('RequestHandler', 'Email');

        function index()
        {
            $this->set('tab', 'contacts');

            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $people = $this->paginate('Person',
                array('Person.creator_id' => $users));

            $this->set('people', PeopleController::populate_contact(
                    $this, $people, 'Person'));

            TagsController::generate_tag_list_for_type($this, 0);
        }

        function picture($id)
        {
            $this->set('tab', 'contacts');

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

                $this->people_secure_read($id);
                $this->Person->id = $id;
                $this->Person->read();
                $this->data['Person']['image_id'] = $this->Attachment->id;
                $this->Person->save($this->data);

                $this->redirect('/people/view/'. $id, true);
            }
        }

        function edit($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'contacts');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);

                $this->people_secure_read($id);
                $this->Person->id = $id;
                $this->Person->read();

                $this->data['Person']['company_id'] =
                $this->get_company_id(
                    $this->data['Company']['name']);

                if ($this->Person->save($this->data))
                {
                    PeopleController::save_contacts(
                        $this->params, $id, 0, $this);

                    PeopleController::save_addresses(
                        $this->params, $this->Person->id, 0, $this);

                    $this->redirect('/people/view/'. $this->Person->id, true);
                }
            }
            else
            {
                $this->data = $this->people_secure_read($id);
                PeopleController::load_contact_methods($id, 0, $this);
                PeopleController::load_addresses($id, 0, $this);
            }
        }

        function delete($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'contacts');
            $this->set('id', $id);

            if ($this->RequestHandler->isPost())
            {
                $this->Person->query(
                  "DELETE FROM ". BaseController::get_db_prefix() .
                  "people WHERE id = {$id}" .
                  " AND creator_id IN (" . implode(",",
                  AdminController::extract_users($this,
                  $this->logged_on_group_id())) . ")");

                $this->remove_link('/people/view/' . $id);
                $this->redirect('/people');
            }
            else
            {
                $person = $this->people_secure_read($id);
                $this->set('person', $person);
            }
        }

        function add()
        {
            $this->set('tab', 'contacts');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);
                $this->params = $mr_clean->clean($this->params);

                $this->data['Person']['creator_id'] =
                $this->logged_on_user_id();
                $this->data['Person']['administrator'] = 0;

                $this->data['Person']['account_id'] =
                $this->logged_on_account_id();
                $this->data['Person']['company_id'] =
                $this->get_company_id(
                    $this->data['Person']['company']);

                if ($this->Person->save($this->data))
                {
                    PeopleController::save_contacts(
                        $this->params, $this->Person->id, 0, $this);

                    PeopleController::save_addresses(
                        $this->params, $this->Person->id, 0, $this);

                    $this->redirect('/people/view/'. $this->Person->id);
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

            $this->set('tab', 'contacts');

            $person = $this->people_secure_read($id);

            $this->set("editable", AdminController::can_edit($this,
                $this->logged_on_group_id(), $person['Person']['creator_id']));

            if($this->is_administrator() && $this->logged_on_user_id() != $id
              && $person['Person']['username'] == NULL)
            {
              $person['Person']['invitable'] = 'Yes';
            }

            $this->set('person', $person);
            $this->set('notes', $this->Note->find('all', array(
              'conditions' => array('associated_with_id' => $id,
                'association_type' => 0),
              'order' => 'Note.created DESC'
                    )));

            $this->set('kases', KasesController::get_kases($this));

            $this->cache_link('/people/view/' . $id,
                $person['Person']['first_name'] . ' ' .
                $person['Person']['surname']);

            PeopleController::load_addresses($id, 0, $this);
            PeopleController::load_contact_methods($id, 0, $this);

            TagsController::generate_tag_list($this, $id, 0);

            TasksController::generate_task_list($this, $id, 0);

            $this->set('kases',
                $this->Kase->findAll(array('account_id' =>
                        $this->logged_on_account_id())));
            $this->set('associated_with_id', $id);
            $this->set('association_type', 0);

            $about = $this->Note->findAll(array(
              'associated_with_id' => $id, 'association_type' => 100));
            if(! empty($about))
            $this->set('about', $about[0]['Note']['text']);
            else
            $this->set('about', '');
        }

        function invite($id)
        {
            if ($this->RequestHandler->isPost())
            {
                $id = $this->params['form']['id'];
                $group = $this->params['data']['parent_id'];
                $from = $this->params['form']['from'];
                $invite_code = $this->params['form']['invite'];
                
                $this->Email->delivery = 'smtp';
                $this->Email->smtpOptions = array(  'port'=> 25, 'host' => 'localhost');
                $this->Email->to = $this->params['form']['email'];
                $this->Email->subject = $this->params['form']['subject'];
                $this->Email->replyTo = $this->params['form']['from'];
                $this->Email->from = $this->params['form']['from'];
                $this->Email->send($this->params['form']['message']);

                $this->people_secure_read($id);
                $this->Person->id = $id;
                $this->Person->read();
                $this->data['Person']['invitation'] = $invite_code;
                $this->Person->save($this->data);

                $this->redirect('/people/view/'. $id, true);
            }

            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'contacts');

            $person = $this->people_secure_read($id);

            $invite_code = rand();
            App::import('Helper', 'Html');
            $html = new HtmlHelper();
            $invitelink = $this->curUrl() . $html->url('/sessions/register/') .
              $invite_code . '/' . $id;

            $logged_user = $this->Session->read('User');

            $message = <<<EOT
Dear {$person['Person']['first_name']},

You have been invited by {$logged_user['first_name']} {$logged_user['surname']} to be a user of the SubwayCRM application at ${invitelink}

Please use the link above to register and select your password.

Thanks.
EOT;
            // Fetch the email address
            $emails = $this->ContactMethod->find(
              array('associated_with_id' => $id,
              'association_type' => 0, 'type' => 1));
            $email = '';
            if(count($emails) > 0)
              $email = $emails['ContactMethod']['detail'];

            // Fetch the from email address
            $emails = $this->ContactMethod->find(
              array('associated_with_id' => $this->logged_on_user_id(),
              'association_type' => 0, 'type' => 1));
            $from = '';
            if(count($emails) > 0)
              $from = $emails['ContactMethod']['detail'];

            // Fetch the groups
            $groups = $this->Group->findAll(
                array('account_id' => $this->logged_on_account_id()));
            $grp = array();
            foreach($groups as $group)
            {
                $grp[$group['Group']['id']] = $group['Group']['name'];
            }
            $this->set('groups', $grp);

            $this->set('person', $person);
            $this->set('message', $message);
            $this->set('email', $email);
            $this->set('from', $from);
            $this->set('invite', $invite_code);
            $this->set('subject', 'Invitation to SubwayCRM');
        }
        
        function curURL() 
        {
            $isHTTPS = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on");
            $port = (isset($_SERVER["SERVER_PORT"]) && ((!$isHTTPS && $_SERVER["SERVER_PORT"] != "80") || ($isHTTPS && $_SERVER["SERVER_PORT"] != "443")));
            $port = ($port) ? ':'.$_SERVER["SERVER_PORT"] : '';
            $url = ($isHTTPS ? 'https://' : 'http://').$_SERVER["SERVER_NAME"].$port;
            return $url;
        }

        function tag($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'contacts');

            $prefix =  BaseController::get_db_prefix();

            $query = <<<EOT
SELECT Person.*, Company.* FROM {$prefix}people Person
LEFT JOIN {$prefix}tags t ON t.id = $id
LEFT JOIN {$prefix}companies Company ON Person.company_id = Company.id
LEFT JOIN {$prefix}association_tags ats ON ats.tag_id = t.id
WHERE ats.association_type = 0 AND ats.associated_with_id = Person.id
EOT;

            $this->set('people', $this->Person->query($query));

            $this->Tag->id = $id;
            $this->set('tag', $this->Tag->read());

            TagsController::generate_tag_list_for_type($this, 0);

            $this->render('index');
        }

        function partial($id)
        {
            $mr_clean = new Sanitize();
            $mr_clean->clean($this->params);

            if(empty($this->params['form']['search_field']))
              $name = $id;
            else
              $name = $this->params['form']['search_field'];

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $people = $this->Person->findAll(
                        "(lower(surname) LIKE '{$name}%' "
                . "or lower(first_name) LIKE '{$name}%')" .
                " AND Person.creator_id IN " . $users);

            $this->set('people', PeopleController::populate_contact(
                    $this, $people, 'Person'));
            $this->set('search_string', $name);
            $this->layout = "ajax";
        }

        function companies($id)
        {
            $mr_clean = new Sanitize();
            $mr_clean->clean($this->params);

            if(empty($id))
            $name = '';
            else
            $name = $id;

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $this->set('companies', $this->Company->findAll(
                        "lower(name) LIKE '{$name}%' AND Company.creator_id IN "
                    . $users));

            $this->layout = "ajax";
        }

        public static function populate_contact($view,
            $people, $type, $t = 0)
        {
            foreach($people as $key => $person)
            {
                $cms = $view->ContactMethod->findAll(
                    array('associated_with_id' => $person[$type]['id'],
                                'association_type' => $t));

                foreach($cms as $cm) {
                    if($cm['ContactMethod']['type'] == 0)
                    {
                        $phone = $cm['ContactMethod']['detail'];
                        break;
                    }
                }

                foreach($cms as $cm) {
                    if($cm['ContactMethod']['type'] == 1)
                    {
                        $email = $cm['ContactMethod']['detail'];
                        break;
                    }
                }

                if(!empty($email))
                  $person[$type]['email'] = $email;
                if(!empty($phone))
                  $person[$type]['phone'] = $phone;

                $email = $phone = null;

                $people[$key] = $person;
            }
            return $people;
        }

        public static function load_addresses($id, $type, $caller)
        {
            $addresses = $caller->Address->findAll(
                array('associated_with_id' => $id,
                        'association_type' => $type));

            for($i = count($addresses); $i < 1; $i++)
            {
                array_push($addresses, array('Address' =>
                        array('street' => '', 'city' => '', 'state' => '',
                    'zip' => '', 'country' => '', 'type' => 'Work')));
            }

            $caller->set('addresses', $addresses);
        }

        public static function save_addresses(
            $params, $id, $caller_type, $caller)
        {
            $caller->Address->deleteAll(
                array('associated_with_id' => $id,
                  'association_type' => $caller_type));

            // Now save all the contact methods
            for($i = 0; $i < 50; $i++)
            {
                if(!empty($params['form']['street'.$i]))
                {
                    if($params['form']['street'.$i] != '')
                    {
                        $cm = array();
                        $cm['Address'] = array();
                        $cm['Address']['street'] =
                        $caller->params['form']['street'.$i];
                        $cm['Address']['city'] =
                        $caller->params['form']['city'.$i];
                        $cm['Address']['state'] =
                        $caller->params['form']['state'.$i];
                        $cm['Address']['zip'] =
                        $caller->params['form']['zip'.$i];
                        $cm['Address']['country'] =
                        $caller->params['form']['country'.$i];
                        $cm['Address']['associated_with_id'] = $id;
                        $cm['Address']['association_type'] =
                        $caller_type;
                        $caller->Address->save($cm);
                    }
                }
            }
        }

        public static function load_contact_methods($id, $type, $caller)
        {
            $phones = $caller->ContactMethod->findAll(
                array('associated_with_id' => $id, 'type' => 0,
                        'association_type' => $type));
            for($i = count($phones); $i < 2; $i++)
            {
                array_push($phones, array('ContactMethod' =>
                        array('name' => '', 'detail' => '')));
            }
            $caller->set('phones', $phones);

            $emails = $caller->ContactMethod->findAll(
                array('associated_with_id' => $id, 'type' => 1,
                        'association_type' => $type));
            for($i = count($emails); $i < 2; $i++)
            {
                array_push($emails, array('ContactMethod' =>
                        array('name' => '', 'detail' => '')));
            }
            $caller->set('emails', $emails);

            $websites = $caller->ContactMethod->findAll(
                array('associated_with_id' => $id, 'type' => 2,
                        'association_type' => $type));
            for($i = count($websites); $i < 2; $i++)
            {
                array_push($websites, array('ContactMethod' =>
                        array('name' => '', 'detail' => '')));
            }
            $caller->set('websites', $websites);
        }

        public static function save_contacts(
            $params, $id, $caller_type, $caller)
        {
            $caller->ContactMethod->deleteAll(
                array('associated_with_id' => $id,
                  'association_type' => $caller_type));

            $types = array('phone', 'email', 'website');

            // Now save all the contact methods
            for($i = 0; $i < 50; $i++)
            {
                foreach($types as $index=>$type) :
                if(!empty($params['form'][$type.$i]))
                {
                    if($params['form'][$type.$i] != '')
                    {
                        $cm = array();
                        $cm['ContactMethod'] = array();
                        $cm['ContactMethod']['detail'] =
                        $caller->params['form'][$type.$i];
                        $cm['ContactMethod']['id'] = false;
                        $cm['ContactMethod']['name'] =
                        $caller->params['form'][$type . '_select' . $i];
                        $cm['ContactMethod']['type'] = $index;
                        $cm['ContactMethod']['associated_with_id'] = $id;
                        $cm['ContactMethod']['association_type'] =
                        $caller_type;
                        $caller->ContactMethod->save($cm);
                    }
                }
                endforeach;
            }
        }

        private function get_company_id($name)
        {
            if($name == '' || $name == 'Company name')
              return null;

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $comp = $this->Company->findAll(
                        "lower(name) LIKE '{$name}%' AND Company.creator_id IN "
                . $users);

            if(count($comp) == 0)
            {
                $cm = array();
                $cm['Company'] = array();
                $cm['Company']['name'] = $name;
                $cm['Company']['creator_id'] =
                $this->logged_on_user_id();
                $cm['Company']['account_id'] =
                $this->logged_on_account_id();
                $this->Company->save($cm);
                return $this->Company->id;
            }
            else
            {
                return $comp[0]['Company']['id'];
            }
        }

        private function people_secure_read($id)
        {
            $users = AdminController::extract_users($this,
                $this->logged_on_group_id());

            $obj = $this->Person->find(array('Person.id' => $id,
                        'Person.creator_id' => $users));

            if(empty($obj))
            {
                $this->redirect('/admin/security');
                exit;
            }
            return $obj;
        }
    }
?>
