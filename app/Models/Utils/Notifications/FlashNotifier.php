<?php

namespace App\Models\Utils\Notifications;

use Illuminate\Session\Store;

class FlashNotifier
{
    private $session;

    protected $notification = [
        'title' => null,
        'type' => 'alert-info',
        'modal' => null,
        'overlay' => '',
        'message' => '',
    ];

    public function __construct(Store $session)
    {
        $this->session = $session;
    }

    public function success($message, $title = null)
    {
        $this->notification['type'] = 'alert alert-success';
        $this->flashIt($message, $title);
    }

    public function info($message, $title = null)
    {
        $this->notification['type'] = 'alert alert-info';
        $this->flashIt($message, $title);
    }

    public function warning($message, $title = null)
    {
        $this->notification['type'] = 'alert alert-warning';
        $this->flashIt($message, $title);
    }

    public function error($message, $title = null)
    {
        $this->notification['type'] = 'alert alert-danger';
        $this->flashIt($message, $title);
    }

    public function overlay()
    {
        $this->notification['overlay'] = 'overlay ';
        return $this;
    }

    protected function flashIt($message, $title = null)
    {
        $this->notification['message'] = $this->normalizeMessage($message);
        $this->notification['title'] = $title;
        $this->session->flash('flash_notification', $this->notification);
    }

    protected function normalizeMessage($message)
    {
        if (is_array($message)) {
            $message = '<ul><li>' . implode('</li><li>', $message) . '</li>';
        }
        return $message;
    }
}
