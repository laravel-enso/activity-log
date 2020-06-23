<?php

namespace LaravelEnso\ActivityLog\Contracts;

use Illuminate\Database\Eloquent\Model;

interface Loggable
{
    public function type(): int;

    public function model(): Model;

    public function message();

    public function icon(): string;

    public function iconClass(): string;
}
