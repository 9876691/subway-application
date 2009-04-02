<?php

uses('sanitize');

class SessionsController extends AppController
{
    var $uses = array('Person', 'Account');
    var $helpers = array('Html','Javascript','Ajax','Form');
    var $layout = 'marketing';
	
    function validateLogin($data)
    {
        $user = $this->Person->find(array('username' => $data['username'],
			'password' => md5($data['password'])));
        if(empty($user) == false)
            return $user['Person'];
        return false;
    }

    function register($id, $id2)
    {
        $user = $this->Person->find(array('Person.id' => $id2));
        if($user == null)
          $this->redirect('index', null, true);

        if(! empty($_GET['error']))
        {
            $error = $_GET['error'];
            $message = array();
            if(($error / 4) >= 1)
            {
              array_push($message, 'Invalid invitation code.');
              $error = $error -4;
            }
            if(($error / 2) >= 1)
            {
              array_push($message, 'Invalid password or passwords are not the same.');
              $error = $error -2;
            }

            if(($error / 1) >= 1)
              array_push($message, 'Invalid or already taken username.');

            $this->set('errors', $message);
        }
        else
            $this->set('errors', NULL);

        $this->set('user', $user);
        $this->set('code', $id);
    }

    function accept()
    {
        if(!empty($this->params['form']))
        {
            $username = $this->params['form']['username'];
            $password = $this->params['form']['password'];
            $password1 = $this->params['form']['password1'];
            $code = $this->params['form']['code'];
            $id = $this->params['form']['id'];

            $user = $this->Person->find(array('Person.id' => $id));

            $error = 0;
            $check_username = $this->Person->find(array('Person.username' => $username));
            if($check_username != null or strlen($username) == 0)
              $error = $error + 1;
            if($password != $password1 or strlen($password) == 0)
              $error = $error + 2;
            if($code != $user['Person']['invitation'])
              $error = $error + 4;

            if($error != 0)
                $this->redirect('/sessions/register/'. $code . '/' . $id .
                '/?error=' . $error,
                    null, true);

            $this->Person->id = $id;
            $this->Person->read();

            $this->data['Person']['username'] = $username;
            $this->data['Person']['password'] = md5($password);

            $this->Person->save($this->data);
        }
    }
    
    function index()
    {
        if(!empty($this->params['form']))
        {
        	$mr_clean = new Sanitize();
        	$this->params = $mr_clean->clean($this->params);
        	
            if(($user = $this->validateLogin(
            	$this->params['form'])) == true)
            {
                $this->Session->write('User', $user);
                $this->Account->id = $user['account_id'];
                $acc = $this->Account->read();
                $this->Session->write('Account', $acc['Account']);
                $this->redirect('/dashboard', null, true);
            }
            else
            {
                $this->Session->setFlash('Sorry, the information '
                	. 'you\'ve entered is incorrect.');
                $this->redirect('index', null, true);
            }
        }
        
        $this->layout = 'marketing';
    }
    
    function logout()
    {
        $this->Session->destroy('user');
        $this->Session->setFlash('You\'ve successfully logged out.');
        $this->redirect('/');
        exit();
    }
}
?>
