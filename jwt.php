<?php

if (!class_exists('JWT')) { // Prevent redeclaration
    class JWT
    {
        private $secret;

        public function __construct(string $secret)
        {
            $this->secret = $secret;
        }

        public function createToken(array $payload, int $expirySeconds = null): string
        {
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);

            if ($expirySeconds !== null) {
                $payload['exp'] = time() + $expirySeconds;
            }

            $payload = json_encode($payload);

            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);

            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
            $base64UrlSignature = $this->base64UrlEncode($signature);

            return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
        }

        public function verifyToken(string $token): ?array
        {
            $parts = explode('.', $token);
            if (count($parts) !== 3) {
                return null;
            }

            [$base64UrlHeader, $base64UrlPayload, $base64UrlSignature] = $parts;

            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
            $decodedSignature = $this->base64UrlDecode($base64UrlSignature);

            if (!hash_equals($signature, $decodedSignature)) {
                return null;
            }

            $payload = json_decode($this->base64UrlDecode($base64UrlPayload), true);

            if (isset($payload['exp']) && $payload['exp'] < time()) {
                return null;
            }

            return $payload;
        }

        private function base64UrlEncode(string $data): string
        {
            return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
        }

        private function base64UrlDecode(string $data): string
        {
            $base64 = str_replace(['-', '_'], ['+', '/'], $data);
            return base64_decode(str_pad($base64, strlen($base64) + (4 - strlen($base64) % 4) % 4, '=', STR_PAD_RIGHT));
        }
    }
}
