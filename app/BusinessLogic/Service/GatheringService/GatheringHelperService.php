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
                            'startTime' => 'Start time and end time cannot be same.',
                            'endTime' => 'Start time and end time cannot be same.'
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
                                $touched => 'Selected time overlaps with an existing gathering.'
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
                                $touched => ucfirst($field) . ' overlaps with an existing gathering.'
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
        $tag = $data['value'] ?? '';
        $error = '';

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
        $theme = $data['value'] ?? '';
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
        $pax = $data['value'] ?? '';

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


    // public function validateDateTime($data, $joinedGatherings, $editingId = null)
    // {
    //     $field    = $data['field'] ?? '';
    //     $touched  = $data['touched'] ?? '';
    //     $value    = $data['value'] ?? [];
    //     $dateStr      = $value['inputDate'] ?? '';
    //     $startTimeStr = $value['startTime'] ?? '';
    //     $endTimeStr   = $value['endTime'] ?? '';
    //     $response = ['valid' => true, 'errors' => [], 'errFields' => []];
    //     if (!empty($editingId)) {
    //         $joinedGatherings = array_values(array_filter($joinedGatherings, function ($g) use ($editingId) {
    //             return $g['gatheringID'] != $editingId;
    //         }));
    //     }

    //     try {
    //         $date = $dateStr ? new DateTime($dateStr) : null;
    //         $startDT = $startTimeStr ? new DateTime($startTimeStr) : null;
    //         $endDT   = $endTimeStr   ? new DateTime($endTimeStr)   : null;

    //         // if ($startDT && $endDT && $endDT < $startDT) {
    //         //     $endDT->modify('+1 day');
    //         // }
    //     } catch (Exception $e) {
    //         return $this->formatResponse([
    //             'valid' => false,
    //             'errors' => ['Invalid datetime format'],
    //             'errFields' => [$touched]
    //         ], $field, $touched);
    //     }

    //     $today = new DateTime('today');
    //     $now = new DateTime();
    //     $threeHoursLater = (clone $now)->modify('+3 hours');

    //     // Constraint: start time must be >= now + 3 hours
    //     if ($field === 'inputDateStartTime' || $field === 'inputDateEndTime') {
    //         if ($date < $threeHoursLater) {
    //             $response['valid'] = false;
    //             $response['errors'] = ['Start time must be at least 3 hours from now.'];
    //             $response['errFields'] = ($field === 'inputDateStartTime') ? ['startTime'] : ['endTime'];
    //         }
    //     } else if ($startDT && $startDT < $threeHoursLater) {
    //         $response['valid'] = false;
    //         $response['errors'] = ['Start time must be at least 3 hours from now.'];
    //         $response['errFields'] = ['startTime'];
    //     }

    //     // 1. If field is "time" (start + end both fulfilled) and NOT triggered by "time"
    //     if ($field === 'time') {
    //         if ($startDT && $endDT) {
    //             if ($startDT > $endDT) {
    //                 $response['valid'] = false;
    //                 $response['errors'] = ['Start time must be earlier than end time.'];
    //                 $response['errFields'] = [$touched];
    //             } else if ($startDT == $endDT) {
    //                 $response['valid'] = false;
    //                 $error = ($touched === 'startTime') ? ['Invalid time options.', ''] : ['', 'Invalid time options.'];
    //                 $response['errors'] = [$error];
    //                 $response['errFields'] = ['startTime', 'endTime'];
    //             }
    //         }

    //         foreach ($joinedGatherings as $gathering) {
    //             $joinedStart = new DateTime("{$gathering['date']} {$gathering['startTime']}");
    //             $joinedEnd   = new DateTime("{$gathering['date']} {$gathering['endTime']}");

    //             // if ($joinedEnd < $joinedStart) {
    //             //     $joinedEnd->modify('+1 day');
    //             // }

    //             if ($startDT && $endDT && $startDT < $joinedEnd && $endDT > $joinedStart) {
    //                 $response['valid'] = false;
    //                 $response['errors'] = ['Selected time overlaps with an existing gathering.'];
    //                 $response['errFields'] = [$touched];
    //                 break;
    //             }
    //         }
    //     }

    //     // 2. If current field is the touched one
    //     if ($field === $touched) {
    //         if ($field === 'inputDate' && $date && $date < $today) {
    //             $response['valid'] = false;
    //             $response['errors'] = ['Date must be today or later.'];
    //             $response['errFields'] = [$touched];
    //         }

    //         if ($field === 'startTime' || $field === 'endTime') {
    //             $targetDT = $field === 'startTime' ? $startDT : $endDT;

    //             foreach ($joinedGatherings as $gathering) {
    //                 $joinedStart = new DateTime("{$gathering['date']} {$gathering['startTime']}");
    //                 $joinedEnd   = new DateTime("{$gathering['date']} {$gathering['endTime']}");

    //                 // if ($joinedEnd < $joinedStart) {
    //                 //     $joinedEnd->modify('+1 day');
    //                 // }

    //                 if ($targetDT && $targetDT > $joinedStart && $targetDT < $joinedEnd) {
    //                     $response['valid'] = false;
    //                     $response['errors'] = [ucfirst($field) . ' overlaps with an existing gathering.'];
    //                     $response['errFields'] = [$touched];
    //                     break;
    //                 }
    //             }
    //         }
    //     }

    //     return $this->formatResponse($response, $field, $touched);
    // }

    // public function validateLocation($data, $validLoc)
    // {
    //     $field    = $data['field'] ?? '';
    //     $touched  = $data['touched'] ?? '';
    //     $value    = $data['value'] ?? [];
    //     $id      = $value['locationId'] ?? '';
    //     $name = $value['locationName'] ?? '';
    //     $response = ['valid' => true, 'errors' => []];

    //     if ($id === '' || $name === '') {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Please select a valid location.';
    //     } elseif (!$validLoc || strcasecmp($validLoc['locationName'], $name) !== 0) {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Selected location is invalid.';
    //     }
    //     return $this->formatResponse($response, $field, $touched);
    // }

    // public function validateGatheringTag($data, $validTags)
    // {
    //     $field    = $data['field'] ?? '';
    //     $touched  = $data['touched'] ?? '';
    //     $tag = $data['value'] ?? '';
    //     $response = ['valid' => true, 'errors' => []];

    //     if ($tag === '') {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Preference is required.';
    //     } elseif (!in_array(strtoupper($tag), $validTags, true)) {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Invalid preference selected.';
    //     }
    //     return $this->formatResponse($response, $field, $touched);
    // }

    // public function validateTheme($data)
    // {
    //     $field    = $data['field'] ?? '';
    //     $touched  = $data['touched'] ?? '';
    //     $theme = $data['value'] ?? '';
    //     $response = ['valid' => true, 'errors' => []];

    //     if ($theme === '') {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Theme is required.';
    //     } elseif (strlen($theme) > 100) {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Theme cannot exceed 100 characters.';
    //     } elseif (!preg_match('/[A-Za-z]/', $theme)) {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Theme must contain at least one letter.';
    //     }
    //     return $this->formatResponse($response, $field, $touched);
    // }

    // public function validatePax($data, $min, $max)
    // {
    //     $field    = $data['field'] ?? '';
    //     $touched  = $data['touched'] ?? '';
    //     $pax = $data['value'] ?? '';
    //     $response = ['valid' => true, 'errors' => []];

    //     if ($pax < $min || $pax > $max) {
    //         $response['valid'] = false;
    //         $response['errors'] = 'Pax must be between ' . $min . ' and ' . $max . '.';
    //     }
    //     return $this->formatResponse($response, $field, $touched);
    // }

    // private function formatResponse($raw, $field, $touched)
    // {
    //     $normalized = [
    //         'valid' => $raw['valid'] ?? true,
    //         'field' => $field,
    //         'touched' => $touched,
    //         'errors' => []
    //     ];

    //     if (!$normalized['valid']) {
    //         $errors = $raw['errors'] ?? [];
    //         $errFields = $raw['errFields'] ?? [];

    //         if (!is_array($errors)) {
    //             $errors = [$errors]; // catch single string error
    //         }

    //         foreach ($errFields as $i => $f) {
    //             $normalized['errors'][$f] = $errors[$i] ?? '';
    //         }

    //         // fallback: if no specific errFields, attach to touched field
    //         if (empty($errFields) && !empty($errors)) {
    //             $normalized['errors'][$touched] = $errors[0];
    //         }
    //     }

    //     return $normalized;
    // }
}
