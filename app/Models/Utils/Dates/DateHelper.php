<?php

namespace App\Models\Utils\Dates;

use DateTime;

/**
 * Class DateHelper.
 * @property      int $year
 * @property      int $month
 * @property      int $day
 * @property      int $hour
 * @property      int $minute
 * @property      int $second
 * @property      int $timestamp seconds since the Unix Epoch
 * @property-read int $micro
 * @property-read int $dayOfWeek 0 (for Sunday) through 6 (for Saturday)
 * @property-read int $dayOfYear 0 through 365
 * @property-read int $weekOfMonth 1 through 6
 */
class DateHelper extends DateTime
{
    /**
     * The day constants.
     */
    public const SUNDAY = 0;
    public const MONDAY = 1;
    public const TUESDAY = 2;
    public const WEDNESDAY = 3;
    public const THURSDAY = 4;
    public const FRIDAY = 5;
    public const SATURDAY = 6;

    /**
     * Format constants.
     */
    public static $FORMATS = [
        'year' => 'Y',
        'month' => 'n',
        'day' => 'j',
        'hour' => 'G',
        'minute' => 'i',
        'second' => 's',
        'micro' => 'u',
        'dayOfWeek' => 'w',
        'dayOfYear' => 'z',
        'weekOfYear' => 'W',
        'daysInMonth' => 't',
        'timestamp' => 'U',
    ];

    /**
     * Names of days of the week.
     *
     * @var array
     */
    protected static $DAYS = [
        self::SUNDAY => 'Domingo',
        self::MONDAY => 'Lunes',
        self::TUESDAY => 'Martes',
        self::WEDNESDAY => 'Miercoles',
        self::THURSDAY => 'Jueves',
        self::FRIDAY => 'Viernes',
        self::SATURDAY => 'Sabado',
    ];

    protected static $MONTHS = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public function __construct($date = 'now', $timeZone = null)
    {
        parent::__construct($date, $timeZone);
    }

    public static function create($date = 'now', $timeZone = null)
    {
        return new static($date, $timeZone);
    }

    public static function toLegalDate($date = 'now')
    {
        return self::create($date)->translateToLegalDate();
    }

    public static function toHumanDate($date = 'now')
    {
        return self::create($date)->translateToHumanDate();
    }

    public static function toYearMonth($date = 'now')
    {
        return self::create($date)->translateToYearMonth();
    }

    public static function getDifference($fromDate, $toDate = 'now')
    {
        return self::create($fromDate)->toDifferenceWith($toDate);
    }

    public function toDifferenceWith($toDate = 'now')
    {
        return new DateDifference($this->diff(new static($toDate)));
    }

    public function translateToShortDate()
    {
        return $this->format('d/m/Y');
    }

    public function translateToHumanDate()
    {
        return self::$DAYS[$this->dayOfWeek] . ', ' . $this->day . ' de ' . self::$MONTHS[$this->month - 1] . ' de ' . $this->year;
    }

    public function translateToLegalDate()
    {
        return $this->day . ' días, del mes de ' . self::$MONTHS[$this->month - 1] . ' del año ' . $this->year;
    }

    public function translateToYearMonth()
    {
        return self::$MONTHS[$this->month - 1] . '/' . $this->year;
    }

    public function translateToTime()
    {
        return $this->format('H:i');
    }

    public function toSQLDate()
    {
        return $this->format('Y-m-d');
    }

    public function toSQLTimestamp()
    {
        return $this->format('Y-m-d H:i:s');
    }

    public function toSQLReport($endOfDay = false)
    {
        if ($endOfDay) {
            return $this->format('Y-m-d') . ' 23:59:59';
        }
        return $this->format('Y-m-d') . ' 00:00:00';
    }

    public function changeDays($numberOfDaysWithSign)
    {
        return $this->modify($numberOfDaysWithSign . ' days');
    }

    public function changeMonths($numberOfMonthsWithSign)
    {
        $this->modify($numberOfMonthsWithSign . ' months');
        return $this;
    }

    public function __get($name)
    {
        if (array_key_exists($name, self::$FORMATS)) {
            return $this->format(self::$FORMATS[$name]);
        }
        throw new \Exception('No existe el parametro que ingresó');
    }
}
