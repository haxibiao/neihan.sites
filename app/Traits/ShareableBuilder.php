<?php
namespace App\Traits;

use App\Share;
use Illuminate\Support\Carbon;
use Ramsey\Uuid\Uuid;

class ShareableBuilder
{
    private $model;

    private $active;


    private $expirationDate = null;

    private $url;

    private $user_id = null;

    public function __construct($model)
    {
        $this->model = $model;
    }

    public function setActive($active): self
    {
        $this->active = $active;

        return $this;
    }

    public function setExpirationDate(Carbon $date): self
    {
        $this->expirationDate = $date;

        return $this;
    }

    public function setUserId($userId): self
    {
        $this->user_id = $userId;

        return $this;
    }

    public function setUrl($url): self
    {
        $this->url = $url;

        return $this;
    }

    public function build()
    {
        $uuid = Uuid::uuid4()->getHex();

        $share = new Share([
            'active'    => $this->active,
            'expired_at' => $this->expirationDate,
            'uuid'      => $uuid,
            'url'       => $this->url,
            'user_id'   => $this->user_id,
        ]);
        return $this->model->shares()->save($share);
    }
}