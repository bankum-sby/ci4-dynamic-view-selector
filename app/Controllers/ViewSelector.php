<?php

namespace App\Controllers;

class ViewSelector extends BaseController
{
    /**
     * Display the main dashboard with selection buttons
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * Handle AJAX requests to load different views
     * Expects POST request with 'view' parameter
     */
    public function loadView()
    {
        $this->response->setContentType('application/json');

        // Get the view name from the request
        $viewName = $this->request->getPost('view');

        // Validate the view name to prevent directory traversal
        $allowedViews = ['dashboard', 'profile', 'settings', 'analytics'];

        if (!$viewName || !in_array($viewName, $allowedViews)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid view requested',
                'status' => 400
            ]);
        }

        try {
            // Load and render the requested view
            $viewPath = 'view_' . $viewName;
            $content = view($viewPath);

            return $this->response->setJSON([
                'success' => true,
                'content' => $content,
                'view' => $viewName,
                'status' => 200
            ]);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Error loading view: ' . $e->getMessage(),
                'status' => 500
            ]);
        }
    }
}
