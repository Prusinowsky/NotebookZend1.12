<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function showAction()
    {
        $note_model = new Application_Model_DbTable_Notes();
        $notes = $note_model->fetchAll();
        $this->view->notes = $notes;

    }

    public function addAction()
    {
        $form = new Application_Form_Note();
        $form->setAction(
            $this->view->url(
                array('controller' => 'index', 'action' => 'create')
            )
        );
        $form->submit->setLabel('Utworz');
        $this->view->form = $form;
    }

    public function createAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Note();
        if($form->isValid($request->getPost())) {
            $note = new Application_Model_DbTable_Notes();
            $title = $request->getParam('title');
            $content = $request->getParam('content');
            $note->addNote(array('title' => $title, 'content' => $content));
            $this->redirect('/index/show');
        } else {
            $this->view->form = $form;
            $form->populate($request->getPost());
            $this->_helper->viewRenderer('add');
        }
    }

    public function editAction()
    {
        $id = (int)$this->getParam('id');
        $note = new Application_Model_DbTable_Notes();
        $note = $note->getNote($id);
        $form = new Application_Form_Note();
        $form->setAction(
            $this->view->url(
                array('controller' => 'index', 'action' => 'update')
            )
        );
        $form->populate($note);
        $form->submit->setLabel('Zapisz zmiany');
        $this->view->form = $form;
    }

    public function updateAction()
    {
        $request = $this->getRequest();
        $form = new Application_Form_Note();
        if($form->isValid($request->getPost())) {
            $note = new Application_Model_DbTable_Notes();
            $id = $request->getParam('id');
            $title = $request->getParam('title');
            $content = $request->getParam('content');
            $note->updateNote($id,array('title' => $title, 'content' => $content));
            $this->redirect('/index/show');
        } else {
            $this->view->form = $form;
            $form->populate($request->getPost());
            $this->_helper->viewRenderer('edit');
        }
    }

    public function deleteAction()
    {
        if((int)$this->_getParam('id') === null)
            $this->redirect('/index/show');

        $id = (int)$this->_getParam('id');
        $note = new Application_Model_DbTable_Notes();
        $note->deleteNote($id);
        $this->redirect('/index/show');
    }

    public function googleCalendarAction()
    {
        $client = new Google_Client();
        $client->setApplicationName("Google Calendar Test");
        $client->setDeveloperKey("AIzaSyAQJu3aLJhQWXCP4hYenCtMEsF2wkPebTI");
        $service = new Google_Service_Calendar($client);
        $calendarId = 'nq5cct77rbgdnihmd5gfhrtbks@group.calendar.google.com';
        $optParams = array(
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => date('c'),
        );
        $results = $service->events->listEvents($calendarId, $optParams);
        $events = $results->getItems();

        $this->view->events = $events;
    }


}















