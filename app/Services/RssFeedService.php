<?php

namespace App\Services;

use SimpleXMLElement;

class RssFeedService
{
    // === UNTUK "CEK RSS" (MANUAL) ===
    public function getLatestGenre($feedUrl)
    {
        try {
            $xml = simplexml_load_file($feedUrl);
            if (!$xml || !isset($xml->channel->item[0])) {
                return null;
            }
            
            $latestItem = $xml->channel->item[0];
            $title = (string) $latestItem->title;
            $description = (string) $latestItem->description;
            
            return $this->extractGenre($title, $description);
        } catch (\Exception $e) {
            return null;
        }
    }

    // === UNTUK GENRE DINAMIS (OTOMATIS BERDASARKAN JAM) ===
    public function getGenreByTime($feedUrl, $jamSekarang, $hari)
    {
        try {
            $xml = simplexml_load_file($feedUrl);
            if (!$xml || !isset($xml->channel->item)) {
                return null;
            }

            foreach ($xml->channel->item as $item) {
                $title = (string) $item->title;
                $description = (string) $item->description;
                $pubDate = isset($item->pubDate) ? (string) $item->pubDate : '';

                $jamProgram = $this->extractTime($title, $description, $pubDate);
                
                if ($jamProgram && $this->isTimeInRange($jamSekarang, $jamProgram)) {
                    $genre = $this->extractGenre($title, $description);
                    if ($genre) return $genre;
                }
            }

            // Fallback: ambil genre dari item pertama
            $firstItem = $xml->channel->item[0];
            return $this->extractGenre(
                (string) $firstItem->title,
                (string) $firstItem->description
            );
        } catch (\Exception $e) {
            return null;
        }
    }

    // === CORE FUNCTIONS ===
    private function extractGenre($title, $description)
    {
        $keywords = [
            'dangdut' => 'Dangdut',
            'koplo' => 'Dangdut',
            'campursari' => 'Dangdut',
            'pop' => 'Pop',
            'hits' => 'Pop',
            'musik' => 'Pop',
            'religi' => 'Religi',
            'islami' => 'Religi',
            'rohani' => 'Religi',
            'sholawat' => 'Religi',
            'berita' => 'Berita',
            'news' => 'Berita',
            'info' => 'Berita',
            'talkshow' => 'Berita',
            'lokal' => 'Lokal',
            'daerah' => 'Lokal',
            'budaya' => 'Lokal',
        ];

        $text = strtolower($title . ' ' . $description);

        foreach ($keywords as $keyword => $genre) {
            if (strpos($text, $keyword) !== false) {
                return $genre;
            }
        }

        return null;
    }

    private function extractTime($title, $description, $pubDate)
    {
        if (!empty($pubDate)) {
            try {
                $date = new \DateTime($pubDate);
                return $date->format('H:i:s');
            } catch (\Exception $e) {}
        }

        $patterns = ['/(\d{1,2})[:.](\d{2})/', '/(\d{1,2})-(\d{2})/'];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $title . ' ' . $description, $matches)) {
                return sprintf('%02d:%02d:00', $matches[1], $matches[2]);
            }
        }

        return null;
    }

    private function isTimeInRange($now, $start)
    {
        $nowTs = strtotime($now);
        $startTs = strtotime($start);
        $endTs = strtotime('+2 hours', $startTs);
        return $nowTs >= $startTs && $nowTs <= $endTs;
    }
}