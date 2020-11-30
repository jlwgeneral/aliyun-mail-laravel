<?php

namespace JlwGeneral\AliyunMailLaravel\Contracts\AliyunMail;

interface Factory
{
    /**
     * @param array $args
     * @return mixed
     */
    public function send(array $args);

}
