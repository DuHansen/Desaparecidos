<?php
// Chave secreta para assinatura do token
const JWT_SECRET_KEY = 'sua_chave_super_secreta_123'; // Você pode trocar isso por algo mais seguro em .env

function base64UrlEncode($data) {
  return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}

function base64UrlDecode($data) {
  return base64_decode(strtr($data, '-_', '+/'));
}

function gerarJWT(array $payload): string {
  $header = ['typ' => 'JWT', 'alg' => 'HS256'];

  $payload['iat'] = time();             // Tempo de emissão
  $payload['exp'] = time() + 3600;      // Tempo de expiração (1 hora)

  $base64Header = base64UrlEncode(json_encode($header));
  $base64Payload = base64UrlEncode(json_encode($payload));

  $signature = hash_hmac('sha256', "$base64Header.$base64Payload", JWT_SECRET_KEY, true);
  $base64Signature = base64UrlEncode($signature);

  return "$base64Header.$base64Payload.$base64Signature";
}

function validarJWT(string $token): array {
  $parts = explode('.', $token);
  if (count($parts) !== 3) {
    throw new Exception('Token malformado');
  }

  [$base64Header, $base64Payload, $base64Signature] = $parts;

  $signatureCheck = base64UrlEncode(
    hash_hmac('sha256', "$base64Header.$base64Payload", JWT_SECRET_KEY, true)
  );

  if (!hash_equals($signatureCheck, $base64Signature)) {
    throw new Exception('Assinatura inválida');
  }

  $payload = json_decode(base64UrlDecode($base64Payload), true);

  if (!isset($payload['exp']) || $payload['exp'] < time()) {
    throw new Exception('Token expirado');
  }

  return $payload;
}
