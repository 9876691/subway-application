<?php
    include_once "base_controller.php";
    include_once "vcard.php";

    uses('sanitize');

    class AdminController extends BaseController
    {
        var $uses = array('Group', 'Person', 'Account', 'Company',
                'ContactMethod', 'Address', 'Lru');
        var $helpers = array('Html','Form', 'Javascript','Ajax');
        var $components = array('RequestHandler');

        private static $cache = array();

        function groups()
        {
            $this->set('tab', 'none');

            $this->populate_list_screen();
        }

        function security()
        {
            $users = AdminController::get_users_from_group_and_subgroups(
                $this, $this->logged_on_group_id());

            $this->layout = "ajax";
        }

        function group($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'none');

            $this->Group->id = $id;
            $this->set('group', $this->Group->read());

            $this->populate_user_list($id);
        }

        function import()
        {
            $this->set('tab', 'none');

            if(! empty($this->params['form'])
                && is_uploaded_file($this->params['form']['File']['tmp_name']))
            {
                $mr_clean = new Sanitize();
                $this->params = $mr_clean->clean($this->params);

                $cards = $this->parse_vcards(
                    file($this->params['form']['File']['tmp_name']));

                foreach ($cards as $card_name => $card) {

                    $cm = array();
                    $cm['Person'] = array();

                    $names = $this->punch_into_array(
                        $card, 'N', array('surname', 'first_name'));
                    if(!empty($names))
                    $cm['Person'] = array_merge($cm['Person'], $names);

                    $title = $this->punch_into_array(
                        $card, 'TITLE', array('title'));
                    if(!empty($title))
                    $cm['Person'] = array_merge($cm['Person'], $title);

                    $cm['Person']['company_id'] =
                    $this->create_company($card);

                    $cm['Person']['creator_id'] =
                    $this->logged_on_user_id();
                    $cm['Person']['account_id'] =
                    $this->logged_on_account_id();

                    $showas = $card->getProperty('X-ABSHOWAS');
                    if(empty($showas))
                    $this->Person->save($cm);

                    $this->create_contact_methods($card, $this->Person->id);

                    $this->create_addresses($card, $this->Person->id);

                    $this->Person->id = null;
                    $this->Company->id = null;
                }

                $this->redirect('/people/');
                exit;
            }
        }

        function addgroup()
        {
            if(! empty($this->params['form']['name']))
            {
                $mr_clean = new Sanitize();
                $this->params = $mr_clean->clean($this->params);

                $group = array();
                $group['Group'] = array();
                $group['Group']['name'] = $this->params['form']['name'];
                if(!empty($this->params['data']['parent_id']))
                $group['Group']['parent_id'] =
                $this->params['data']['parent_id'];
                $group['Group']['account_id'] = $this->logged_on_account_id();
                $group['Group']['creator_id'] = $this->logged_on_user_id();

                $this->Group->save($group);
            }

            $this->populate_list_screen();
            $this->redirect('/admin/groups/');
        }

        function moveuser($group_id)
        {
            $mr_clean = new Sanitize();
            $group_id = $mr_clean->paranoid($group_id);

            $this->layout = "ajax";

            if(! empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);

                $id = $this->data['Person']['id'];

                $this->Person->query(
                  "UPDATE ". BaseController::get_db_prefix() .
                  "people SET group_id = {$group_id} WHERE" .
                  " id = {$id}");
                $this->populate_user_list($group_id);
            }


            if(! $this->RequestHandler->isAjax())
            {
                $this->redirect('/admin/group/' . $group_id, true);
            }
        }

        function editgroup($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->set('tab', 'none');

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);

                $this->Group->id = $id;
                $this->Group->read();
                if ($this->Group->save($this->data))
                {
                    $this->redirect('/admin/groups/');
                }
            }
            else
            {
                $this->Group->id = $id;
                $this->data = $this->Group->read();
                $this->set('selected', $this->data['Group']['parent_id']);
                $this->populate_list_screen();
            }
        }

        function deletegroup($id)
        {
            $mr_clean = new Sanitize();
            $id = $mr_clean->paranoid($id);

            $this->Group->query(
                        "DELETE FROM groups WHERE id = {$id}");
            $this->redirect('/admin/groups/', true);
        }

        function settings()
        {
            $this->set('tab', 'none');
            $this->Account->id = $this->logged_on_account_id();

            if (!empty($this->data))
            {
                $mr_clean = new Sanitize();
                $this->data = $mr_clean->clean($this->data);

                $this->Account->save($this->data);
                $acc = $this->Account->read();
                $this->Session->write('Account', $acc['Account']);
                $this->redirect('/dashboard/');
                exit;
            }
            else
            {
                $this->data = $this->Account->read();
            }
        }

        function users()
        {
            $this->set('tab', 'none');
            $account_id = $this->logged_on_account_id();
            $users = $this->Person->findAll(
                "Person.account_id = {$account_id} AND username IS NOT NULL");
            $this->set('people', $users);
        }

        public static function extract_users_string($the_view, $group_id)
        {
            $users = AdminController::extract_users($the_view, $group_id);

            return "(" . implode(",", $users) . ")";
        }

        public static function extract_all_users($the_view)
        {
            $users = array();

            $people = $the_view->Person->findAll();

            foreach($people as $person)
              array_push($users, $person['Person']['id']);

            return $users;
        }

        private static function get_access($the_view)
        {
            $account = $the_view->Person->query('SELECT Account.* ' .
              'FROM '. BaseController::get_db_prefix() . 'accounts Account ' .
              'WHERE id = ' . $the_view->logged_on_account_id());
            return $account[0]['Account']['security_level'];
        }

        public static function extract_users($the_view, $group_id)
        {
            $access = AdminController::get_access($the_view);

            if($access == 'high') {
                $users = AdminController::get_users_from_group_and_subgroups(
                    $the_view, $group_id);

                $people = $the_view->Person->findAll(array('group_id'
                        => $group_id));

                foreach($people as $person)
                  array_push($users, $person['Person']['id']);
            } else {
                $users = AdminController::extract_all_users(
                    $the_view);
            }

            return $users;
        }

        public static function can_edit($the_view, $group_id, $creator_id)
        {
            $access = AdminController::get_access($the_view);

            if($access == 'low')
              return true;

            $users = AdminController::get_users_from_group_and_subgroups(
                $the_view, $group_id);

            $people = $the_view->Person->findAll(array('group_id'
                    => $group_id));

            foreach($people as $person)
              array_push($users, $person['Person']['id']);

            foreach($users as $user)
            {
                if($user == $creator_id)
                  return true;
            }

            return false;
        }

        public static function get_users_from_group_and_subgroups(
            $the_view, $group_id = -1)
        {
            $users = array();

            $groups = $the_view->Group->findAll(
                array('parent_id' => $group_id));

            foreach($groups as $group)
            {
                $people = $the_view->Person->findAll(array('group_id'
                        => $group['Group']['id']));

                foreach($people as $person)
                  array_push($users, $person['Person']['id']);

                $sub_users = 
                  AdminController::get_users_from_group_and_subgroups($the_view,
                    $group['Group']['id']);

                foreach($sub_users as $user)
                  array_push($users, $user);
            }
            return $users;
        }

        private function populate_user_list($id)
        {
            $this->set('people', $this->Person->findAll(
                "group_id = {$id} AND username IS NOT NULL"));

            $us = $this->Person->findAll(
                "(group_id <> {$id} OR group_id IS NULL) AND username IS NOT NULL");
            $users = array();
            if(!empty($us))
            {
                foreach($us as $user)
                {
                    $users[$user['Person']['id']] =
                    $user['Person']['first_name']
                    . ' ' . $user['Person']['surname'];
                }
            }

            $this->set('users', $users);
        }

        private function populate_list_screen()
        {
            $groups = $this->Group->findAll(
                array('account_id' => $this->logged_on_account_id()));

            $hierarchy = $this->parse_groups($groups);

            $this->set('hierarchy', $hierarchy);

            $grp = array();
            foreach($groups as $group)
            {
                $grp[$group['Group']['id']] = $group['Group']['name'];
            }
            $this->set('groups', $grp);
        }

        private function parse_groups($list, $parent_id = -1)
        {
            $groups = array();

            foreach($list as $key => $group)
            {
                if((empty($group['Group']['parent_id']) && $parent_id == -1)
                    || ($group['Group']['parent_id'] == $parent_id))
                {
                    $group['Group']['children'] =
                    $this->parse_groups($list, $group['Group']['id']);

                    array_push($groups, $group);
                }
            }
            return $groups;
        }

        private function create_company($card)
        {
            $prop = $card->getProperty('ORG');

            if(empty($prop))
            return null;

            $comp = $prop->getComponents();

            $cm = array();
            $cm['Company'] = array();
            $cm['Company']['name'] = $comp[0];
            $cm['Company']['creator_id'] =
            $this->logged_on_user_id();
            $cm['Company']['account_id'] =
            $this->logged_on_account_id();

            $users = AdminController::extract_users_string($this,
                $this->logged_on_group_id());

            $name = strtolower($cm['Company']['name']);
            $companies = $this->Company->findAll(
                        "lower(name) = '{$name}' AND Company.creator_id IN "
                . $users);
            if(empty($companies))
            {
                $this->Company->save($cm);
            }
            else
            {
                return $companies[0]['Company']['id'];
            }

            return $this->Company->id;
        }

        private function create_addresses($card, $id)
        {
            $props = $card->getProperties('ADR');

            if(empty($props))
            return;

            for ($x = 0; $x < count($props); $x++) {

                $cm = array();
                $cm['ADDRESS'] = array();

                $comps = $props[$x]->getComponents();

                $cm['Address']['id'] = false;

                if(!empty($props[$x]->params['TYPE'][0]))
                $cm['Address']['name'] = ucwords(
                    strtolower($props[$x]->params['TYPE'][0]));
                else
                $cm['Address']['name'] = 'Home';

                $street = '';
                $street = $this->add_property($street, $comps[0]);
                $street = $this->add_property($street, $comps[1]);
                $street = $this->add_property($street, $comps[2]);
                $cm['Address']['street'] = $street;

                if(!empty($comps[3]))
                $cm['Address']['city'] = $comps[3];
                if(!empty($comps[4]))
                $cm['Address']['state'] = $comps[4];
                if(!empty($comps[5]))
                $cm['Address']['zip'] = $comps[5];
                if(!empty($comps[6]))
                $cm['Address']['country'] = $comps[6];

                $cm['Address']['associated_with_id'] = $id;
                $cm['Address']['association_type'] = 0;
                $this->Address->save($cm);
            }
        }

        private function add_property($s, $prop)
        {
            if(! empty($prop))
            {
                if(!empty($s))
                $s .= "\n";
                $s .= $prop;
            }
            return $s;
        }

        private function create_contact_methods($card, $id)
        {
            $methods = array('TEL', 'EMAIL');

            for ($i = 0; $i < count($methods); $i++) {

                $props = $card->getProperties($methods[$i]);

                if(empty($props))
                continue;

                for ($x = 0; $x < count($props); $x++) {

                    $cm = array();
                    $cm['ContactMethod'] = array();
                    $cm['ContactMethod']['detail'] = $props[$x]->value;
                    $cm['ContactMethod']['id'] = false;
                    if(!empty($props[$x]->params['TYPE'][0]))
                    $cm['ContactMethod']['name'] = ucwords(
                        strtolower($props[$x]->params['TYPE'][0]));
                    else
                    $cm['ContactMethod']['name'] = $methods[$i];
                    $cm['ContactMethod']['type'] = $i;
                    $cm['ContactMethod']['associated_with_id'] = $id;
                    $cm['ContactMethod']['association_type'] = 0;
                    $this->ContactMethod->save($cm);
                }
            }
        }

        private function punch_into_array($card, $property, $items)
        {
            $object = array();

            $props = $card->getProperties($property);
            if(empty($props))
            return;

            $comps = $props[0]->getComponents();
            if ($comps) {

                for ($i = 0; $i < count($items); $i++) {
                    if(!empty($comps[$i]))
                    $object[$items[$i]] = $comps[$i];
                }
            }

            return $object;
        }

        private function parse_vcards($lines)
        {
            $cards = array();
            $card = new VCard();
            while ($card->parse($lines)) {
                $property = $card->getProperty('N');
                if (!$property) {
                    return "";
                }
                $n = $property->getComponents();
                $tmp = array();
                if (!empty($n[3]) && $n[3]) $tmp[] = $n[3];      // Mr.
                if (!empty($n[1]) && $n[1]) $tmp[] = $n[1];      // John
                if (!empty($n[2]) && $n[2]) $tmp[] = $n[2];      // Quinlan
                if (!empty($n[4]) && $n[4]) $tmp[] = $n[4];      // Esq.
                $ret = array();
                if ($n[0]) $ret[] = $n[0];
                $tmp = join(" ", $tmp);
                if ($tmp) $ret[] = $tmp;
                $key = join(", ", $ret);
                $cards[$key] = $card;
                // MDH: Create new VCard to prevent overwriting previous one (PHP5)
                $card = new VCard();
            }
            ksort($cards);
            return $cards;
        }
    }
?>
