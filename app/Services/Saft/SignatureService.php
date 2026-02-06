<?php

namespace App\Services\Saft;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use OpenSSLAsymmetricKey;

class SignatureService
{
    // A chave privada guardada na memória como objeto/recurso OpenSSL
    protected OpenSSLAsymmetricKey|bool $privateKeyResource;

    public function __construct()
    {
        // 1. Caminho absoluto
        $path = storage_path('app/agt/keys/private_key.pem');

        if (!file_exists($path)) {
            throw new Exception("CRÍTICO: O ficheiro de chave não existe em: " . $path);
        }

        // 2. Ler conteúdo do ficheiro
        $content = file_get_contents($path);

        if (!$content) {
             throw new Exception("ERRO: O ficheiro private_key.pem está vazio ou não pode ser lido.");
        }

        // 3. Transformar texto em Chave OpenSSL válida
        // Isto vai validar se o formato (BEGIN RSA PRIVATE KEY...) está correto
        $this->privateKeyResource = openssl_pkey_get_private($content);

        if ($this->privateKeyResource === false) {
            $msg = "";
            while ($sslErr = openssl_error_string()) { $msg .= $sslErr . "; "; }
            throw new Exception("ERRO: Chave privada inválida/corrompida. OpenSSL: " . $msg);
        }
    }

    public function signDocument(Model $document, string $docType, string $previousHash = ""): string
    {
        // --- Formatação dos Dados conforme AGT ---

        $dateDoc = Carbon::parse($document->data_emissao)->format('Y-m-d');

        // Se system_entry_date for null, define "agora". Senão usa a que já existe.
        $sysDate = $document->system_entry_date ? Carbon::parse($document->system_entry_date) : Carbon::now();
        $dateSystem = $sysDate->format('Y-m-d\TH:i:s');

        // Atualiza a model com a data usada (importante para salvar no DB)
        $document->system_entry_date = $sysDate;

        $docNumber = $document->numero;

        // Fatura tem 'total', Recibo tem 'valor'
        $val = $document->total ?? $document->valor ?? 0;
        $grossTotal = number_format($val, 2, '.', '');

        $prevHash = $previousHash ?: "";

        // String para assinar
        $plainText = "{$dateDoc};{$dateSystem};{$docNumber};{$grossTotal};{$prevHash}";

        // --- Debug Opcional (Ver no Laravel.log) ---
        // \Log::info("SAFT STRING: " . $plainText);

        // --- Assinatura ---
        $signature = '';
        $algo = OPENSSL_ALGO_SHA1;

        // Usamos o recurso carregado no construtor
        $ok = openssl_sign($plainText, $signature, $this->privateKeyResource, $algo);

        if (!$ok) {
            throw new Exception("Falha ao criar assinatura digital no OpenSSL.");
        }

        return base64_encode($signature);
    }
}
