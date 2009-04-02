<?php
    class BaseController extends AppController
    {
        function beforeFilter()
        {
            parent::beforeFilter(); // call the AppController::beforeFilter()

            if($this->Session->check('User') == false)
            {
                $this->redirect('/sessions');
                exit();
            }

            $this->set('logged_on_user', $this->Session->read('User'));
        }

        function beforeRender()
        {
            $this->set('lrutabs', $this->retrieve_links());
        }


        function is_administrator()
        {
            $logged_on_user = $this->Session->read('User');
            return $logged_on_user['administrator'] == 1;
        }

        function logged_on_user_id()
        {
            $logged_on_user = $this->Session->read('User');
            return $logged_on_user['id'];
        }

        function logged_on_account_id()
        {
            $logged_on_user = $this->Session->read('User');
            return $logged_on_user['account_id'];
        }

        function logged_on_group_id()
        {
            $logged_on_user = $this->Session->read('User');
            return $logged_on_user['group_id'];
        }

        function cache_link($link, $name)
        {
            App::import('Helper', 'Html');
            $html = new HtmlHelper();
            $link = $html->url($link);

            $lnks = $this->retrieve_links();

            $links = array();
            foreach($lnks as $key => $linker)
            {
                if(strcmp($linker[0], $link) != 0)
                  array_push($links, $linker);
            }

            if(count($links) == 4)
              array_shift($links);

            if(strlen($name) > 12)
              $name = substr($name, 0, 12) . '...';
            $tab = array($link, $name);
            array_push($links, $tab);

            $this->store_links($links);
        }

        private function store_links($links)
        {
            $person_id = $this->logged_on_user_id();

            $this->Lru->deleteAll(
                array('person_id' => $person_id));

            foreach($links as $link)
            {
              $lru = array();
              $lru['Lru'] = array();
              $lru['Lru']['id'] = null;
              $lru['Lru']['value'] = $link[0];
              $lru['Lru']['name'] = $link[1];
              $lru['Lru']['person_id'] = $person_id;
              $this->Lru->save($lru);
            }
        }

        public static function get_db_prefix()
        {
            $db_config = new DATABASE_CONFIG();
            return $db_config->default['prefix'];
        }

        function retrieve_links()
        {
            $person_id = $this->logged_on_user_id();
            $obj = $this->Lru->findAll(array('person_id' => $person_id));

            $links = array();
            foreach($obj as $link)
            {
                $l = $link['Lru']['value'];
                $name = $link['Lru']['name'];
                if(strlen($name) > 12)
                  $name = substr($name, 0, 12) . '...';

                $one = array();
                $one[0] = $l;
                $one[1] = $name;
                array_push($links, $one);
            }

            return $links;
        }

        function remove_link($link)
        {
            App::import('Helper', 'Html');
            $html = new HtmlHelper();
            $link = $html->url($link);

            $this->Lru->deleteAll(array('value' => $link));
        }
    }
?>
