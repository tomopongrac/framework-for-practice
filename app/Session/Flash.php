<?php

namespace App\Session;

class Flash
{
    protected $session;

    protected $messages;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
        $this->loadFlashMessagesIntoCache();

        $this->clear();
    }

    public function now($key, $value)
    {
        $this->session->set('flash', array_merge(
            $this->session->get('flash') ?? [], [$key => $value]
        ));
    }

    public function has($key)
    {
        return isset($this->messages[$key]);
    }

    public function get($key)
    {
        if ($this->has($key)) {
            return $this->messages[$key];
        }
    }

    public function getAll()
    {
        return $this->session->get('flash');
    }

    protected function loadFlashMessagesIntoCache()
    {
        $this->messages = $this->getAll();
    }

    protected function clear()
    {
        $this->session->clear(['flash']);
    }
}
