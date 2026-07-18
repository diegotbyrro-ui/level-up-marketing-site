<?php
declare(strict_types=1);
session_start([
  'cookie_httponly' => true,
  'cookie_secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
  'cookie_samesite' => 'Strict',
  'use_strict_mode' => true,
]);
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { http_response_code(405); echo json_encode(['ok'=>false,'error'=>'Método não permitido.']); exit; }
if (empty($_SESSION['crm_authenticated'])) { http_response_code(401); echo json_encode(['ok'=>false,'error'=>'Sua sessão expirou. Entre novamente no CRM.']); exit; }
$apiKey = getenv('OPENAI_API_KEY') ?: '';
if ($apiKey === '') { http_response_code(503); echo json_encode(['ok'=>false,'error'=>'O assistente ainda não foi ativado no servidor.']); exit; }
http_response_code(503);
echo json_encode(['ok'=>false,'error'=>'A chave foi localizada, mas a integração final ainda precisa ser habilitada.']);
