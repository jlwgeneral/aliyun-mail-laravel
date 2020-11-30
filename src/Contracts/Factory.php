<?php

namespace JlwGeneral\AliyunMailLaravel\Contracts;

interface Factory
{
    /**
     * @param array $args
     * @return mixed
     */
    public function send(array $args);

}
