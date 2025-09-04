<?php

declare(strict_types=1);
/**
 * 安陌直播项目 [Hyperf版本]--直播系统
 * (c) 多播网络科技有限公司。保留所有权利。
 * @anchor 多播网络科技有限公司
 */

namespace Doe\ToolHelper\DataSecurity;

use Exception;

class Crypto
{

    public $config = [
        'cipher' => 'AES-128-CBC',  // AES-192-CBC, AES-256-CBC
        'secret_key' => 'AyC91mkMTO23kNF5',
    ];

    public function __construct()
    {
    }

    /**
     * @throws Exception
     */
    public function encrypt(string $data): array
    {
        $iv = $this->getIv();
        $encrypted = openssl_encrypt($data, $this->config['cipher'], $this->config['secret_key'], 0, $iv);

        if (!$encrypted) {
            throw new Exception('Encryption failed');
        }

        return [
            'encrypted' => base64_encode($encrypted),
            'iv' => base64_encode($iv)
        ];
    }

    /**
     * @throws Exception
     */
    public function decrypt(string $data, string $iv): string
    {

        $decrypted = openssl_decrypt(base64_decode($data), $this->config['cipher'], $this->config['secret_key'], 0, base64_decode($iv));

        if (!$decrypted) {
            throw new Exception('Decryption failed');
        }

        return $decrypted;
    }

    public function setConfig(array $config = []): Crypto
    {
        $config && $this->config = array_merge($this->config, $config);
        return $this;
    }

    public function getIv(): string
    {
        $ivLength = openssl_cipher_iv_length($this->config['cipher']); // 获取IV长度
        return openssl_random_pseudo_bytes($ivLength); // 生成随机IV
    }
}
