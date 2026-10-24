<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RadioListenerStats extends Model
{
    use HasFactory;

    protected $table = 'radio_listener_stats';
    protected $fillable = [
        'radio_id',
        'stat_date',
        'total_listeners',
        'hourly_listeners' // Ubah semua referensi hourly_breakdown menjadi hourly_listeners
    ];
    
    protected $casts = [
        'hourly_listeners' => 'array',
        'stat_date' => 'date'
    ];

    public function radio()
    {
        return $this->belongsTo(StasiunRadio::class);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('stat_date', [$startDate, $endDate]);
    }

    public function scopeForRadio($query, $radioId)
    {
        return $query->where('radio_id', $radioId);
    }

    public function updateHourlyData($hour, $count)
    {
        $hourlyData = $this->hourly_listeners ?: [];
        $hourlyData[$hour] = ($hourlyData[$hour] ?? 0) + $count;
        $this->hourly_listeners = $hourlyData;
    }

    // Tambahkan method untuk mengambil data per jam
    public static function getHourlyStatsForDate($radioId, $date)
    {
        $stats = self::where('radio_id', $radioId)
                    ->whereDate('stat_date', $date)
                    ->first();
                    
        return $stats ? $stats->hourly_listeners : [];
    }

    public static function getMonthlyStats($radioId, $year)
    {
        return self::forRadio($radioId)
            ->whereYear('stat_date', $year)
            ->selectRaw('MONTH(stat_date) as month, SUM(total_listeners) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();
    }

    public static function getDailyStats($radioId, $year, $month)
    {
        return self::forRadio($radioId)
            ->whereYear('stat_date', $year)
            ->whereMonth('stat_date', $month)
            ->selectRaw('DAY(stat_date) as day, SUM(total_listeners) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();
    }
    
}