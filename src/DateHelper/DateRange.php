<?php

declare(strict_types=1);

namespace Doe\ToolHelper\DateHelper;

use Exception;

class DateRange
{
    private static string $format = 'Y-m-d H:i:s';

    private static int $toTime = 0;

    /**
     * 获取时间区间
     * @param string $type day week month year
     * @return array
     */
    public function getDateRange(string $type = 'day'): array
    {
        $toTime = self::$toTime ?: time();
        switch ($type) {
            case 'week':
                $start = date(self::$format, strtotime('monday this week', $toTime));
                $end = date(self::$format, strtotime('sunday this week', $toTime));
                break;
            case 'month':
                $start = date('Y-m-01 00:00:00', $toTime);
                $end = date('Y-m-t 23:59:59', $toTime);
                break;
            case 'year':
                $start = date('Y-01-01 00:00:00', $toTime);
                $end = date('Y-12-31 23:59:59', $toTime);
                break;
            default:
                $start = date('Y-m-d 00:00:00', $toTime);
                $end = date('Y-m-d 23:59:59', $toTime);
        }

        return [
            'start_date' => $start,
            'end_date' => $end,
            'start_time' => strtotime($start),
            'end_time' => strtotime($end),
        ];
    }

    public function setFormat(string $format = 'Y-m-d H:i:s'): self
    {
        self::$format = $format;
        return $this;
    }

    public function setToTime(int $toTime = 0): self
    {
        self::$toTime = $toTime;
        return $this;
    }

    /**
     * 时间戳人性化转化
     * @param int $time
     * @return string
     */
    public function toTimeTran(int $time): string
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v . '前';
            }
        }
        return '刚刚';
    }

    /**
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        throw new Exception('not found method');
    }

    /**
     * @throws Exception
     */
    public static function __callStatic($method, $arguments)
    {
        $method = str_replace('_call', '', $method);
        try {
            return (new static())->{$method}(...$arguments);
        } catch (\Exception $e) {
            throw new Exception('not found method');
        }

    }
}
