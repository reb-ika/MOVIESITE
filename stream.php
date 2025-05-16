<?php
session_start();

// Optional: Block if user is not logged in
if (!isset($_SESSION['user'])) {
  http_response_code(403);
  exit("Access denied");
}

$filename = basename($_GET['file'] ?? '');
$filepath = __DIR__ . "/uploads/$filename";

if (!file_exists($filepath)) {
  http_response_code(404);
  exit("File not found");
}

$filesize = filesize($filepath);
$offset = 0;
$length = $filesize;

// Check for HTTP_RANGE header
if (isset($_SERVER['HTTP_RANGE'])) {
  preg_match('/bytes=(\d+)-/', $_SERVER['HTTP_RANGE'], $matches);
  $offset = intval($matches[1]);
  $length = $filesize - $offset;
  header("HTTP/1.1 206 Partial Content");
}

$fp = fopen($filepath, 'rb');
fseek($fp, $offset);

header("Content-Type: video/mp4");
header("Content-Length: " . $length);
header("Accept-Ranges: bytes");
header("Content-Range: bytes $offset-" . ($filesize - 1) . "/$filesize");

$buffer = 1024 * 8;
while (!feof($fp) && $length > 0) {
  echo fread($fp, $buffer);
  flush();
  $length -= $buffer;
}

fclose($fp);
exit;
