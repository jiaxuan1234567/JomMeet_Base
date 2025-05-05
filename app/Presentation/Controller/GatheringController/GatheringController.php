<?php

namespace Presentation\Controller\GatheringController;

use BusinessLogic\Model\GatheringModel\GatheringModel;

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
        $_SESSION['allow_select_location'] = true;

        $preferenceTags = $this->gatheringModel->getPreferenceTags();
        $paxLimit = $this->gatheringModel->getPaxLimit();
        $allowedDate = $this->gatheringModel->getCreateAllowedDate();
        include $this->fileHelper->getFilePath('CreateGathering');
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

    // GET: select-location
    public function viewSelectLocation()
    {
        //empty($_SESSION['allow_select_location'])
        if (
            $_SESSION['previous_page'] != '/api/validate-gathering' &&
            $_SESSION['previous_page'] != '/api/savedLocations' &&
            $_SESSION['previous_page'] != '/api/location-feedback' &&
            $_SESSION['previous_page'] != '/api/search-location' &&
            $_SESSION['previous_page'] != '/my-gathering/create'
        ) {
            $_SESSION['flash_message'] = 'Restricted Page' . $_SESSION['previous_page'];
            $_SESSION['flash_type'] = 'error';
            header('Location: /');
            exit;
        }

        //unset($_SESSION['allow_select_location']);
        include $this->fileHelper->getFilePath('SelectLocation');
    }

    // POST: select-location
    public function SelectedLocation()
    {
        if (isset($_SESSION['allow_select_location'])) {
            unset($_SESSION['allow_select_location']);
        }
        header('Location: /my-gathering/create');
        exit;
    }

    // POST: create-gathering
    public function createGathering()
    {
        $data = $_POST;
        $profileId = $_SESSION['profile']['profileID'];
        unset($_SESSION['allow_select_location']);

        try {
            $newId = $this->gatheringModel->createGathering($data, $profileId);
            if ($newId) {
                $_SESSION['flash_message'] = 'Gathering has been created successfully!';
                $_SESSION['flash_type'] = 'success';

                header("Location: /my-gathering");
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
        }
        $_SESSION['flash_message'] = 'Error occured while creating gathering.';
        $_SESSION['flash_type'] = 'error';
        header("Location: /my-gathering");
        exit;
    }

    // POST: edit-gathering
    public function editSubmit($gatheringId)
    {
        $data = $_POST;
        $profileId = $_SESSION['profile']['profileID'];

        try {
            $result = $this->gatheringModel->updateGathering($data, $profileId, $gatheringId);
            if ($result) {
                $_SESSION['flash_message'] = 'Gathering has been updated successfully!';
                $_SESSION['flash_type'] = 'success';

                header("Location: /my-gathering");
                exit;
            }
        } catch (Exception $e) {
            http_response_code(500);
        }
        $_SESSION['flash_message'] = 'Error occured while updating gathering.';
        $_SESSION['flash_type'] = 'error';
        header("Location: /my-gathering");
        exit;
    }

    // POST: cancel-gathering
    public function cancelGathering($id)
    {
        $result = $this->gatheringModel->cancelGathering($id);

        //$returnLoc = is_array($result) ? "/my-gathering#cancelled" : "/my-gathering";
        $returnLoc = "/my-gathering";
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

    // AJAX GET: my gathering with status
    public function ajaxGetMyGathering($status)
    {
        $userID = $_SESSION['profile']['profileID'] ?? null;
        if (!$userID) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }
        $allGatherings = $this->gatheringModel->getMyGatheringsWithTab($userID);
        $status = strtolower($status);
        header('Content-Type: application/json');
        echo json_encode($allGatherings[$status] ?? []);
    }

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

        echo json_encode($this->gatheringModel->getAllLocations());
    }

    public function ajaxSearchLocation()
    {
        header('Content-Type: application/json');

        $query = $_GET['q'] ?? '';
        $results = $this->gatheringModel->searchLocations($query);

        echo json_encode($results);
    }

    public function ajaxGetLocationFeedback()
    {
        header('Content-Type: application/json');

        $locationId = $_GET['locationId'] ?? null;

        if (!$locationId) {
            echo json_encode([]);
            return;
        }

        $feedbacks = $this->gatheringModel->getLocationFeedback($locationId);

        echo json_encode($feedbacks);
    }


    // -- Join Gathering --
    public function joinGathering()
    {
        try {
            $gatheringid = $_POST['gatheringid'] ?? null;
            $userid = $_SESSION['profile']['profileID'] ?? null;

            if ($gatheringid != null && $userid != null) {
                $result = $this->gatheringModel->addUserToGathering($userid, $gatheringid);

                if ($result) {
                    error_log("Join Result: Success");
                    $_SESSION['flash_message'] = "You have successfully joined the gathering.";
                    $_SESSION['flash_type'] = "success";
                } else {
                    error_log("Join Result: Failure");
                    // Flash message is already set in addUserToGathering for failure cases
                }

                header("Location: /gathering");
                exit;
            } else {
                error_log("Missing gatheringid or userid");
                $_SESSION['flash_message'] = "Invalid request. Missing gathering ID or user ID.";
                $_SESSION['flash_type'] = "error";
                header("Location: /gathering");
                exit;
            }
        } catch (Exception $e) {
            error_log("Error in joinGathering: " . $e->getMessage());
            $_SESSION['flash_message'] = "An error occurred while trying to join the gathering.";
            $_SESSION['flash_type'] = "error";
            header("Location: /gathering");
            exit;
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
                $_SESSION['flash_message'] = "Please enter a search term.";
                $_SESSION['flash_type'] = "error";
                header("Location: /gathering");
                exit;
            }

            $searchResults = $this->gatheringModel->searchGatherings($searchTerm);

            if (!$searchResults) {
                error_log("[GatheringController] Search returned no results for term: '" . $searchTerm . "', showing all gatherings");
                $_SESSION['flash_message'] = "No gatherings found for the search term.";
                $_SESSION['flash_type'] = "error";
                header("Location: /gathering");
                exit;
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

                error_log("[searchGatherings] Filtered Gatherings: " . print_r($filteredGatherings, true));

                if (empty($filteredGatherings)) {
                    $_SESSION['flash_message'] = "No gatherings found matching the search criteria.";
                    $_SESSION['flash_type'] = "error";
                    header("Location: /gathering");
                    exit;
                }

                $_SESSION['flash_message'] = "Search results found.";
                $_SESSION['flash_type'] = "success";
                $gatherings = $filteredGatherings;
                return include $this->fileHelper->getFilePath('GatheringList');
            }
        } catch (Exception $e) {
            error_log("[GatheringController] Error in searchGatherings: " . $e->getMessage());
            $_SESSION['flash_message'] = "An error occurred while searching for gatherings.";
            $_SESSION['flash_type'] = "error";
            header("Location: /gathering");
            exit;
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
            $_SESSION['flash_message'] = "Match Gathering Found.";
            $_SESSION['flash_type'] = "success";
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

    // ============================================================================
    // FEEDBACK PART
    // ============================================================================
    // GET the feedback page
    public function showLocationFeedback()
    {
        $profileId    = $_SESSION['profile']['profileID'];
        $gatheringId  = (int)($_POST['gatheringID']  ?? 0);
        $locationId   = (int)($_POST['locationID']   ?? 0);

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
        $gatheringFeedbacks = $this->gatheringModel->getLocationFeedback($gatheringId);
        include $this->fileHelper->getFilePath('LocationFeedback');
        exit;
    }

    // GET: display all gathering feedback + form
    public function showGatheringFeedback()
    {
        $gatheringID = (int)($_POST['gatheringID'] ?? 0);

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
        $locationFeedbacks = $this->gatheringModel->getGatheringFeedback($gatheringID);
        include $this->fileHelper->getFilePath('GatheringFeedback');
        exit;
    }

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

        if (!empty($reminders)) {
            foreach ($reminders as &$reminder) {
                $reminder['timeAgo'] = $this->formatTimeAgo($reminder['createdAt']);
                $reminder['role'] = $gathering['hostProfileID'] == $reminder['profileID'] ? 'Host' : 'Participant';
            }
        } else {
            $userRole = ($gathering['hostProfileID'] == $profileId) ? 'Host' : 'Participant';
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
            'profileId'     => $_SESSION['profile']['profileID'],
            'gatheringId'   => (int)($_POST['gatheringID'] ?? null),
            'description'   => $_POST['description'] ?? '',
            'createdAt'     => date('Y-m-d H:i:s'),
        ];

        $validationResult = $this->gatheringModel->validateReminder($data['description']);

        if (!$validationResult['success']) {
            $_SESSION['flash_message'] = $validationResult['message'];
            $_SESSION['flash_type'] = 'error';
            $_SESSION['validation_errors'] = true;
            $_SESSION['previous_desc'] = $validationResult['pre_desc'];

            header("Location: /my-gathering/reminder/view/" . $data['gatheringId']);
            exit;
        }

        try {
            $newId = $this->gatheringModel->createReminder($data);
            $_SESSION['flash_message'] = 'Reminder has been created successfully!';
            $_SESSION['flash_type'] = 'success';
            $_SESSION['validation_errors'] = false;

            header("Location: /my-gathering/reminder/view/" . $data['gatheringId']);
            exit;
        } catch (Exception $e) {
            http_response_code(500);
            echo "Error creating gathering: " . htmlspecialchars($e->getMessage());
        }
    }

    public function validateReminderData()
    {
        header('Content-Type: application/json');

        $description = trim($_POST['description'] ?? null);

        $result = $this->gatheringModel->validateReminder($description);

        echo json_encode($result);
        exit;
    }
}
