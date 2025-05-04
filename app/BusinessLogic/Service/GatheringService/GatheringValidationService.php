<?php

namespace BusinessLogic\Service\GatheringService;

use DateTime;

class GatheringValidationService
{
    public function validate(array $form, array $validTags, ?array $locationRow, array $joinedRows, array $onlyFields = [], ?int $editingId = null): array
    {
        $errors = [];

        // 1. Theme
        if ($this->shouldValidate('inputTheme', $onlyFields)) {
            $errors = $this->validateTheme(trim($form['inputTheme'] ?? ''), $errors);
        }

        // 2. Pax
        if ($this->shouldValidate('inputPax', $onlyFields)) {
            $errors = $this->validatePax((int)($form['inputPax'] ?? 0), $errors);
        }

        // 3. Location
        if ($this->shouldValidate('inputLocation', $onlyFields)) {
            $errors = $this->validateLocation(
                $form['locationId'] ?? '',
                trim($form['inputLocation'] ?? ''),
                $locationRow,
                $errors
            );
        }

        // 4. Tag
        if ($this->shouldValidate('gatheringTag', $onlyFields)) {
            $errors = $this->validateTag($form['gatheringTag'] ?? '', $validTags, $errors);
        }

        // 5. Date
        $date = $form['inputDate'] ?? '';
        if ($this->shouldValidate('inputDate', $onlyFields)) {
            $errors = $this->validateDate($date, $errors);
        }

        // 6. Time presence
        // $start = $form['startTime'] ?? '';
        // $end   = $form['endTime'] ?? '';
        $start = isset($form['startTime']) ? substr($form['startTime'], 0, 5) : '';
        $end   = isset($form['endTime']) ? substr($form['endTime'], 0, 5) : '';
        if ($this->shouldValidate('startTime', $onlyFields) || $this->shouldValidate('endTime', $onlyFields)) {
            $errors = $this->validateTime($start, $end, $errors);
        }

        // 7. Time buffer
        if (empty($errors['inputDate']) && empty($errors['startTime']) && $date !== '' && $start !== '') {
            $errors = $this->validateStartTimeBuffer($date, $start, $errors);
        }

        // 8. Date+Time logic
        if (empty($errors['inputDate']) && empty($errors['startTime']) && empty($errors['endTime'])) {
            $errors = $this->validateDateTime($date, $start, $end, $errors);
        }

        // 9. Overlap always runs
        $errors = $this->validateOverlap($date, $start, $joinedRows, $errors, $editingId);

        return $errors;
    }

    private function shouldValidate(string $field, array $only): bool
    {
        return empty($only) || in_array($field, $only, true);
    }

    private function validateTag(string $tag, array $validTags, array $e): array
    {
        if ($tag === '') {
            $e['gatheringTag'] = 'Preference is required.';
        } elseif (!in_array(strtoupper($tag), $validTags, true)) {
            $e['gatheringTag'] = 'Invalid preference selected.';
        }
        return $e;
    }

    private function validateTheme(string $theme, array $e): array
    {
        if ($theme === '') {
            $e['inputTheme'] = 'Theme is required.';
        } elseif (strlen($theme) > 100) {
            $e['inputTheme'] = 'Theme cannot exceed 100 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $theme)) {
            $e['inputTheme'] = 'Theme must contain at least one letter.';
        }
        return $e;
    }

    private function validatePax(int $pax, array $e): array
    {
        if ($pax < 3 || $pax > 8) {
            $e['inputPax'] = 'Pax must be between 3 and 8.';
        }
        return $e;
    }

    private function validateLocation(string $id, string $name, ?array $row, array $e): array
    {
        if ($id === '' || $name === '') {
            $e['inputLocation'] = 'Please select a valid location.';
        } elseif (!$row || strcasecmp($row['locationName'], $name) !== 0) {
            $e['inputLocation'] = 'Selected location is invalid.';
        }
        return $e;
    }

    private function validateDate(string $date, array $e): array
    {
        if ($date === '') {
            $e['inputDate'] = 'Date is required.';
        } else {
            $d = DateTime::createFromFormat('Y-m-d', $date);
            if (!$d) {
                $e['inputDate'] = 'Invalid date format.';
            } elseif ($d < new DateTime('today')) {
                $e['inputDate'] = 'Date cannot be in the past.';
            }
        }
        return $e;
    }

    private function validateTime(string $start, string $end, array $e): array
    {
        if ($start === '') {
            $e['startTime'] = 'Start time is required.';
        }
        if ($end === '') {
            $e['endTime'] = 'End time is required.';
        }
        return $e;
    }

    private function validateDateTime(string $date, string $start, string $end, array $e): array
    {
        $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        $endDT   = DateTime::createFromFormat('Y-m-d H:i', "$date $end");

        if (!$startDT || !$endDT) {
            $e['endTime'] = 'Invalid time format.';
        } elseif ($startDT >= $endDT) {
            $e['endTime'] = 'End time must be after start time.';
        } else {
            $minStart = (new DateTime())->modify('+3 hours');
            if ($startDT < $minStart) {
                $e['startTime'] = 'Start time must be at least 3 hours from now.';
            }
        }
        return $e;
    }

    private function validateStartTimeBuffer(string $date, string $start, array $e): array
    {
        $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        if (!$startDT) return $e;

        $minStart = (new DateTime())->modify('+3 hours');
        if ($startDT < $minStart) {
            $e['startTime'] = 'Start time must be at least 3 hours from now.';
        }
        return $e;
    }

    private function validateOverlap(string $date, string $start, array $joined, array $e, ?int $editingId = null): array
    {
        if ($date === '' || $start === '' || !$joined) {
            return $e;
        }

        $newStart = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
        if (!$newStart) {
            return $e;
        }

        foreach ($joined as $g) {
            if ($editingId && $g['gatheringID'] == $editingId) {
                continue;
            }

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
            if ($jStart && $jEnd && $newStart >= $jStart && $newStart < $jEnd) {
                $e['startTime'] = sprintf(
                    "You have another gathering from %s to %s.",
                    $jStart->format('d M Y g:i A'),
                    $jEnd->format('d M Y g:i A')
                );
                break;
            }
        }

        return $e;
    }
}
