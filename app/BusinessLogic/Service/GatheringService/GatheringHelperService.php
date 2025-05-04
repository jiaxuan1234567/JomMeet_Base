<?php

namespace BusinessLogic\Service\GatheringService;

use DateTime;
use Exception;

class GatheringHelperService
{
    public function validateDateTime($data, $joinedGatherings)
    {
        $field    = $data['field'] ?? '';
        $touched  = $data['touched'] ?? '';
        $value    = $data['value'] ?? [];
        $dateStr      = $value['inputDate'] ?? '';
        $startTimeStr = $value['startTime'] ?? '';
        $endTimeStr   = $value['endTime'] ?? '';
        $response = ['valid' => true, 'errors' => []];

        try {
            $date = $dateStr ? new DateTime($dateStr) : null;
            $startDT = $startTimeStr ? new DateTime($startTimeStr) : null;
            $endDT   = $endTimeStr   ? new DateTime($endTimeStr)   : null;

            if ($startDT && $endDT && $endDT < $startDT) {
                $endDT->modify('+1 day');
            }
        } catch (Exception $e) {
            return ['valid' => false, 'errors' => ['Invalid datetime format']];
        }

        $today = new DateTime('today');
        $now = new DateTime();
        $threeHoursLater = (clone $now)->modify('+3 hours');

        // Constraint: start time must be >= now + 3 hours
        if ($startDT && $startDT < $threeHoursLater) {
            $response['valid'] = false;
            $response['errors'] = 'Start time must be at least 3 hours from now.';
        }

        // 1. If field is "time" (start + end both fulfilled) and NOT triggered by "time"
        if ($field === 'time') {
            if ($startDT && $endDT) {
                if ($startDT > $endDT) {
                    $response['valid'] = false;
                    $response['errors'] = 'Start time must be earlier than end time.';
                } else if ($startDT == $endDT) {
                    $response['valid'] = false;
                    $response['errors'] = 'Invalid time options.';
                }
            }

            foreach ($joinedGatherings as $gathering) {
                $joinedStart = new DateTime("{$gathering['date']} {$gathering['startTime']}");
                $joinedEnd   = new DateTime("{$gathering['date']} {$gathering['endTime']}");

                if ($joinedEnd < $joinedStart) {
                    $joinedEnd->modify('+1 day');
                }

                if ($startDT && $endDT && $startDT < $joinedEnd && $endDT > $joinedStart) {
                    $response['valid'] = false;
                    $response['errors'] = 'Selected time overlaps with an existing gathering.';
                    break;
                }
            }
        }

        // 2. If current field is the touched one
        if ($field === $touched) {
            if ($field === 'inputDate' && $date && $date < $today) {
                $response['valid'] = false;
                $response['errors'] = 'Date must be today or later.';
            }

            if ($field === 'startTime' || $field === 'endTime') {
                $targetDT = $field === 'startTime' ? $startDT : $endDT;

                foreach ($joinedGatherings as $gathering) {
                    $joinedStart = new DateTime("{$gathering['date']} {$gathering['startTime']}");
                    $joinedEnd   = new DateTime("{$gathering['date']} {$gathering['endTime']}");

                    if ($joinedEnd < $joinedStart) {
                        $joinedEnd->modify('+1 day');
                    }

                    if ($targetDT && $targetDT > $joinedStart && $targetDT < $joinedEnd) {
                        $response['valid'] = false;
                        $response['errors'] = ucfirst($field) . ' overlaps with an existing gathering.';
                        break;
                    }
                }
            }
        }

        return $response;
    }

    public function validateLocation($data, $validLoc)
    {
        $field    = $data['field'] ?? '';
        $touched  = $data['touched'] ?? '';
        $value    = $data['value'] ?? [];
        $id      = $value['locationId'] ?? '';
        $name = $value['locationName'] ?? '';
        $response = ['valid' => true, 'errors' => []];

        if ($id === '' || $name === '') {
            $response['valid'] = false;
            $response['errors'] = 'Please select a valid location.';
        } elseif (!$validLoc || strcasecmp($validLoc['locationName'], $name) !== 0) {
            $response['valid'] = false;
            $response['errors'] = 'Selected location is invalid.';
        }
        return $response;
    }

    public function validateGatheringTag($data, $validTags)
    {
        $tag = $data['value'] ?? '';
        $response = ['valid' => true, 'errors' => []];

        if ($tag === '') {
            $response['valid'] = false;
            $response['errors'] = 'Preference is required.';
        } elseif (!in_array(strtoupper($tag), $validTags, true)) {
            $response['valid'] = false;
            $response['errors'] = 'Invalid preference selected.';
        }
        return $response;
    }

    public function validateTheme($data)
    {
        $theme = $data['value'] ?? '';
        $response = ['valid' => true, 'errors' => []];

        if ($theme === '') {
            $response['valid'] = false;
            $response['errors'] = 'Theme is required.';
        } elseif (strlen($theme) > 100) {
            $response['valid'] = false;
            $response['errors'] = 'Theme cannot exceed 100 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $theme)) {
            $response['valid'] = false;
            $response['errors'] = 'Theme must contain at least one letter.';
        }
        return $response;
    }

    public function validatePax($data, $min, $max)
    {
        $pax = $data['value'] ?? '';
        $response = ['valid' => true, 'errors' => []];

        if ($pax < $min || $pax > $max) {
            $response['valid'] = false;
            $response['errors'] = 'Pax must be between ' . $min . ' and ' . $max . '.';
        }
        return $response;
    }

    // function buildGatheringDatetime($date, $startTime, $endTime)
    // {
    //     $start = new DateTime("$date $startTime");
    //     $end = new DateTime("$date $endTime");

    //     // If end time is earlier than start time, assume it's the next day
    //     if ($end < $start) {
    //         $end->modify('+1 day');
    //     }

    //     return [
    //         'startDatetime' => $start->format('Y-m-d H:i'),
    //         'endDatetime'   => $end->format('Y-m-d H:i')
    //     ];
    // }

    // private function shouldValidate(string $field, array $only): bool
    // {
    //     return empty($only) || in_array($field, $only, true);
    // }

    // private function validateTag(string $tag, array $validTags, array $e): array
    // {
    //     if ($tag === '') {
    //         $e['gatheringTag'] = 'Preference is required.';
    //     } elseif (!in_array(strtoupper($tag), $validTags, true)) {
    //         $e['gatheringTag'] = 'Invalid preference selected.';
    //     }
    //     return $e;
    // }

    // private function validateTheme(string $theme, array $e): array
    // {
    //     if ($theme === '') {
    //         $e['inputTheme'] = 'Theme is required.';
    //     } elseif (strlen($theme) > 100) {
    //         $e['inputTheme'] = 'Theme cannot exceed 100 characters.';
    //     } elseif (!preg_match('/[A-Za-z]/', $theme)) {
    //         $e['inputTheme'] = 'Theme must contain at least one letter.';
    //     }
    //     return $e;
    // }

    // private function validatePax(int $pax, array $e): array
    // {
    //     if ($pax < 3 || $pax > 8) {
    //         $e['inputPax'] = 'Pax must be between 3 and 8.';
    //     }
    //     return $e;
    // }

    // // private function validateLocation(string $id, string $name, ?array $row, array $e): array
    // // {
    // //     if ($id === '' || $name === '') {
    // //         $e['inputLocation'] = 'Please select a valid location.';
    // //     } elseif (!$row || strcasecmp($row['locationName'], $name) !== 0) {
    // //         $e['inputLocation'] = 'Selected location is invalid.';
    // //     }
    // //     return $e;
    // // }

    // private function validateDate(string $date, array $e): array
    // {
    //     if ($date === '') {
    //         $e['inputDate'] = 'Date is required.';
    //     } else {
    //         $d = DateTime::createFromFormat('Y-m-d', $date);
    //         if (!$d) {
    //             $e['inputDate'] = 'Invalid date format.';
    //         } elseif ($d < new DateTime('today')) {
    //             $e['inputDate'] = 'Date cannot be in the past.';
    //         }
    //     }
    //     return $e;
    // }

    // private function validateTime(string $start, string $end, array $e): array
    // {
    //     if ($start === '') {
    //         $e['startTime'] = 'Start time is required.';
    //     }
    //     if ($end === '') {
    //         $e['endTime'] = 'End time is required.';
    //     }
    //     return $e;
    // }

    // // private function validateDateTime(string $date, string $start, string $end, array $e): array
    // // {
    // //     $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
    // //     $endDT   = DateTime::createFromFormat('Y-m-d H:i', "$date $end");

    // //     if (!$startDT || !$endDT) {
    // //         $e['endTime'] = 'Invalid time format.';
    // //     } elseif ($startDT >= $endDT) {
    // //         $e['endTime'] = 'End time must be after start time.';
    // //     } else {
    // //         $minStart = (new DateTime())->modify('+3 hours');
    // //         if ($startDT < $minStart) {
    // //             $e['startTime'] = 'Start time must be at least 3 hours from now.';
    // //         }
    // //     }
    // //     return $e;
    // // }

    // private function validateStartTimeBuffer(string $date, string $start, array $e): array
    // {
    //     $startDT = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
    //     if (!$startDT) return $e;

    //     $minStart = (new DateTime())->modify('+3 hours');
    //     if ($startDT < $minStart) {
    //         $e['startTime'] = 'Start time must be at least 3 hours from now.';
    //     }
    //     return $e;
    // }

    // private function validateOverlap(string $date, string $start, array $joined, array $e, ?int $editingId = null): array
    // {
    //     if ($date === '' || $start === '' || !$joined) {
    //         return $e;
    //     }

    //     $newStart = DateTime::createFromFormat('Y-m-d H:i', "$date $start");
    //     if (!$newStart) {
    //         return $e;
    //     }

    //     foreach ($joined as $g) {
    //         if ($editingId && $g['gatheringID'] == $editingId) {
    //             continue;
    //         }

    //         if (in_array(strtoupper($g['status']), ['END', 'CANCELLED'], true)) {
    //             continue;
    //         }
    //         $jStart = DateTime::createFromFormat(
    //             'Y-m-d H:i:s',
    //             "{$g['date']} {$g['startTime']}"
    //         );
    //         $jEnd = DateTime::createFromFormat(
    //             'Y-m-d H:i:s',
    //             "{$g['date']} {$g['endTime']}"
    //         );
    //         if ($jStart && $jEnd && $newStart >= $jStart && $newStart < $jEnd) {
    //             $e['startTime'] = sprintf(
    //                 "You have another gathering from %s to %s.",
    //                 $jStart->format('d M Y g:i A'),
    //                 $jEnd->format('d M Y g:i A')
    //             );
    //             break;
    //         }
    //     }

    //     return $e;
    // }
}
