<?php
require_once '../../../fileRegister.php';
require_once '../../../Persistence/DAO/GatheringDAO/GatheringDAO.php';
require_once '../../../BusinessLogic/Model/GatheringModel/GatheringModel.php';
require_once '../../../../app/Database.php';

class GatheringController
{
    private $gatheringModel;
    private $path;

    public function __construct()
    {
        $this->initializeDependencies();
    }

    /**
     * Initialize dependencies such as the database connection and GatheringModel.
     */
    private function initializeDependencies()
    {
        $this->path = getFilePath("gathering");
        try {
            $database = new Database();
            $db = $database->getConnection();
            $this->gatheringModel = new GatheringModel($db);
        } catch (Exception $e) {
            $this->handleInitializationError($e);
        }
    }

    /**
     * Handle errors during initialization.
     *
     * @param Exception $e
     */
    private function handleInitializationError(Exception $e)
    {
        error_log("Error in GatheringController constructor: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(["error" => "Internal Server Error"]);
        exit;
    }

    /**
     * Redirect to a specific path based on the provided key.
     *
     * @param string $key
     * @return string|null
     */
    public function redirect($key)
    {
        return $this->path[$key] ?? null;
    }

    /**
     * List all gatherings.
     *
     * @return array
     */
    public function listGatherings()
    {
        try {
            $gatherings = $this->gatheringModel->getAllGatherings();
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

    /**
     * View a specific gathering by ID.
     *
     * @param int $id
     * @return array|null
     */
    public function viewGathering($id)
    {
        try {
            return $this->gatheringModel->getGatheringById($id);
        } catch (Exception $e) {
            error_log("Error in viewGathering: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle a specific action related to gatherings.
     *
     * @return mixed|null
     */
    public function action()
    {
        try {
            return $this->gatheringModel->handleAction();
        } catch (Exception $e) {
            error_log("Error in action: " . $e->getMessage());
            return null;
        }
    }
}