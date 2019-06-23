<?php

namespace App\Session;

class Flash
{
    /**
     * @var SessionStoreInterface
     */
    protected $session;

    /**
     * @var array
     */
    protected $messages;

    public function __construct(SessionStoreInterface $session)
    {
        $this->session = $session;
        $this->loadFlashMessagesIntoCache();

        $this->clear();
    }

    /**
     * Set session with flash message.
     *
     * @param  string  $key
     * @param  string  $value
     */
    public function now(string $key, string $value): void
    {
        $this->session->set('flash', array_merge(
            $this->session->get('flash') ?? [], [$key => $value]
        ));
    }

    /**
     * Checking if exists message with given key.
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->messages[$key]);
    }

    /**
     * Get flash message for given key.
     *
     * @param  string  $key
     * @return string
     */
    public function get(string $key): string
    {
        if ($this->has($key)) {
            return $this->messages[$key];
        }
    }

    /**
     * Get all flash messages.
     *
     * @return array|null
     */
    public function getAll(): ?array
    {
        return $this->session->get('flash');
    }

    /**
     * Setting message propertie with flash messages from session.
     */
    protected function loadFlashMessagesIntoCache(): void
    {
        $this->messages = $this->getAll();
    }

    /**
     * Delete flash session.
     */
    protected function clear(): void
    {
        $this->session->clear(['flash']);
    }
}
