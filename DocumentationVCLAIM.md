# Contoh Pembuatan Signature

**SIGNATURE**
Metode signature yang digunakan adalah menggunakan HMAC-SHA256, dimana paramater saat generate signature dibutuhkan parameter message dan key.
Berikut contoh hasil generate HMAC-SHA256
message : aaa
key : bbb
hasil generate HMAC-SHA256 : 20BKS3PWnD3XU4JbSSZvVlGi2WWnDa8Sv9uHJ+wsELA=
Diatas adalah hasil generate dari server BPJS Kesehatan

Signature : HMAC-256(value : key)
value : variabel1
key : consumerSecret
Signature : HMAC-256(variabel1 : consumerSecret)


```php
<?php 
    $data = "testtesttest";
    $secretKey = "secretkey";
            // Computes the timestamp
            date_default_timezone_set('UTC');
            $tStamp = strval(time()-strtotime('1970-01-01 00:00:00'));
            // Computes the signature by hashing the salt with the secret key as the key
    $signature = hash_hmac('sha256', $data."&".$tStamp, $secretKey, true);
    
    // base64 encode�
    $encodedSignature = base64_encode($signature);
    
    // urlencode�
    // $encodedSignature = urlencode($encodedSignature);
    
    echo "X-cons-id: " .$data ." ";
    echo "X-timestamp:" .$tStamp ." ";
    echo "X-signature: " .$encodedSignature;
?>
```

