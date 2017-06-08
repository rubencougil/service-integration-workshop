<?php

namespace ConferenceManagement;

class CreateConference
{
    /**
     * @var string
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var \DateTimeImmutable
     */
    public $start;
    /**
     * @var \DateTimeImmutable
     */
    public $end;

    public $city;
}
