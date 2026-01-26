<?php
// Simple deployment webhook for GitHub
// This script receives POST from GitHub webhook and runs git pull

// Security: Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Change to project root directory
$projectRoot = dirname(__FILE__);
chdir($projectRoot);

// Run git pull
$output = [];
$returnCode = 0;
exec('git checkout -- . 2>&1', $output, $returnCode);
exec('git pull origin main 2>&1', $output, $returnCode);

// Log the deployment
$logFile = $projectRoot . '/storage/logs/deploy.log';
$logEntry = date('Y-m-d H:i:s') . " - Deploy triggered\n";
$logEntry .= "Return code: $returnCode\n";
$logEntry .= "Output: " . implode("\n", $output) . "\n";
$logEntry .= "---\n";
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Return response
http_response_code(200);
echo json_encode([
    'success' => $returnCode === 0,
    'output' => $output
]);
