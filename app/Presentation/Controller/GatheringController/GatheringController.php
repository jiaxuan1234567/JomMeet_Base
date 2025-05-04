<?php

namespace Presentation\Controller\GatheringController;

use BusinessLogic\Model\GatheringModel\GatheringModel;
use BusinessLogic\Model\GatheringModel\LocationModel;

use Database;
use Exception;
use FileHelper;
use PDOException;

class GatheringController
{
    private $gatheringModel;
    private $fileHelper;

    public function __construct()
    {
        $this->fileHelper = new FileHelper('gathering');
        $this->gatheringModel = new GatheringModel();
    }

    // GET: create-gathering
    public function viewCreate()
    {
        $preferenceTags = $this->gatheringModel->getPreferenceTags();
        $paxLimit = $this->gatheringModel->getPaxLimit();
        $allowedDate = $this->gatheringModel->getCreateAllowedDate();
        include $this->fileHelper->getFilePath('CreateGathering');
    }

    // POST: create-gathering
    public function createGathering()
    {
        $data = [
            'locationId'        => (int)($_POST['locationId'] ?? 0),
            'theme'             => trim($_POST['inputTheme'] ?? ''),
            'maxParticipant'    => (int)($_POST['inputPax'] ?? 0),
            'minParticipant'    => (int)($_POST['minParticipant'] ?? 3),
            'currentParticipant' => 0,
            'date'              => $_POST['inputDate'] ?? '',
            'startTime'         => $_POST['startTime'] ?? '',
            'endTime'           => $_POST['endTime'] ?? '',
            'status'            => 'NEW',
            'preference'        => $_POST['gatheringTag'] ?? '',
            'hostProfileID' => $_SESSION['profile']['profileID']
        ];

        try {
            $newId = $this->gatheringModel->createGathering($data);
            $_SESSION['flash_message'] = 'Gathering has been created successfully!';
            $_SESSION['flash_type'] = 'success';

            header("Location: /my-gathering#hosted");
            exit;
        } catch (Exception $e) {
            // on error, you could re-render the form with $e->getMessage()
            http_response_code(500);
            echo "Error creating gathering: " . htmlspecialchars($e->getMessage());
        }
    }

    // GET: edit-gathering
    public function viewEdit($gatheringId)
    {
        $profileId = $_SESSION['profile']['profileID'];
        $gathering = $this->gatheringModel->getEditableGatheringById($gatheringId, $profileId);

        if (!empty($gathering['error'])) {
            $_SESSION['flash_message'] = $gathering['error'];
            $_SESSION['flash_type'] = "error";
            header("Location: /my-gathering");
            exit;
        }

        $paxLimit = $this->gatheringModel->getEditPaxLimit($gathering);
        $preferenceTags = $this->gatheringModel->getPreferenceTags();
        $allowedDate = $this->gatheringModel->getCreateAllowedDate();

        include $this->fileHelper->getFilePath('EditGathering');
    }

    // POST: edit-gathering
    public function editSubmit($gatheringId)
    {
        $data = $_POST;
        $profileId = $_SESSION['profile']['profileID'];

        $this->gatheringModel->updateGathering($data, $profileId, $gatheringId);
        $_SESSION['flash_message'] = 'Gathering updated successfully!';
        $_SESSION['flash_type'] = 'success';
        header("Location: /my-gathering");
        exit;
    }

    // GET: select-location
    public function viewSelectLocation()
    {
        $redirectUrl = $_GET['redirect'] ?? '/my-gathering/create';
        include $this->fileHelper->getFilePath('SelectLocation');
    }

    // POST: cancel-gathering
    public function cancelGathering($id)
    {
        $result = $this->gatheringModel->cancelGathering($id);

        $returnLoc = is_array($result) ? "/my-gathering#cancelled" : "/my-gathering";
        header("Location: " . $returnLoc);
        exit;
    }

    // POST: leave-gathering
    public function leaveGathering($gatheringId)
    {
        $profileId = $_SESSION['profile']['profileID'];
        $result = $this->gatheringModel->leaveGathering($profileId, $gatheringId);

        header('Location: /my-gathering');
        exit;
    }

    // AJAX Validation: CREATE Gathering Fields
    public function ajaxValidateGathering()
    {
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);
        $response = $this->gatheringModel->validateGathering($data);
        echo json_encode($response);
        exit;
    }

    // AJAX Validation: EDIT Gathering Fields
    public function ajaxValidateEditGathering()
    {
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);
        $editingId = $post['editingId'] ?? '';
        $data = $post['data'];
        $response = $this->gatheringModel->validateGathering($data, $editingId);
        echo json_encode($response);
        exit;
    }

    // // AJAX GET: my gathering with status
    // public function ajaxGetMyGathering($status)
    // {
    //     $userID = $_SESSION['userID'] ?? null;
    //     if (!$userID) {
    //         http_response_code(401);
    //         echo json_encode(['error' => 'Unauthorized']);
    //         return;
    //     }

    //     $allGatherings = $this->gatheringModel->getMyGatheringsWithTab($userID); // ← Your full list
    //     $status = strtolower($status);

    //     $filtered = array_filter($allGatherings, function ($g) use ($status) {
    //         switch ($status) {
    //             case 'hosted':
    //                 return $g['isHost'] && $g['status'] !== 'cancelled';
    //             case 'upcoming':
    //                 return $g['status'] === 'new';
    //             case 'ongoing':
    //                 return $g['status'] === 'start';
    //             case 'completed':
    //                 return $g['status'] === 'end';
    //             case 'cancelled':
    //                 return $g['isHost'] && $g['status'] === 'cancelled';
    //             case 'all':
    //             default:
    //                 return true;
    //         }
    //     });

    //     header('Content-Type: application/json');
    //     echo json_encode(array_values($filtered));
    // }

    // GET: gathering-detail
    public function viewDetail($gatheringId)
    {
        $profileId = $_SESSION['profile']['profileID'];
        $gathering = $this->gatheringModel->getPublicGatheringById($profileId, $gatheringId);

        // Not public gathering
        if (!$gathering) {
            // Check if is user gathering
            $gathering = $this->gatheringModel->getUserGatheringById($profileId, $gatheringId);
            if ($gathering) {
                header('Location: /my-gathering/view/' . $gathering['gatheringID']);
                exit;
            } else {
                $_SESSION['flash_message'] = "You are not authorized to view this gathering.";
                $_SESSION['flash_type'] = "error";
                header('Location: /gathering');
                exit;
            }
        }
        include $this->fileHelper->getFilePath('GatheringDetail');
    }

    // GET: my-gathering-detail
    public function viewMyGatheringDetail($gatheringId)
    {
        $profileId = $_SESSION['profile']['profileID'];
        $gathering = $this->gatheringModel->getUserGatheringById($profileId, $gatheringId);

        // Not user gathering
        if (!$gathering) {
            // Check if is public gathering
            $gathering = $this->gatheringModel->getPublicGatheringById($profileId, $gatheringId);
            if ($gathering) {
                header('Location: /gathering/view/' . $gathering['gatheringID']);
                exit;
            } else {
                $_SESSION['flash_message'] = "You are not authorized to view this gathering.";
                $_SESSION['flash_type'] = "error";
                header('Location: /my-gathering');
                exit;
            }
        }
        include $this->fileHelper->getFilePath('MyGatheringDetails');
    }

    // AJAX GET: Get All Locations
    public function apiSavedLocations()
    {
        header('Content-Type: application/json');

        echo json_encode((new LocationModel())->getAllLocations());
    }

    public function ajaxSearchLocation()
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';
        $model = new LocationModel();
        $results = (new LocationModel())->searchLocations($query);

        echo json_encode($results);
    }

    // -- Join Gathering --
    public function joinGathering()
    {
        try {
            $gatheringid = $_POST['gatheringid'] ?? null;
            $userid = $_SESSION['profile']['profileID'] ?? null;

            if ($gatheringid != null && $userid != null) {
                $result = $this->gatheringModel->addUserToGathering($userid, $gatheringid);
                error_log("Join Result: " . ($result ? 'Success' : 'Failure'));
                $gatherings = $this->gatheringModel->getAllGatherings();
                header("Location: /gathering");
            } else {
                error_log("Missing gatheringid or userid");
            }
        } catch (Exception $e) {
            error_log("Error in joinGathering: " . $e->getMessage());
            return false;
        }
    }

    // public function verifyUserInGathering($userID, $gatheringID)
    // {
    //     return $this->gatheringModel->verifyUserInGathering($userID, $gatheringID);
    // }

    // public function isBeforeStartTime($gatheringID)
    // {
    //     try {
    //         return $this->gatheringModel->isBeforeStartTime($gatheringID);
    //     } catch (Exception $e) {
    //         error_log("Error in isBeforeStartTime: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function listGatherings($userID)
    {
        try {
            $gatherings = $this->gatheringModel->getAvailableGatherings($userID);
            if ($gatherings === false) {
                error_log("getAllGatherings returned false");
                return [];
            }
            return $gatherings;
        } catch (Exception $e) {
            error_log("Error in listGatherings: " . $e->getMessage());
            return [];
        }
    }


    public function getPublicGatheringById($userID, $gatheringID)
    {
        try {
            return $this->gatheringModel->getPublicGatheringById($userID, $gatheringID);
        } catch (Exception $e) {
            error_log("Error in getPublicGatheringById: " . $e->getMessage());
            return [];
        }
    }

    public function searchGatherings()
    {
        try {
            $userID = $_POST['userid'] ?? null;
            $searchTerm = $_POST['searchTerm'] ?? '';

            if ($searchTerm === '' || $searchTerm === null) {
                $gatherings = $this->listGatherings($userID);
                header("Location: /gathering");
                return;
            }

            $searchResults = $this->gatheringModel->searchGatherings($searchTerm);

            if (!$searchResults) {
                error_log("[GatheringController] Search returned no results for term: '" . $searchTerm . "', showing all gatherings");
                $gatherings = $this->listGatherings($userID);
                header("Location: /gathering");
            } else {
                $filteredGatherings = [];

                foreach ($searchResults as $gathering) {
                    $gatheringID = $gathering['gatheringID'];

                    // Delegate condition filtering to getPublicGatheringById
                    $validGathering = $this->getPublicGatheringById($userID, $gatheringID);

                    if (!empty($validGathering)) {
                        $filteredGatherings[] = $validGathering;
                    } else {
                        error_log("[searchGatherings] Skipping gathering $gatheringID: not eligible (joined, private, or time conflict)");
                    }
                }

                $gatherings = $filteredGatherings;
            }

            return include $this->fileHelper->getFilePath('GatheringList');
        } catch (Exception $e) {
            error_log("[GatheringController] Error in searchGatherings: " . $e->getMessage());
            return [];
        }
    }



    // public function isNewGatheringConflicting($userID, $gatheringID)
    // {
    //     try {
    //         return $this->gatheringModel->isNewGatheringConflicting($userID, $gatheringID);
    //     } catch (Exception $e) {
    //         error_log("Error in isNewGatheringConflicting: " . $e->getMessage());
    //         return false;
    //     }
    // }

    public function matchGathering()
    {
        try {
            $userID = $_SESSION['profile']['profileID'] ?? null;
            error_log("User ID: " . $userID);
            $gatherings = $this->gatheringModel->matchGathering($userID);

            if (empty($gatherings)) {
            } else {
            }

            return include $this->fileHelper->getFilePath('GatheringList');
        } catch (Exception $e) {
            return include $this->fileHelper->getFilePath('GatheringList');
        }
    }


    // Background Job
    public function runGatheringJob()
    {
        $this->gatheringModel->checkAndTransitionGatherings();
    }

    //
    // HELPER FUNCTION to save location (will DELETE in future)
    //
    public function saveLocation()
    {
        // read JSON POST body
        $loc = json_decode(file_get_contents('php://input'), true);

        $db = Database::getConnection();

        try {
            try {
                $placeId = $loc['place_id'];
                $name = $loc['name'];
                $address = $loc['address'];
                $latitude = $loc['latitude'];
                $longtitude = $loc['longitude'];

                $sql = "
                          INSERT INTO `location` (placeID, locationName, address,  longitude, latitude)
                          VALUES (:pid, :name, :addr, :lng, :lat)
                          ON DUPLICATE KEY UPDATE
                            locationName = VALUES(locationName),
                            `address`    = VALUES(`address`),
                            latitude     = VALUES(latitude),
                            longitude    = VALUES(longitude)
            
                        ";
                $stmt = $db->prepare($sql);
                return $stmt->execute([
                    ':pid'  => $placeId,
                    ':name' => $name,
                    ':addr' => $address,
                    ':lng'  => $longtitude,
                    ':lat'  => $latitude,
                ]);
            } catch (PDOException $e) {
                error_log("Error in saveLocation: " . $e->getMessage());
                return false;
            }
            http_response_code(200);
            echo $status;
        } catch (Exception $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
    }

    // ============================================================================
    // FEEDBACK PART
    // ============================================================================
    // GET the feedback page
    public function showLocationFeedback()
    {
        $profileId    = $_SESSION['profile']['profileID'];
        $gatheringId  = (int)($_GET['gatheringID']  ?? 0);
        $locationId   = (int)($_GET['locationID']   ?? 0);

        // Only participants can view
        if (! $this->gatheringModel->verifyUserInGathering($profileId, $gatheringId)) {
            $_SESSION['flash_message'] = "You must join this gathering to leave feedback.";
            $_SESSION['flash_type']    = "error";
            header("Location: /my-gathering/view/{$gatheringId}");
            exit;
        }

        $locationFeedbacks = $this->gatheringModel->getLocationFeedback($locationId);

        include $this->fileHelper->getFilePath('LocationFeedback');
    }

    // POST to save feedback
    public function locationFeedback()
    {
        $profileId    = $_SESSION['profile']['profileID'];
        $gatheringId  = (int)($_POST['gatheringID']   ?? 0);
        $locationId   = (int)($_POST['locationID']    ?? 0);
        $desc         = trim($_POST['feedbackDesc']   ?? '');

        // Only participants can submit
        if (! $this->gatheringModel->verifyUserInGathering($profileId, $gatheringId)) {
            $_SESSION['flash_message'] = "You must join this gathering to leave feedback.";
            $_SESSION['flash_type']    = "error";
        }
        // And only once per gathering
        else if ($this->gatheringModel->saveLocationFeedback($profileId, $gatheringId, $locationId, $desc)) {
            $_SESSION['flash_message'] = "Thank you for your feedback!";
            $_SESSION['flash_type']    = "success";
        } else {
            $_SESSION['flash_message'] = "You have already left feedback for this gathering.";
            $_SESSION['flash_type']    = "error";
        }

        // Redirect back to the GET page
        header("Location: /my-gathering/locationFeedback"
            . "?gatheringID={$gatheringId}&locationID={$locationId}");
        exit;
    }

    // GET: display all gathering feedback + form
    public function showGatheringFeedback()
    {
        $gatheringID = (int)($_GET['gatheringID'] ?? 0);

        // fetch all feedback entries for this gathering
        $gatheringFeedbacks = $this->gatheringModel
            ->getGatheringFeedback($gatheringID);

        // include the view—which expects $gatheringID & $gatheringFeedbacks
        include $this->fileHelper->getFilePath('GatheringFeedback');
    }

    // POST: save a new anonymous gathering feedback, then redirect back
    public function submitGatheringFeedback()
    {
        $profileId   = $_SESSION['profile']['profileID'];
        $gatheringID = (int)($_POST['gatheringID'] ?? 0);
        $desc        = trim($_POST['feedbackDesc'] ?? '');

        // Only participants can submit
        if (! $this->gatheringModel->verifyUserInGathering($profileId, $gatheringID)) {
            $_SESSION['flash_message'] = "You must join this gathering to leave feedback.";
            $_SESSION['flash_type']    = "error";
        }
        // And only once per gathering
        else if ($this->gatheringModel->addGatheringFeedback($profileId, $gatheringID, $desc)) {
            $_SESSION['flash_message'] = "Thank you for your feedback!";
            $_SESSION['flash_type']    = "success";
        } else {
            $_SESSION['flash_message'] = "You have already left feedback for this gathering.";
            $_SESSION['flash_type']    = "error";
        }

        // Redirect back to the GET page
        header("Location: /my-gathering/gatheringFeedback?gatheringID={$gatheringID}");
        exit;
        
    // ============================================================================
    // Reminder PART
    // ============================================================================
    public function viewGatheringReminder($gatheringId)
    {
        $profileId = $_SESSION['profile']['profileID'];

        $gathering = $this->gatheringModel->getUserGatheringById($profileId, $gatheringId);

        if (!$gathering) {
            $_SESSION['flash_message'] = "You are not authorized to view this gathering reminder.";
            $_SESSION['flash_type'] = "error";
            header('Location: /my-gathering');
            exit;
        }

        $reminders = $this->gatheringModel->getReminders($gatheringId, $profileId);

        foreach ($reminders as &$reminder) {
            $reminder['timeAgo'] = $this->formatTimeAgo($reminder['createdAt']);
            $reminder['role'] = $gathering['hostProfileID'] == $reminder['profileID'] ? 'Host' : 'Participant';
        }

        include $this->fileHelper->getFilePath('GatheringReminder');
    }

    private function formatTimeAgo($datetime)
    {
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;

        if ($diff < 60) {
            return "just now";
        } elseif ($diff < 3600) {
            return floor($diff / 60) . " minute(s) ago";
        } elseif ($diff < 86400) {
            return floor($diff / 3600) . " hour(s) ago";
        } elseif ($diff < 172800) {
            return "yesterday";
        } else {
            return date("d M Y, H:i", $timestamp);
        }
    }

    public function createGatheringReminder()
    {
        $data = [
            'profileId'         => $_SESSION['profile']['profileID'],
            'gatheringId'       => (int)($_POST['gatheringID'] ?? null),
            'description'       => $_POST['description'] ?? '',
            'createdAt'         => date('Y-m-d H:i:s'),
        ];

        try {
            $newId = $this->gatheringModel->createReminder($data);
            $_SESSION['flash_message'] = 'Reminder has been created successfully!';
            $_SESSION['flash_type'] = 'success';
            
            header("Location: /my-gathering/reminder/view/" . $data['gatheringId']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error creating gathering: " . htmlspecialchars($e->getMessage());
        }
    }
}
