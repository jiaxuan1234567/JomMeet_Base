<?php

namespace BusinessLogic\Service\GatheringService;

use DateTime;
use Exception;

class GatheringHelperService
{
    public function validateDateTime($data, $joinedGatherings, $editingId = null)
    {
        $field    = $data['field'] ?? '';
        $touched  = $data['touched'] ?? '';
        $value    = $data['value'] ?? [];
        $dateStr      = $value['inputDate'] ?? '';
        $startTimeStr = $value['startTime'] ?? '';
        $endTimeStr   = $value['endTime'] ?? '';

        $joinedGatherings = array_values(array_filter($joinedGatherings, function ($g) {
            return $g['status'] == 'NEW';
        }));

        if (!empty($editingId)) {
            $joinedGatherings = array_values(array_filter($joinedGatherings, function ($g) use ($editingId) {
                return $g['gatheringID'] != $editingId;
            }));
        }

        try {
            $date = $dateStr ? new DateTime($dateStr) : null;
            $startDT = $startTimeStr ? new DateTime($startTimeStr) : null;
            $endDT   = $endTimeStr   ? new DateTime($endTimeStr)   : null;
        } catch (Exception $e) {
            return [
                'valid' => false,
                'field' => $field,
                'touched' => $touched,
                'errors' => [
                    $touched => 'Invalid datetime format'
                ]
            ];
        }

        $today = new DateTime('today');
        $now = new DateTime();
        $threeHoursLater = (clone $now)->modify('+3 hours');

        // Constraint: start time must be >= now + 3 hours
        if ($field === 'inputDateStartTime' || $field === 'inputDateEndTime') {
            if ($date < $threeHoursLater) {
                return [
                    'valid' => false,
                    'field' => $field,
                    'touched' => $touched,
                    'errors' => [
                        $field === 'inputDateStartTime' ? 'startTime' : 'endTime' => 'Start time must be at least 3 hours from now.'
                    ]
                ];
            }
        } elseif ($startDT && $startDT < $threeHoursLater) {
            return [
                'valid' => false,
                'field' => $field,
                'touched' => $touched,
                'errors' => [
                    'startTime' => 'Start time must be at least 3 hours from now.'
                ]
            ];
        }

        // Validate start vs end
        if ($field === 'time') {
            if ($startDT && $endDT) {
                if ($startDT > $endDT) {
                    return [
                        'valid' => false,
                        'field' => $field,
                        'touched' => $touched,
                        'errors' => [
                            $touched => 'Start time must be earlier than end time.'
                        ]
                    ];
                } elseif ($startDT == $endDT) {
                    return [
                        'valid' => false,
                        'field' => $field,
                        'touched' => $touched,
                        'errors' => [
                            'startTime' => 'Invalid start time option.',
                            'endTime' => 'Invalid end time option.'
                        ]
                    ];
                }

                foreach ($joinedGatherings as $g) {
                    $joinedStart = new DateTime("{$g['date']} {$g['startTime']}");
                    $joinedEnd   = new DateTime("{$g['date']} {$g['endTime']}");
                    if ($startDT < $joinedEnd && $endDT > $joinedStart) {
                        return [
                            'valid' => false,
                            'field' => $field,
                            'touched' => $touched,
                            'errors' => [
                                $touched => 'You have a gathering start from ' . $joinedStart . ' to ' . $joinedEnd . '.'
                            ]
                        ];
                    }
                }
            }
        }

        // Validate single field overlaps
        if ($field === $touched) {
            if ($field === 'inputDate' && $date && $date < $today) {
                return [
                    'valid' => false,
                    'field' => $field,
                    'touched' => $touched,
                    'errors' => [
                        $touched => 'Date must be today or later.'
                    ]
                ];
            }

            if ($field === 'startTime' || $field === 'endTime') {
                $targetDT = $field === 'startTime' ? $startDT : $endDT;

                foreach ($joinedGatherings as $g) {
                    $joinedStart = new DateTime("{$g['date']} {$g['startTime']}");
                    $joinedEnd   = new DateTime("{$g['date']} {$g['endTime']}");
                    if ($targetDT && $targetDT > $joinedStart && $targetDT < $joinedEnd) {
                        return [
                            'valid' => false,
                            'field' => $field,
                            'touched' => $touched,
                            'errors' => [
                                $touched => 'You have a gathering start from ' . $joinedStart . ' to ' . $joinedEnd . '.'
                            ]
                        ];
                    }
                }
            }
        }

        return [
            'valid' => true,
            'field' => $field,
            'touched' => $touched,
            'errors' => []
        ];
    }

    public function validateLocation($data, $validLoc)
    {
        $field = $data['field'] ?? '';
        $touched = $data['touched'] ?? '';
        $value = $data['value'] ?? [];
        $id = $value['locationId'] ?? '';
        $name = $value['locationName'] ?? '';

        $error = '';
        if ($id === '' || $name === '') {
            $error = 'Please select a valid location.';
        } elseif (!$validLoc || strcasecmp($validLoc['locationName'], $name) !== 0) {
            $error = 'Selected location is invalid.';
        }

        return [
            'valid' => $error === '',
            'field' => $field,
            'touched' => $touched,
            'errors' => $error ? [$touched => $error] : []
        ];
    }

    public function validateGatheringTag($data, $validTags)
    {
        $field = $data['field'] ?? '';
        $touched = $data['touched'] ?? '';
        $tag = $data['value']['gatheringTag'] ?? '';
        $error = '';
        $validTags = array_map('strtoupper', $validTags);

        if ($tag === '') {
            $error = 'Preference is required.';
        } elseif (!in_array(strtoupper($tag), $validTags, true)) {
            $error = 'Invalid preference selected.';
        }

        return [
            'valid' => $error === '',
            'field' => $field,
            'touched' => $touched,
            'errors' => $error ? [$touched => $error] : []
        ];
    }

    public function validateTheme($data)
    {
        $field = $data['field'] ?? '';
        $touched = $data['touched'] ?? '';
        $theme = $data['value']['inputTheme']  ?? '';
        $error = '';

        if ($theme === '') {
            $error = 'Theme is required.';
        } elseif (strlen($theme) > 100) {
            $error = 'Theme cannot exceed 100 characters.';
        } elseif (!preg_match('/[A-Za-z]/', $theme)) {
            $error = 'Theme must contain at least one letter.';
        }

        return [
            'valid' => $error === '',
            'field' => $field,
            'touched' => $touched,
            'errors' => $error ? [$touched => $error] : []
        ];
    }

    public function validatePax($data, $min, $max)
    {
        $field = $data['field'] ?? '';
        $touched = $data['touched'] ?? '';
        $pax = $data['value']['inputPax']  ?? '';

        $error = ($pax < $min || $pax > $max)
            ? 'Pax must be between ' . $min . ' and ' . $max . '.'
            : '';

        return [
            'valid' => $error === '',
            'field' => $field,
            'touched' => $touched,
            'errors' => $error ? [$touched => $error] : []
        ];
    }
}
