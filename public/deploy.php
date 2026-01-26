<?php
// GitHub webhook deployment script
// Receives POST from GitHub and runs git pull

// Secret token - change this to something random
$secret = 'kadaorg-deploy-secret-2026';

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method not allowed');
}

// Verify GitHub signature
$payload = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_HUB_SIGNATURE_256'] ?? '';

if ($signature) {
    $expected = 'sha256=' . hash_hmac('sha256', $payload, $secret);
    if (!hash_equals($expected, $signature)) {
        http_response_code(403);
        exit('Invalid signature');
    }
}

// Change to project root (one level up from public/)
$projectRoot = dirname(__DIR__);
chdir($projectRoot);

// Run git pull
$output = [];
$returnCode = 0;
exec('/usr/bin/git checkout -- . 2>&1', $output, $returnCode);
exec('/usr/bin/git pull origin main 2>&1', $output, $returnCode);

// Log deployment
$logFile = $projectRoot . '/storage/logs/deploy.log';
$logEntry = date('Y-m-d H:i:s') . " - Deploy triggered\n";
$logEntry .= "Return code: $returnCode\n";
$logEntry .= "Output: " . implode("\n", $output) . "\n";
$logEntry .= "---\n";
@file_put_contents($logFile, $logEntry, FILE_APPEND);

// Response
http_response_code(200);
echo json_encode(['success' => $returnCode === 0, 'output' => $output]);
