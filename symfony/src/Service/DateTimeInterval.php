<?php

namespace App\Service;

class DateTimeInterval
{
    const H1 = 'P1H';
    const H12 = 'P12H';
    const D1 = 'P1D';
    const W1 = 'P7D';
    const M1 = 'P1M';
    const M6 = 'P6M';
    const Y1 = 'P1Y';

    private $datetime;

    /**
     * DateTimeInterval constructor.
     * @param string $timezone in DateTimeZone class format
     */
    public function __construct(string $timezone = 'Europe/Minsk')
    {
        $this->datetime = new \DateTime('now', new \DateTimeZone($timezone));
    }

    /**
     * @param int $option
     * @return \DateTime
     */
    public function addTime(int $option): \DateTime
    {
        switch ($option) {
            case 1:
                return $this->datetime->add(new \DateInterval(self::H1));
            case 2:
                return $this->datetime->add(new \DateInterval(self::H12));
            case 3:
                return $this->datetime->add(new \DateInterval(self::D1));
            case 4:
                return $this->datetime->add(new \DateInterval(self::W1));
            case 5:
                return $this->datetime->add(new \DateInterval(self::M1));
            case 6:
                return $this->datetime->add(new \DateInterval(self::M6));
            case 7:
                return $this->datetime->add(new \DateInterval(self::Y1));
        }
    }
}