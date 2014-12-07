<?php namespace SaleBoss\Models;

use Miladr\Jalali\jDate;

trait DateTrait {

	/**
	 * Return a date in ago format
	 *
	 * @param string $column
	 * @return string
	 */
	public function diff($column = 'created_at')
	{
		return \jDate::forge($this->$column)->ago();
	}

    /**
     * Jalali date
     *
     * @param $attr
     * @return string
     */
    public function jalaliDate($attr)
    {
        if (is_null($this->$attr))
        {
            return $this->$attr;
        }
        $timestamp = strtotime($this->$attr);
        return jDate::forge($timestamp)->format('date');
    }

    /**
     * Jalali date English Numbers
     *
     * @param $attr
     * @return string
     */
    public function jalaliDateEnglishNumbers($attr)
    {
        if (is_null($this->$attr))
        {
            return $this->$attr;
        }
        $timestamp = strtotime($this->$attr);
        $date = jDate::forge($timestamp)->format('date');

        return $this->convertNumbers($date);
    }

    /**
     * Jalali time date
     *
     * @param $attr
     * @return string
     */
    public function jalaliTimeDate($attr)
    {
        if (is_null($this->$attr))
        {
            return $this->$attr;
        }
        $timestamp = strtotime($this->$attr);
        return jDate::forge($timestamp)->format('datetime');
    }

    /**
     * Jalali date with ago format
     *
     * @param $attr
     * @return string
     */
    public function jalaliAgoDate($attr)
    {
        if (is_null($this->$attr))
        {
            return $this->$attr;
        }
        $timestamp = strtotime($this->$attr);
        return jDate::forge($timestamp)->ago();
    }

    private static function convertNumbers($matches)
    {
        $farsi_array = array("۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹");
        $english_array = array("0", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        return str_replace($farsi_array, $english_array, $matches);
    }
} 