<?php

trait Timestamp
{
    public function setTimestamps(): void
    {
        $this->created_at = $this->getTimestamp();
        $this->updated_at = $this->getTimestamp();
    }

    public function setUpdatedAt(): void
    {
        $this->updated_at = $this->getTimestamp();
    }

    private function getTimestamp(): string
    {
        return date('Y-m-d H:i:s');
    }
}
