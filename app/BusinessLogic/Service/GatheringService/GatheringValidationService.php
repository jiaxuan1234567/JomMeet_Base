<?php

namespace BusinessLogic\Service\GatheringService;

use DateTime;

/**
 * Business-logic helper that validates the Create-Gathering input.
 * It does NOT touch the database – every lookup must be supplied
 * by the caller (normally the GatheringModel).
 */
class GatheringValidationService
{
    public function __construct() {}

    public function validate(array $form, ?array $locationRow, array $currentGatherings): array
    {
        $errors = [];

        $this->theme($form, $errors);
        $this->dateTime($form, $errors);
        $this->pax($form, $errors);
        $this->location($form, $locationRow, $errors);

        if ($this->canCheckOverlap($errors)) {
            $this->overlap($form, $currentGatherings, $errors);
        }
        return $errors;
    }

    /* ---- atomic helpers (unchanged except for DAO removal) ---- */

    private function theme($d, $e)
    {
        $theme = trim($d['inputTheme'] ?? '');

        if ($theme === '') {
            $e['inputTheme'] = 'Theme is required.';
        } elseif (strlen($theme) > 100) {
            $e['inputTheme'] = 'Theme cannot exceed 100 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $theme)) {
            $e['inputTheme'] = 'Theme must contain at least one letter.';
        }
        return $e;
    }
    private function dateTime(array $d, array &$e): void
    {
        $date  = $d['inputDate'] ?? '';
        $start = $d['startTime']  ?? '';
        $end   = $d['endTime']    ?? '';

        // 1. Date integrity
        if ($date === '') {
            $e['inputDate'] = 'Date is required.';
            return; // further time rules make no sense
        }
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dateObj) {
            $e['inputDate'] = 'Invalid date format.';
            return;
        }
        if ($dateObj < new DateTime('today')) {
            $e['inputDate'] = 'Date cannot be in the past.';
            return;
        }

        // 2. Start / End presence
        if ($start === '') {
            $e['startTime'] = 'Start time is required.';
        }
        if ($end   === '') {
            $e['endTime']   = 'End time is required.';
        }
        if (isset($e['startTime']) || isset($e['endTime'])) {
            return;
        }

        // 3. Convert to DateTime for comparisons
        $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        $endDT   = DateTime::createFromFormat('Y-m-d H:i', "$date $end");
        if (!$startDT || !$endDT) {
            $e['startTime'] = 'Invalid time format.';
            return;
        }
        if ($startDT >= $endDT) {
            $e['endTime'] = 'End time must be after start time.';
        }

        // 4. “Start ≥ now + 3 h” regardless of date
        $minStart = (new DateTime())->modify('+3 hours');
        if ($startDT < $minStart) {
            $e['startTime'] = 'Start time must be at least 3 hours from now.';
        }
    }

    private function pax(array $d, array &$e): void
    {
        $pax = (int)($d['inputPax'] ?? 0);
        if ($pax < 3 || $pax > 8) {
            $e['inputPax'] = 'Pax must be between 3 and 8.';
        }
    }

    private function location(array $d, ?array $loc, array &$e): void
    {
        $id   = $d['locationId'] ?? '';
        $name = trim($d['inputLocation'] ?? '');

        if ($id === '' || $name === '') {
            $e['inputLocation'] = 'Please select a valid location.';
            return;
        }
        if (!$loc || strcasecmp($loc['locationName'], $name) !== 0) {
            $e['inputLocation'] = 'Selected location is invalid.';
        }
    }

    private function canCheckOverlap(array $e): bool
    {
        return empty($e['inputDate']) && empty($e['startTime']);
    }

    private function overlap(array $d, array $rows, array &$e): void
    {
        $startDT = DateTime::createFromFormat(
            'Y-m-d H:i',
            "{$d['inputDate']} {$d['startTime']}"
        );
        if (!$startDT) {
            return;
        }

        foreach ($rows as $g) {
            if (in_array(strtoupper($g['status']), ['END', 'CANCELLED'], true)) {
                continue;
            }
            $jStart = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['startTime']}"
            );
            $jEnd = DateTime::createFromFormat(
                'Y-m-d H:i:s',
                "{$g['date']} {$g['endTime']}"
            );
            if ($jStart && $jEnd && $startDT >= $jStart && $startDT < $jEnd) {
                $e['startTime'] =
                    "You have another gathering from " .
                    $jStart->format('d M Y g:i A')
                    . " to " . $jEnd->format('d M Y g:i A') . ".";
                break;
            }
        }
    }
}
