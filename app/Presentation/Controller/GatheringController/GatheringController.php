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
        header("Location: /my-gathering/view/$gatheringId");
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
        $post = json_decode($json, true);

        $fields = $post['touchedFields'] ?? [];
        $data = $post;

        $errors = $this->gatheringModel->validateGatheringFields($data, $fields);

        echo json_encode(['errors' => $errors]);
    }

    // AJAX Validation: EDIT Gathering Fields
    public function ajaxValidateEditGathering()
    {
        header('Content-Type: application/json');
        $json = file_get_contents('php://input');
        $post = json_decode($json, true);

        $fields = $post['touchedFields'] ?? [];
        $gatheringId = $post['gatheringId'] ?? null;
        $data = $post;

        $errors = $this->gatheringModel->validateGatheringFields($data, $fields, $gatheringId);

        echo json_encode(['errors' => $errors]);
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

    public function searchGatherings()
    {
        try {
            $userID = $_POST['userid'] ?? null;
            $searchTerm = $_POST['searchTerm'] ?? '';

            if ($searchTerm === '' || $searchTerm === null) {
                $gatherings = $this->listGatherings($userID);
                header("Location: /gathering");
                // return include $this->fileHelper->getFilePath('GatheringList');
            } else {
                $gatherings = $this->gatheringModel->searchGatherings($searchTerm);
                if (!$gatherings) {
                    error_log("[GatheringController] Search returned no results for term: '" . $searchTerm . "', showing all gatherings");
                    $gatherings = $this->listGatherings($userID);
                }
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

    public function matchGathering($userid)
    {
        try {
            $gatherings = $this->gatheringModel->matchGathering($userid);

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
}
