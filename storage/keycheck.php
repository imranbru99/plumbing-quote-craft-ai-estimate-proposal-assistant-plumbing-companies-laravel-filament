<?php
require '/var/www/html/vendor/autoload.php';
$app = require '/var/www/html/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$key = config('app.key');
$cipher = config('app.cipher');
echo "cipher=$cipher\n";
echo "key=$key\n";
echo "env=" . env('APP_KEY') . "\n";
echo "getenv=" . getenv('APP_KEY') . "\n";

$provider = new ReflectionClass(Illuminate\Encryption\EncryptionServiceProvider::class);
// mimic parseKey
$k = $key;
if (str_starts_with($k, 'base64:')) {
    $raw = base64_decode(substr($k, 7));
} else {
    $raw = $k;
}
echo "raw_len=" . strlen($raw) . "\n";
echo "raw_hex_prefix=" . bin2hex(substr($raw, 0, 4)) . "\n";

try {
    $e = app('encrypter');
    echo "app_encrypter_ok\n";
} catch (Throwable $t) {
    echo "app_encrypter_fail=" . $t->getMessage() . "\n";
}

try {
    $e2 = new Illuminate\Encryption\Encrypter($raw, $cipher);
    echo "manual_ok\n";
} catch (Throwable $t) {
    echo "manual_fail=" . $t->getMessage() . "\n";
    // try lowercase cipher
    try {
        $e3 = new Illuminate\Encryption\Encrypter($raw, strtolower($cipher));
        echo "lowercase_ok\n";
    } catch (Throwable $t2) {
        echo "lowercase_fail=" . $t2->getMessage() . "\n";
    }
}

// dump supported sizes from source if possible
$ref = new ReflectionClass(Illuminate\Encryption\Encrypter::class);
if ($ref->hasProperty('supportedCiphers')) {
    $p = $ref->getProperty('supportedCiphers');
    $p->setAccessible(true);
    // might be static
    echo "supported=" . json_encode($p->getValue()) . "\n";
}
