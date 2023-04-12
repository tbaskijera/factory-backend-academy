<?php

namespace App\Models;

use Timestamp;
use App\Models\Model;

class User extends Model
{
    use Timestamp;

    public function save(): void
    {
        $this->setTimestamps(); // set created_at and updated_at timestamps
        return parent::save();
    }

    public function update(): void
    {
        $this->setUpdatedAt();
        return parent::save();
    }

    public function softDelete(): void
    {
        $this->setDeletedAt();
        return parent::softDelete();
    }
}
