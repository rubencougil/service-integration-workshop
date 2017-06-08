<?php


namespace ConferenceManagement;


class ConferenceCreated
{
    private const DATE_TIME_FORMAT = \DateTime::ATOM;

    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var \DateTimeImmutable
     */
    private $start;
    /**
     * @var \DateTimeImmutable
     */
    private $end;

    private $city;

    /**
     * @param string $id
     * @param string $name
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable $end
     * @param $city
     */
    public function __construct($id, $name, $start, $end, $city)
    {
        $this->id = $id;
        $this->name = $name;
        $this->start = $start;
        $this->end = $end;
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    /**
     * @return \DateTimeImmutable
     */
    public function getStart(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->start);
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEnd(): \DateTimeImmutable
    {
        return \DateTimeImmutable::createFromFormat(self::DATE_TIME_FORMAT, $this->end);
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }


}
